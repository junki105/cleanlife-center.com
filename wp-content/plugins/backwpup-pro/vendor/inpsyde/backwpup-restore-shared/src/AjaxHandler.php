<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore;

use Inpsyde\Restore\Api\Controller\DecryptController;
use Inpsyde\Restore\Api\Controller\JobController;
use Inpsyde\Restore\Api\Controller\LanguageController;
use Inpsyde\Restore\Api\Module\Decryption\Exception\DecryptException;
use Inpsyde\Restore\Api\Module\Registry;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Ajax
 */
class AjaxHandler
{
    const EVENT_SOURCE_CONTEXT = 'event_source';

    /**
     * Ajax Hooks
     *
     * @var array List of the ajax hooks for the actions to perform.
     */
    private static $hooks = array(
        'download',
        'decompress_upload',
        'decrypt',
        'get_strategy',
        'switch_language',
        'save_strategy',
        'db_test',
        'restore_db',
        'restore_dir',
        'upload',
    );

    /**
     * Action Controller
     *
     * @var mixed A Controller based on the requested action
     */
    private $controller;

    /**
     * Action
     *
     * @var string mixed The action to perform
     */
    private $action;

    /**
     * @var JobController
     */
    private $jobController;

    /**
     * @var LanguageController
     */
    private $languageController;

    /**
     * @var DecryptController
     */
    private $decryptController;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventSource
     */
    private $eventSource;

    /**
     * @var string
     */
    private $logFilePath;

