<?php
namespace PublishPress\Capabilities;

class Maint {
    public static function callHome($request_topic, $request_vars = [], $post_vars = false)
    {
        $request_vars = array_merge((array)$request_vars, ['PPServerRequest' => $request_topic]);

        $args = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
                'User-Agent' => 'WordPress/' . get_bloginfo("version"),
                'Referer' => get_bloginfo("url")
            ],
        ];

        $timeout = in_array($request_topic, ['update-check', 'changelog'], true) ? 8 : 30;
        
        $body = (false !== $post_vars) ? $post_vars : array_merge($request_vars, ['url' => site_url()]);

        try {
        $server_response = wp_remote_post(
            'https://publishpress.com/',
            [
            'timeout'   => $timeout,
            'sslverify' => true,
            'body'      => $body,
            ]
        );

        $const = 'PRESSPERMIT_DEBUG_' . strtoupper(str_replace('-', '_', $request_topic));
        if (is_admin() && defined($const) && constant($const)) {
            if (defined('PRESSPERMIT_DEBUG') && ('var_dump' !== constant($const))) {
                pp_dump($server_response);
                pp_backtrace_dump();
            } else {
                var_dump($server_response);
                die('--- PP TEST ---');
            }
        }

        // Is the response an error?
        if (is_wp_error($server_response) || 200 !== wp_remote_retrieve_response_code($server_response)) {
            $message = (is_wp_error($server_response)) ? $server_response->get_error_message() : '';

            if (empty($message)) {
                throw new \Exception(__('An error occurred. Please try again or contact the support team.', 'capsman-enhanced'));
            } else {
                throw new \Exception($message);
            }
        }

        $json_response = wp_remote_retrieve_body($server_response);

        // Convert data response to an object.
        $data = json_decode($json_response);

        // Do we have empty data? Throw an error.
        if (empty($data) || ! is_object($data)) {
            throw new \Exception(__('An error occurred. Please try again or contact the support team.', 'capsman-enhanced'));
        }
        } catch (\Exception $e) {
        return $e->getMessage();
        }

        return $json_response;
    }

    public static function isMuPlugin($plugin_path = '')
    {
        if ( ! $plugin_path && defined(CME_FILE) ) {
            $plugin_path = CME_FILE;
        }
        return (defined('WPMU_PLUGIN_DIR') && (false !== strpos($plugin_path, WPMU_PLUGIN_DIR)));
    }

    public static function isNetworkActivated($plugin_file = '')
    {
        if ( ! $plugin_file && defined('CME_FILE') ) {
            $plugin_file = plugin_basename(CME_FILE);
        }
        return (array_key_exists($plugin_file, (array)maybe_unserialize(get_site_option('active_sitewide_plugins'))));
    }
}
