<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'JVCF7P_STORE_URL', 'https://dnesscarkey.com/jquery-validation' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file 

// the name of your product. This should match the download name in EDD exactly
define( 'JVCF7P_ITEM_NAME', 'Jquery Validation for Contact Form 7 Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function jvcf7p_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'jvcf7p_license_key' ) );

	// setup the updater
	$jvcf7p_updater = new EDD_SL_Plugin_Updater( JVCF7P_STORE_URL, 'jquery-validation-for-contact-form-7-pro/jquery-validation-for-contact-form-7-pro.php', array( 
			'version' 	=> '4.3', 			// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => JVCF7P_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Dinesh Karki'  // author of this plugin
		)
	);

}
add_action( 'admin_init', 'jvcf7p_plugin_updater', 0 );


function jvcf7p_license_page() {
	$license 	= get_option( 'jvcf7p_license_key' );
	$status 	= get_option( 'jvcf7p_license_status' );
	include('includes/jvcf7p_license.php');
}

function jvcf7p_sanitize_license( $new ) {
	$old = get_option( 'jvcf7p_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'jvcf7p_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate 
* a license key
*************************************/

function jvcf7p_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['jvcf7p_license_activate'] ) ) {

		// run a quick security check 
	 	//if( ! check_admin_referer( 'jvcf7p_nonce', 'jvcf7p_nonce' ) ) 	
		//	return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( $_POST['jvcf7p_license_key'] );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( JVCF7P_ITEM_NAME ), // the name of our product in EDD
			'url'       => '' //home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, JVCF7P_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
		
		
		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;
		
		
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "valid" or "invalid"

		update_option( 'jvcf7p_license_key', $license);
		update_option( 'jvcf7p_license_status', $license_data->license );

	}
}
add_action('admin_init', 'jvcf7p_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function jvcf7p_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['jvcf7p_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'jvcf7p_nonce', 'jvcf7p_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'jvcf7p_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( JVCF7P_ITEM_NAME ), // the name of our product in EDD
			'url'       => '' //home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, JVCF7P_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'jvcf7p_license_status' );

	}
}
add_action('admin_init', 'jvcf7p_deactivate_license');


/************************************
* this illustrates how to check if 
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function jvcf7p_check_license() {

	global $wp_version;

	$license = trim( get_option( 'jvcf7p_license_key' ) );
		
	$api_params = array( 
		'edd_action' => 'check_license', 
		'license' => $license, 
		'item_name' => urlencode( JVCF7P_ITEM_NAME ),
		'url'       => '' //home_url()
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, JVCF7P_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );


	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}