    /**
     * AjaxHandler constructor.
     * @param JobController $jobController
     * @param LanguageController $languageController
     * @param DecryptController $decryptController
     * @param Registry $registry
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @param EventSource $eventSource
     * @param string $logFilePath
     */
    public function __construct(
        JobController $jobController,
        LanguageController $languageController,
        DecryptController $decryptController,
        Registry $registry,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        EventSource $eventSource,
        $logFilePath
    ) {

        list($controller, $action) = $this->prepare();

        $this->jobController = $jobController;
        $this->languageController = $languageController;
        $this->decryptController = $decryptController;
        $this->registry = $registry;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->eventSource = $eventSource;
        $this->logFilePath = $logFilePath;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * Load Hooks
     *
     * @return $this For concatenation
     */
    public function load()
    {
        // Not in standalone.
        if (!function_exists('add_action')) {
            return $this;
        }

        foreach (self::$hooks as $hook) {
            add_action("wp_ajax_{$hook}", array($this, 'dispatch'));
        }

        return $this;
    }

    /**
     * Dispatch Request
     *
     * @return void
     */
    public function dispatch()
    {
        if (!$this->verify_request()) {
            return;
        }

        try {
            $response = '';
            $action = $this->action . '_action';
            $case = strtolower($this->controller);

            switch ($case) {
                case 'job':
                    $response = $this->handle_job($action);
                    break;
                case 'language':
                    $response = $this->handle_language($action);
                    break;
                case 'decrypt':
                    $response = $this->handle_decrypt();
                    break;
                default:
                    break;
            }

            // Clean the registry if we have done.
            if ($this->registry->is_restore_finished()) {
                $this->registry->reset_registry();
            }

            // Sent the response to the client.
            $this->handle_json_response($response);
        } catch (\Exception $e) {
            $this->handle_catch_exception($e);
        }
    }

    /**
     * Handle job action
     *
     * @param string $action The action to handle.
     *
     * @return string|array The output to send back to the client
     */
    public function handle_job($action)
    {
        $response = '';

        if (method_exists($this, $action)) {
            $response = $this->$action();
        } elseif (method_exists($this->jobController, $action)) {
            $response = $this->jobController->$action();
        }

        return $response;
    }

    /**
     * Handle Language Request
     *
     * @param string $action The action to perform.
     *
     * @return string $output The response of the controller.
     */
    public function handle_language($action)
    {
        $response = '';

        if (method_exists($this->languageController, $action)) {
            // phpcs:ignore
            $locale = isset($_POST['locale'])
                ? filter_var($_POST['locale'], FILTER_SANITIZE_STRING)
                : '';

            if ($locale) {
                $response = $this->languageController->$action($locale);
            }
        }

        return $response;
    }

    /**
     * Handle the decryption
     *
     * @return mixed
     * @throws DecryptException
     */
    public function handle_decrypt()
    {
        $encrypted_file = '';

        // phpcs:disable
        if (!isset($_REQUEST['decryption_key'])
            || (isset($_REQUEST['decryption_key']) && '' === $_REQUEST['decryption_key'])
        ) {
            // phpcs:enable
            throw new DecryptException(
                $this->translator->trans(
                    'You tried to decrypt a backup but you didn\'t sent any decryption key'
                )
            );
        }

        // phpcs:ignore
        $key = filter_var($_REQUEST['decryption_key'], FILTER_SANITIZE_STRING);

        // phpcs:ignore
        if (isset($_REQUEST['encrypted_file_path'])) {
            $encrypted_file = (string)filter_var(
                $_REQUEST['encrypted_file_path'],
                FILTER_SANITIZE_STRING
            );
        }
        if (!$encrypted_file && $this->registry->uploaded_file) {
            $encrypted_file = $this->registry->uploaded_file;
        }

        if (!$encrypted_file) {
            throw new DecryptException(
                $this->translator->trans('Backup cannot be decrypted, file has not been found.')
            );
        }

        if ($this->decryptController->decrypt($key, $encrypted_file)) {
            return $this->translator->trans('Backup decrypted successful.');
        }
    }

    /**
     * Save strategy
     *
     * @return string $output The output to send back to the client
     * @throws RuntimeException
     */
    public function save_strategy_action()
    {
        $strategy = (string)filter_input(INPUT_POST, 'strategy', FILTER_SANITIZE_STRING);

        if (!$strategy) {
            throw new RuntimeException(
                $this->translator->trans('You have to select one strategy.')
            );
        }

        $this->jobController->save_strategy_action($strategy);
    }

    /**
     * Restore Database
     *
     * @return mixed Whatever the `restore_db_action` returns
     */
    public function restore_db_action()
    {
        $this->eventSource
            ->increaseResources()
            ->setHeaders();

        return $this->jobController->restore_db_action();
    }

    /**
     * Restore Directory
     *
     * @return mixed Whatever the `restore_dir_action` returns
     */
    public function restore_dir_action()
    {
        $this->eventSource
            ->increaseResources()
            ->setHeaders();

        return $this
            ->jobController
            ->restore_dir_action();
    }

    /**
     * Db Test
     *
     * Test the connection to the db
     *
     * @return array The response text of the test
     */
    public function db_test_action()
    {
        $response = array();

        // phpcs:ignore
        if (!isset($_POST['db_settings'])) {
            return $response;
        }

        // Clean up the json settings data since Wp additional add slashes to the value.
        // phpcs:ignore
        $db_settings = backwpup_clean_json_from_request($_POST['db_settings']);

        if ($db_settings && $this->jobController->db_test_action($db_settings)) {
            $response['message'] = $this->translator->trans('Connection to Database Successful.');
            $response['charset'] = $this->registry->dbcharset ?: false;
        }

        return $response;
    }

    /**
     * Download Action
     *
     * AJAX method to trigger the download.
     *
     * @return bool True on success, throw exception on failure.
     * @throws \Exception If something goes wrong with the download.
     *
     */
    public function download_action()
    {
        // Clean the restore.log.
        file_put_contents($this->logFilePath, '', LOCK_EX); // phpcs:ignore

        $this->eventSource->setHeaders();

        // phpcs:disable
        $source_file_path = filter_var($_GET['source_file_path'], FILTER_SANITIZE_STRING);
        $service = filter_var($_GET['service'], FILTER_SANITIZE_STRING);
        $job_id = filter_var($_GET['jobid'], FILTER_SANITIZE_NUMBER_INT);
        $local_file_path = filter_var($_GET['local_file_path'], FILTER_SANITIZE_STRING);
        // phpcs:enable
        $local_file_path = \BackWPup_Sanitize_Path::sanitize_path($local_file_path);

        $this->jobController->download_action(
            (int)$job_id,
            $service,
            $source_file_path,
            $local_file_path
        );
    }

    /**
     * Decompress Upload Action
     *
     * AJAX method to trigger the decompression and save path to registry.
     *
     * @return bool True on success, throw exception on failure.
     * @throws \Exception If something goes wrong with the decompression.
     *
     */
    public function decompress_upload_action()
    {
        $this->eventSource
            ->increaseResources()
            ->setHeaders();

        // We may pass or not a file path, depends from where the decompression action is started.
        // From the backups page we have a file path but when we upload it we don't.
        // phpcs:disable
        $file_path = isset($_GET['file_path'])
            ? filter_var($_GET['file_path'], FILTER_SANITIZE_STRING)
            : '';
        // phpcs:enable
        $file_path = \BackWPup_Sanitize_Path::sanitize_path($file_path);

        $this->jobController->decompress_upload_action($file_path);

        return true;
    }

    /**
     * Prepare request
     *
     * @return array $array An array containing the controller and the action requested
     */
    private function prepare()
    {
        $output = array(
            'controller' => 'Job',
            'action' => 'index',
        );

        foreach ($output as $key => $value) {
            // phpcs:ignore
            $param = isset($_REQUEST[$key]) ? filter_var($_REQUEST[$key], FILTER_DEFAULT) : '';

            if ($param) {
                $output[$key] = $param;
            }
        }

        $output['controller'] = ucfirst($output['controller']);

        return array(
            $output['controller'],
            $output['action'],
        );
    }

    /**
     * Verify Request
     *
     * @return bool True if nonce is verified, false if not set. Will die if nonce isn't a valid one.
     */
    private function verify_request()
    {
        // Retrieve nonce value and return silently if not set, but if it's set, die if not a valid one.
        // phpcs:ignore
        if (!isset($_REQUEST['backwpup_action_nonce'])) {
            return false;
        }

        return check_ajax_referer('backwpup_action_nonce', 'backwpup_action_nonce')
            && current_user_can('backwpup_restore');
    }

    /**
     * Handle Response
     *
     * @param mixed $response The response data to send back to the client.
     *
     * @return void
     */
    private function handle_json_response($response)
    {
        // phpcs:disable
        $context = isset($_REQUEST['context'])
            ? filter_var($_REQUEST['context'], FILTER_SANITIZE_STRING)
            : '';
        // phpcs:enable

        if (!is_array($response)) {
            $response = array(
                'message' => (string)$response,
            );
        }

        // EventSource Request.
        if (self::EVENT_SOURCE_CONTEXT === $context) {
            // Set the state to done.
            $response['state'] = 'done';

            $this->eventSource->response('message', $response);

            return;
        }

        wp_send_json_success($response, 200);
    }

    /**
     * Handle Catch Exception
     *
     * @param \Exception $e The exception to handle.
     */
    private function handle_catch_exception(\Exception $e)
    {
        $context = '';

        // phpcs:ignore
        if (isset($_REQUEST['context'])) {
            $context = (string)filter_var($_REQUEST['context'], FILTER_SANITIZE_STRING);
        }

        $this->logger->alert($e->getMessage());

        if (!$e instanceof DecryptException) {
            $this->registry->reset_registry();
        }

        if ($context === self::EVENT_SOURCE_CONTEXT) {
            $jsonData = wp_json_encode(array(
                'state' => 'error',
                'message' => $e->getMessage(),
            ));
            echo "event: log\n";
            echo "data: {$jsonData}\n\n"; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
            flush();

            return;
        }

        // Set a feedback to the user.
        wp_send_json_error(
            array(
                'state' => 'error',
                'message' => $e->getMessage(),
            )
        );
    }
}
