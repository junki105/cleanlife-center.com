<?php

namespace ILJ\Database;

use ILJ\Backend\User;

/**
 * Usermeta wrapper
 *
 * @package ILJ\Database
 * @since   1.1.3
 */
class Usermeta
{
    /**
     * Removes all user meta data from the database
     *
     * @since  1.1.3
     * @return int
     */
    public static function removeAllUsermeta()
    {
        global $wpdb;
        $meta_key = User::ILJ_META_USER;
        return $wpdb->delete($wpdb->usermeta, array( 'meta_key' => $meta_key ));
    }
}
