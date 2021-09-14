<?php
namespace WpAssetCleanUp;

/**
 * Class AssetsPagesManager
 * @package WpAssetCleanUp
 */
class AssetsPagesManager
{
    /**
     * @var array
     */
    public $data = array();

	/**
	 * AssetsPagesManager constructor.
	 */
	public function __construct()
    {
	    $this->data = array(
	    	'for'          => 'homepage', // default
		    'nonce_action' => WPACU_PLUGIN_ID . '_dash_assets_page_update_nonce_action',
		    'nonce_name'   => WPACU_PLUGIN_ID . '_dash_assets_page_update_nonce_name'
	    );

	    if (isset($_GET['wpacu_for']) && $_GET['wpacu_for'] !== '') {
		    $this->data['for'] = sanitize_text_field($_GET['wpacu_for']);
	    }

	    if (isset($_GET['page'])) {
		    $this->data['page'] = $_GET['page'];
	    }

	    $wpacuSettings = new Settings;
	    $this->data['wpacu_settings'] = $wpacuSettings->getAll();
	    $this->data['show_on_front'] = Misc::getShowOnFront();

	    if (in_array($this->data['for'], array('homepage', 'pages', 'posts', 'custom-post-types', 'media-attachment'))) {
		    if ($this->data['show_on_front'] === 'page') {
			    // Front page displays: A Static Page
			    $this->data['page_on_front'] = get_option('page_on_front');

			    if ($this->data['page_on_front']) {
				    $this->data['page_on_front_title'] = get_the_title($this->data['page_on_front']);
			    }

			    $this->data['page_for_posts'] = get_option('page_for_posts');

			    if ($this->data['page_for_posts']) {
				    $this->data['page_for_posts_title'] = get_the_title($this->data['page_for_posts']);
			    }
		    } else {
			    // Your latest posts
			    $postUrl = get_site_url();

			    if (substr($postUrl, -1) !== '/') {
				    $postUrl .= '/';
			    }

			    $this->data['site_url'] = $postUrl;
		    }

		    // e.g. It could be the homepage tab loading a singular page set as the homepage in "Settings" -> "Reading"
	    	$anyPostId = (int)Misc::getVar('post', 'wpacu_manage_singular_page_id');

		    if ($this->data['for'] === 'homepage' && ! $anyPostId) {
			    $this->homepageActions(); // e.g. "Your homepage displays" set as "Your latest posts"
		    } else {
		    	$this->singularPageActions();
		    }
	    }
    }

	/**
	 *
	 */
    public function homepageActions()
    {
        $isHomePageEdit = ( Misc::getVar('get', 'page') === WPACU_PLUGIN_ID . '_assets_manager'
                            && $this->data['for'] === 'homepage' );

        // Only continue if we are on the plugin's homepage edit mode
        if (! $isHomePageEdit) {
            return;
        }

        if (! empty($_POST)) {
	        // Update action?
	        $wpacuNoLoadAssets   = Misc::getVar( 'post', WPACU_PLUGIN_ID, array() );
	        $wpacuHomePageUpdate = Misc::getVar( 'post', 'wpacu_manage_home_page_assets', false );

	        // Could Be an Empty Array as Well so just is_array() is enough to use
	        if ( is_array( $wpacuNoLoadAssets ) && $wpacuHomePageUpdate ) {
		        check_admin_referer( $this->data['nonce_action'], $this->data['nonce_name'] );

		        $wpacuUpdate = new Update;
		        $wpacuUpdate->updateFrontPage( $wpacuNoLoadAssets );
	        }
        }
    }

	/**
	 * Any post type, including the custom ones
	 */
	public function singularPageActions()
    {
	    $postId = (int)Misc::getVar('post', 'wpacu_manage_singular_page_id');

	    $isSingularPageEdit = $postId > 0 &&
			( Misc::getVar('get', 'page') === WPACU_PLUGIN_ID . '_assets_manager' &&
			in_array( $this->data['for'], array('homepage', 'pages', 'posts', 'custom-post-types', 'media-attachment' ) ) );

	    // Only continue if the form was submitted for a singular page
	    // e.g. a post, a page (could be the homepage), a WooCommerce product page, any public custom post type
	    if (! $isSingularPageEdit) {
		    return;
	    }

	    if (! empty($_POST)) {
		    // Update action?
		    $wpacuNoLoadAssets   = Misc::getVar( 'post', WPACU_PLUGIN_ID, array() );
		    $wpacuSingularPageUpdate = Misc::getVar( 'post', 'wpacu_manage_singular_page_assets', false );

		    // Could Be an Empty Array as Well so just is_array() is enough to use
		    if ( is_array( $wpacuNoLoadAssets ) && $wpacuSingularPageUpdate ) {
			    check_admin_referer( $this->data['nonce_action'], $this->data['nonce_name'] );
			    $postObj = get_post($postId);

			    if ($postId > 0) {
				    $wpacuUpdate = new Update;
				    $wpacuUpdate->savePost($postId, $postObj);
			    }
		    }
	    }
    }

	/**
	 * Called in Menu.php (within "admin_menu" hook via "activeMenu" method)
	 */
	public function renderPage()
    {
	    Main::instance()->parseTemplate('admin-page-assets-manager', $this->data, true);
    }
}
