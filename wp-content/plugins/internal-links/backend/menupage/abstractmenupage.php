<?php
namespace ILJ\Backend\MenuPage;

use ILJ\Backend\AdminMenu;
use ILJ\Backend\MenuPage\Includes\Headline;
use ILJ\Backend\MenuPage\Includes\Postbox;
use ILJ\Backend\MenuPage\Includes\Sidebar;

/**
 * Abstract menu page
 *
 * Abstract class for all menupage types
 *
 * @package ILJ\Backend\Menupage
 * @since   1.0.0
 */
abstract class AbstractMenuPage
{
    use Headline;
    use Sidebar;
    use Postbox;

    /**
     * @var   string
     * @since 1.0.0
     */
    protected $page_slug;

    /**
     * @var   string
     * @since 1.0.0
     */
    protected $page_title;

    /**
     * @var   string
     * @since 1.0.0
     */
    protected $page_hook;

    /**
     * Returns the slug of the menu page
     *
     * @since  1.0.0
     * @return string
     */
    public function getSlug()
    {
        return $this->page_slug;
    }

    /**
     * Returns the title of the menu page
     *
     * @since  1.0.0
     * @return string
     */
    public function getTitle()
    {
        return $this->page_title;
    }

    /**
     * Adds the submenu page
     *
     * @since  1.0.0
     * @param  bool $first_level Flag for the base menu page
     * @return void
     */
    protected function addSubMenuPage($first_level = false)
    {
        $submenu = add_submenu_page(
            AdminMenu::ILJ_MENUPAGE_SLUG,
            $this->getTitle() . ' - Internal Link Juicer',
            $this->getTitle(),
            'manage_options',
            ($first_level ? AdminMenu::ILJ_MENUPAGE_SLUG : AdminMenu::ILJ_MENUPAGE_SLUG . '-' . $this->getSlug()),
            [$this, 'render']
        );
        $this->page_hook = $submenu;
    }

    /**
     * Adds all assets to the menu page
     *
     * @since  1.0.0
     * @param  array $js  The JS handles to register
     * @param  array $css The CSS handles to register
     * @return void
     */
    protected function addAssets(array $js, array $css)
    {
        add_action(
            'admin_enqueue_scripts', function () use ($js, $css) {
                $screen = get_current_screen();

                if ($screen->base !== $this->page_hook) {
                    return;
                }

                foreach ($js as $handle => $path) {
                    wp_enqueue_script($handle, $path, [], ILJ_VERSION);
                }

                foreach ($css as $handle => $path) {
                    wp_enqueue_style($handle, $path, [], ILJ_VERSION);
                }
            }
        );
    }

    /**
     * Registers the menu page in the environment
     *
     * @since  1.0.0
     * @return void
     */
    abstract public function register();

    /**
     * Renders the menu page
     *
     * @since  1.0.0
     * @return void
     */
    abstract public function render();
}
