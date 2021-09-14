<?php
namespace WpAssetCleanUp;

/**
 * Class AjaxSearchAutocomplete
 * @package WpAssetCleanUp
 */
class AjaxSearchAutocomplete
{
	/**
	 * AjaxSearchAutocomplete constructor.
	 */
	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_action('wp_ajax_' . WPACU_PLUGIN_ID . '_autocomplete_search', array($this, 'wpAjaxSearch'));
	}

	/**
	 *
	 */
	public function enqueueScripts()
    {
	    if (! isset($_REQUEST['wpacu_for'])) {
			return;
	    }

	    $wpacuFor = $_REQUEST['wpacu_for'];
	    $forPostType = '';

	    switch ($wpacuFor) {
		    case 'posts':
			    $forPostType = 'post';
			    break;
		    case 'pages':
			    $forPostType = 'page';
			    break;
		    case 'media-attachment':
		    	$forPostType = 'attachment';
		    	break;
		    case 'custom-post-types':
		    	$forPostType = 'wpacu-custom-post-types';
		    	break;
	    }

	    if ( ! $forPostType ) {
	    	return;
	    }

	    wp_enqueue_script(
	    	'wpacu-autocomplete-search',
		    WPACU_PLUGIN_URL . '/assets/auto-complete/main.js',
		    array('jquery', 'jquery-ui-autocomplete'),
		    OwnAssets::assetVer('/assets/auto-complete/main.js'),
		    true
	    );

	    wp_localize_script('wpacu-autocomplete-search', 'wpacu_autocomplete_search_obj', array(
		    'ajax_url'       => admin_url('admin-ajax.php'),
		    'ajax_nonce'     => wp_create_nonce('wpacu_autocomplete_search_nonce'),
		    'ajax_action'    => WPACU_PLUGIN_ID . '_autocomplete_search',
		    'post_type'      => $forPostType,
		    'redirect_to'    => admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for='.$wpacuFor.'&wpacu_post_id=[post_id_here]')
	    ));

	    $wp_scripts = wp_scripts();
	    wp_enqueue_style('wpacu-jquery-ui-css',
		    '//ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-autocomplete']->ver . '/themes/smoothness/jquery-ui.css',
		    false, null, false
	    );

	    $jqueryUiCustom = <<<CSS
#wpacu-search-form-assets-manager input[type=text].ui-autocomplete-loading {
	background-position: 99% 6px;
}
CSS;
	    wp_add_inline_style('wpacu-jquery-ui-css', $jqueryUiCustom);
    }

	/**
	 *
	 */
	public function wpAjaxSearch()
    {
		check_ajax_referer('wpacu_autocomplete_search_nonce', 'wpacu_security');

		global $wpdb;

		$search_term = isset($_REQUEST['wpacu_term'])      ? $_REQUEST['wpacu_term']      : '';
		$post_type   = isset($_REQUEST['wpacu_post_type']) ? $_REQUEST['wpacu_post_type'] : '';

		if ( $search_term === '' ) {
			echo json_encode(array());
		}

		$results = array();

	    if ($post_type !== 'attachment') {
	    	// 'post', 'page', custom post types
		    $queryDataByKeyword = array(
			    'post_type'      => $post_type,
			    's'              => $search_term,
			    'post_status'    => array( 'publish', 'private' ),
			    'posts_per_page' => -1
		    );
	    } else {
	    	// 'attachment'
		    $search = $wpdb->get_col( $wpdb->prepare( " SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_title = '%s' ", $search_term ) );
		    $queryDataByKeyword = array(
			    'post_type' => 'attachment',
			    'post_status' => 'inherit',
			    'orderby' => 'date',
			    'order' => 'DESC',
			    'post__in' => $search,
		    );
	    }

		// Standard search
		$query = new \WP_Query($queryDataByKeyword);

		// No results? Search by ID in case the admin put the post/page ID in the search box
	    if (! $query->have_posts()) {
	    	// This one works for any post type, including 'attachment'
		    $queryDataByID = array(
			    'post_type'      => $post_type,
			    'p'              => $search_term,
			    'posts_per_page' => -1
		    );

		    $query = new \WP_Query($queryDataByID);
	    }

		if ($query->have_posts()) {
			$pageOnFront = $pageForPosts = false;

			if ($post_type === 'page' && get_option('show_on_front') === 'page') {
				$pageOnFront  = (int)get_option('page_on_front');
				$pageForPosts = (int)get_option('page_for_posts');
			}

			while ($query->have_posts()) {
				$query->the_post();
				$resultPostId = get_the_ID();
				$resultPostStatus = get_post_status($resultPostId);

				$resultToShow = get_the_title() . ' / ID: '.$resultPostId;

				if ($resultPostStatus === 'private') {
					$iconPrivate = '<span class="dashicons dashicons-lock"></span>';
					$resultToShow .= ' / '.$iconPrivate.' Private';
				}

				// This is a page and it was set as the homepage (point this out)
				if ($pageOnFront === $resultPostId) {
					$iconHome = '<span class="dashicons dashicons-admin-home"></span>';
					$resultToShow .= ' / '.$iconHome.' Homepage';
				}

				if ($pageForPosts === $resultPostId) {
					$iconPost = '<span class="dashicons dashicons-admin-post"></span>';
					$resultToShow .= ' / '.$iconPost.' Posts page';
				}

				$results[] = array(
					'id'    => $resultPostId,
					'label' => $resultToShow,
					'link'  => get_the_permalink()
                );
			}
			wp_reset_postdata();
		}

		if (empty($results)) {
			echo 'no_results';
			wp_die();
		}

		echo json_encode($results);
		wp_die();
	}
}