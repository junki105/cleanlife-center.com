<?php

use  ILJ\Core\Options ;
use  ILJ\Database\Linkindex ;
use  ILJ\Database\Postmeta ;
use  ILJ\Database\Usermeta ;
/**
 * Responsible for removing database stuff on plugin deinstallation
 *
 * @since 1.2.2
 */
function ilj_remove_db_data()
{
    $keep_settings = Options::getOption( \ILJ\Core\Options\KeepSettings::getKey() );
    if ( $keep_settings ) {
        return;
    }
    Options::removeAllOptions();
    Postmeta::removeAllLinkDefinitions();
    Usermeta::removeAllUsermeta();
}

function ilj_uninstall_db()
{
    global  $wpdb ;
    $query_linkindex = "DROP TABLE IF EXISTS " . $wpdb->prefix . Linkindex::ILJ_DATABASE_TABLE_LINKINDEX . ";";
    $wpdb->query( $query_linkindex );
}

\ILJ\ilj_fs()->add_action( 'after_uninstall', '\\ilj_remove_db_data' );
\ILJ\ilj_fs()->add_action( 'after_uninstall', '\\ilj_uninstall_db' );