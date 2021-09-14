<?php

namespace ILJ\Backend;

/**
 * Admin menu page
 *
 * Manages the plugin gui in the backend
 *
 * @package ILJ\Backend
 * @since   1.0.0
 */
class AdminMenu
{
    const  ILJ_MENUPAGE_SLUG = 'internal_link_juicer' ;
    /**
     * Initializes the building process
     *
     * @since  1.0.0
     * @return void
     */
    public static function init()
    {
        self::addMenuPage();
        $submenus = [
            'ILJ\\Backend\\MenuPage\\Dashboard',
            'ILJ\\Backend\\MenuPage\\Tools',
            'ILJ\\Backend\\MenuPage\\Settings',
            'ILJ\\Backend\\MenuPage\\Tour'
        ];
        foreach ( $submenus as $submenu ) {
            $menu_page = new $submenu();
            $menu_page->register();
        }
    }
    
    /**
     * Registers the menu page for the plugin
     *
     * @since  1.0.0
     * @return void
     */
    private static function addMenuPage()
    {
        add_menu_page(
            __( 'Internal Links', 'internal-links' ),
            __( 'Internal Links', 'internal-links' ),
            'manage_options',
            self::ILJ_MENUPAGE_SLUG,
            function () {
            return;
        },
            'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" width="150" height="150"><path fill="#ffffff" d="M115.1334 73.9667c-4.5667-6.8334-9.5667-13.4-14.4334-20.0334-8.2-11.0333-13.5-23.1-12.8666-37.1.2333-5.4 1.2333-10.7333 1.9-16.3333L80.4 8.9333c-15.7666 14.9-29.9 31.1334-40.3666 50.3-6.7667 12.4334-11.5667 25.5-12.6667 39.8-2.0333 24.6667 16.9667 48.2 41.5667 51.0667 27.1333 3.2333 50.3667-14.4 54.3-41.3333 1.8666-12.5667-1.1334-24.3-8.1-34.8zm-15.9 40.9c-4.9667 0-9.2-3.3333-10.4334-7.9l-15.6666 2.6v.5c0 8.8334-7.2334 16.0667-16.1 16.0667-8.8334 0-16.0667-7.2333-16.0667-16.0667 0-8.8666 7.2333-16.1 16.0667-16.1 1.2 0 2.3333.1 3.5.4l7.6303-21.9204C64.5334 70.7666 62.0334 67.1 62.0334 62.8c0-6.0334 4.9-10.8667 10.9-10.8667 5.9666 0 10.9 4.8333 10.9 10.8667s-4.9334 10.8333-10.9 10.8333c-.8667 0-1.7334-.1-2.5667-.3333l-7.5333 21.7333C67.8 97 71.6334 101.3333 72.8 106.6l15.5667-2.5667c0-6.0667 4.8667-10.9 10.9-10.9 6 0 10.8333 4.8333 10.8333 10.9-.0333 6-4.8333 10.8333-10.8666 10.8333z"/></svg>' ),
            16
        );
    }

}