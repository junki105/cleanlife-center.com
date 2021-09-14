<?php
namespace ILJ\Backend\MenuPage;

use ILJ\Backend\AdminMenu;

/**
 * Interactive Tour
 *
 * Builds a guided tour for a comfortable plugin onboarding
 *
 * @package ILJ\Backend\MenuPage
 *
 * @since 1.1.0
 */
class Tour extends AbstractMenuPage
{

    const ILJ_MENUPAGE_TOUR_SLUG = 'tour';
    const ILJ_TOUR_HANDLE        = 'ilj_tour';

    /**
     * @var   array
     * @since 1.1.0
     */
    private $steps = [];

    /**
     * @var   string
     * @since 1.1.0
     */
    private $action = '';

    /**
     * @var   Step
     * @since 1.1.0
     */
    private $current_step = null;

    public function __construct()
    {
        $this->steps = [
            [
                'slug' => 'intro',
                'src'  => '\ILJ\Backend\MenuPage\Tour\Intro'
            ],
            [
                'slug' => 'editor',
                'src'  => '\ILJ\Backend\MenuPage\Tour\Editor'
            ],
            [
                'slug' => 'links',
                'cta'  => __('Adjust the link behavior', 'internal-links'),
                'src'  => '\ILJ\Backend\MenuPage\Tour\Links'
            ],
            [
                'slug' => 'settings',
                'cta'  => __('Discover the most important settings', 'internal-links'),
                'src'  => '\ILJ\Backend\MenuPage\Tour\Settings'
            ],
            [
                'slug' => 'pro',
                'cta'  => __('Advanced linking with pro version', 'internal-links'),
                'src'  => '\ILJ\Backend\MenuPage\Tour\Pro'
            ]
        ];

        if (isset($_GET['action'])) {
            $this->action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        }

        $this->page_slug  = self::ILJ_MENUPAGE_TOUR_SLUG;
        $this->page_title = __('Interactive Tour', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        if (!$this->isTourPage()) {
            return;
        }

        $this->addSubmenuPage();

        wp_enqueue_style(self::ILJ_TOUR_HANDLE, ILJ_URL . 'admin/css/ilj_tour.css', ['wp-admin', 'buttons'], ILJ_VERSION);

        add_action('admin_init', [$this, 'render'], 30);
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        if (!$this->isTourPage()) {
            return;
        }

        $this->setCurrentStep();

        if (ob_get_length()) {
            ob_end_clean();
        }

        ob_start();
        $this->renderHeader();
        $this->current_step->renderContent();
        $this->renderFooter();
        exit;
    }

    /**
     * Checks if the current requested page is a tour page
     *
     * @since  1.1.0
     * @return boolean
     */
    protected function isTourPage()
    {
        if (isset($_GET['page']) && $_GET['page'] == AdminMenu::ILJ_MENUPAGE_SLUG . '-' . self::ILJ_MENUPAGE_TOUR_SLUG) {
            return true;
        }
        return false;
    }

    /**
     * Sets the current step
     *
     * @since  1.1.0
     * @return void
     */
    protected function setCurrentStep()
    {
        if (isset($_GET['step'])) {
            for ($i = 0; $i < count($this->steps); $i++) {
                if ($this->steps[$i]['slug'] == $_GET['step']) {
                    $this->current_step = new $this->steps[$i]['src']();
                    return;
                }
            }
        }

        $this->current_step = new $this->steps[0]['src']();
    }

    /**
     * Renders the header for a tour page
     *
     * @since  1.1.0
     * @return void
     */
    protected function renderHeader()
    {
        echo '<!DOCTYPE html>';
        echo '<html ' . get_language_attributes() . '>';
        echo '<head>';
        echo '<meta name="viewport" content="width=device-width"/>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        echo '<title>' . $this->getTitle() . ' - Internal Link Juicer</title>';
        wp_print_head_scripts();
        wp_print_styles(self::ILJ_TOUR_HANDLE);
        echo '</head>';
        echo '<body class="ilj-' . self::ILJ_MENUPAGE_TOUR_SLUG . ' wp-core-ui">';
        echo '<div id="wrap">';
        echo '<header>';
        echo '<div class="box">';
        echo '<img src="' . ILJ_URL . 'admin/img/ilj-icon-inverted.png" alt="Internal Link Juicer Logo" />';
        echo '</div><div class="box">';
        echo '<h1>Internal Link Juicer</h1>';
        echo '<p class="subline">' . __('Interactive tutorial', 'internal-links') . '</p>';
        echo '</div><div class="clear"></div>';
        echo '</header>';
        echo '<main>';
    }

    /**
     * Renders the footer for a tour page
     *
     * @since  1.1.0
     * @return void
     */
    protected function renderFooter()
    {
        $previous_step = $this->getStepNavigation('prev');
        $next_step     = $this->getStepNavigation('next');

        $next_label     = (isset($next_step['cta'])) ? $next_step['cta'] : ((null === $previous_step) ? __('Start the tutorial now', 'internal-links') : ((null === $next_step) ? __('Finish tutorial', 'internal-links') : __('Next page', 'internal-links')));
        $previous_label = $this->action == 'after-activation' ? __('Skip interactive tour', 'internal-links') : __('Back to dashboard', 'internal-links');
        $dashboard_url  = add_query_arg(['page' => AdminMenu::ILJ_MENUPAGE_SLUG], admin_url('admin.php'));

        if (null === $next_step) {
            $next_step     = ['url' => $dashboard_url];
            $dashboard_url = null;
        }

        echo '</main>';
        echo '<footer>';
        echo '<div class="left">' . ($previous_step ? sprintf('<a href="%1$s" class="button">&lsaquo; ' . __('Previous page', 'internal-links') . '</a>', $previous_step['url']) : '') . '</div><div class="' . ($previous_step ? 'right' : ' only') . '">' . sprintf('<a href="%1$s" class="button button-primary">%2$s &rsaquo;</a>', $next_step['url'], $next_label) . '</div><div class="clear"></div>';
        echo '</footer>';
        echo '</div>';
        echo (null !== $dashboard_url) ? '<div class="leave"><a class="button" href="' . $dashboard_url . '">&lsaquo; ' . $previous_label . '</a></div>' : '';
        echo '</body>';
        wp_print_footer_scripts();
        echo '</html>';
    }

    /**
     * Get a step based navigation for the current tour page
     *
     * @since  1.1.0
     * @param  string $direction
     * @return array|null
     */
    protected function getStepNavigation($direction)
    {

        $navigation = null;

        if (!in_array($direction, ['next', 'prev'])) {
            return $navigation;
        }

        for ($i = 0; $i < count($this->steps); $i++) {
            if ($this->steps[$i]['src'] == '\\' . get_class($this->current_step)) {

                if (($direction == 'next' && $i === count($this->steps) - 1)
                    || ($direction == 'prev' && $i === 0)
                ) {
                    return $navigation;
                }

                $index = ($direction == 'next') ? ($i + 1) : ($i - 1);

                $navigation = $this->steps[$index];
                break;
            }
        }

        $navigation['url'] = add_query_arg(
            [
                'page' => AdminMenu::ILJ_MENUPAGE_SLUG . '-' . self::ILJ_MENUPAGE_TOUR_SLUG,
                'step' => $navigation['slug']
            ], admin_url('admin.php')
        );

        return $navigation;
    }

}
