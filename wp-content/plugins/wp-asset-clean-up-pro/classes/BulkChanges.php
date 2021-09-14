<?php
namespace WpAssetCleanUp;

/**
 *
 * Class BulkChanges
 * @package WpAssetCleanUp
 */
class BulkChanges
{
    /**
     * @var string
     */
    public $wpacuFor = 'everywhere';

    /**
     * @var string
     */
    public $wpacuPostType = 'post';

    // [wpacu_pro]
	/**
	 * @var string
	 */
	public $wpacuTaxonomy = 'category';
	// [/wpacu_pro]

    /**
     * @var array
     */
    public $data = array();

    /**
     * Includes bulk unload rules, RegEx unloads & load exceptions
     *
     * BulkChanges constructor.
     */
    public function __construct()
    {
	    $this->wpacuFor      = sanitize_text_field(Misc::getVar('request', 'wpacu_for', $this->wpacuFor));
	    $this->wpacuPostType = sanitize_text_field(Misc::getVar('request', 'wpacu_post_type', $this->wpacuPostType));

        // [wpacu_pro]
	    $this->wpacuTaxonomy = sanitize_text_field(Misc::getVar('request', 'wpacu_taxonomy', $this->wpacuTaxonomy));
        // [/wpacu_pro]

        if (Misc::getVar('request', 'wpacu_update') == 1) {
            $this->update();
        }
    }

    /**
     * @return array
     */
    public function getCount()
    {
        $values = array();

        if ($this->wpacuFor === 'everywhere') {
            $values = Main::instance()->getGlobalUnload();
        } elseif ($this->wpacuFor === 'post_types') {
            if (strpos($this->wpacuPostType, 'wpacu_custom_post_type_archive') !== false) {
                // For the archive page (if any available) and its pagination (e.g. /news/, /news/page/1/)
                $fetchStr = str_replace( 'wpacu_custom_post_type_archive_', 'custom_post_type_archive_', $this->wpacuPostType );
	            $values = Main::instance()->getBulkUnload( $fetchStr );
            } else {
	            // For the singular page belonging to the post type (e.g. /news/the-post-title-here/)
	            $values = Main::instance()->getBulkUnload( 'post_type', $this->wpacuPostType );
            }
        }

        // [wpacu_pro]
        if ($this->wpacuFor === 'taxonomies') {
            $values = Main::instance()->getBulkUnload('taxonomy', $this->wpacuTaxonomy);
        } elseif ($this->wpacuFor === 'authors') {
	        $values = Main::instance()->getBulkUnload('author');
        } elseif ($this->wpacuFor === 'search_results') {
	        $values = Main::instance()->getBulkUnload('search');
        } elseif ($this->wpacuFor === 'dates') {
	        $values = Main::instance()->getBulkUnload('date');
        } elseif ($this->wpacuFor === '404_not_found') {
            $values = Main::instance()->getBulkUnload('404');
        }
	    // [/wpacu_pro]

	    if (isset($values['styles']) && ! empty($values['styles'])) {
		    sort($values['styles']);
	    }

	    if (isset($values['scripts']) && ! empty($values['scripts'])) {
		    sort($values['scripts']);
	    }

        return $values;
    }

    /**
     *
     */
    public function pageBulkUnloads()
    {
	    $this->data['assets_info'] = Main::getHandlesInfo();
	    if (Misc::getVar('get', 'wpacu_bulk_menu_tab') === 'regex_unloads') {
	        /*
			 * RegEx Unloads (from v1.1.5.0 - Pro feature)
			 * e.g. "Unload it for URLs with request URI matching this RegEx"
			*/
	        $this->data['values'] = Main::getRegExRules('unloads');
        } elseif(Misc::getVar('get', 'wpacu_bulk_menu_tab') === 'regex_load_exceptions') {
	        /*
			 * RegEx Load Exceptions (from v1.1.5.0 - Pro feature)
	         * Only relevant when is used together with a bulk unload such as: unload site-wide, unload on all post types, etc.
			 * e.g. "Load it for URLs with request URI matching this RegEx"
			*/
	        $this->data['values'] = Main::getRegExRules('load_exceptions');
        } else {
            /*
             * Bulk Unloaded (page types)
             * e.g. Everywhere, Posts, Pages &amp; Custom Post Types, Taxonomies, etc.
            */
	        $this->data['for'] = $this->wpacuFor;

	        if ( $this->wpacuFor === 'post_types' ) {
		        $this->data['post_type'] = $this->wpacuPostType;

		        // Get All Public Post Types List
		        $postTypes                     = get_post_types( array( 'public' => true ) );
		        $this->data['post_types_list'] = $this->filterPostTypesList( $postTypes );
	        }

	        // [wpacu_pro]
	        if ( $this->wpacuFor === 'taxonomies' ) {
		        $this->data['taxonomy'] = $this->wpacuTaxonomy;

		        // Get All Public Taxonomies List
		        $postTypes                     = get_taxonomies( array( 'public' => true ) );
		        $this->data['taxonomies_list'] = $this->filterTaxonomiesList( $postTypes );
	        }
	        // [/wpacu_pro]

	        $this->data['values'] = $this->getCount();
        }

        $this->data['nonce_name'] = Update::NONCE_FIELD_NAME;
        $this->data['nonce_action'] = Update::NONCE_ACTION_NAME;

        $this->data['plugin_settings'] = Main::instance()->settings;

        Main::instance()->parseTemplate('admin-page-settings-bulk-changes', $this->data, true);
    }

    /**
     * @param $postTypes
     *
     * @return mixed
     */
    public function filterPostTypesList($postTypes)
    {
        foreach ($postTypes as $postTypeKey => $postTypeValue) {
            // Exclude irrelevant custom post types
            if (in_array($postTypeKey, MetaBoxes::$noMetaBoxesForPostTypes)) {
                unset($postTypes[$postTypeKey]);
            }

            // Polish existing values
            if ($postTypeKey === 'product' && Misc::isPluginActive('woocommerce/woocommerce.php')) {
                $postTypes[$postTypeKey] = 'product &#10230; WooCommerce';
            }
        }

        return $postTypes;
    }

	/**
	 * @param $postTypesList
	 * @param $currentPostType
	 */
	public static function buildPostTypesListDd($postTypesList, $currentPostType)
    {
        $ddList = array();

	    foreach ($postTypesList as $postTypeKey => $postTypeValue) {
	        if (in_array($postTypeKey, array('post', 'page', 'attachment'))) {
		        $ddList['WordPress (default)'][$postTypeKey] = $postTypeValue;
            } else {
		        $ddList['Custom Post Types (Singular pages)'][$postTypeKey] = $postTypeValue;

		        $list = Main::instance()->getBulkUnload('custom_post_type_archive_'.$postTypeKey);

		        // At least one of the buckets ('styles' or 'scripts') needs to contain something
		        if (! empty($list['styles']) || ! empty($list['scripts'])) {
			        $ddList['Custom Post Types (Archive pages)'][ 'wpacu_custom_post_type_archive_'.$postTypeKey ] = $postTypeValue. ' (archive page)';
		        }
            }
	    }
	    ?>
        <select id="wpacu_post_type_select" name="wpacu_post_type">
		    <?php
            foreach ($ddList as $groupLabel => $groupPostTypesList) {
                echo '<optgroup label="'.$groupLabel.'">';

                foreach ($groupPostTypesList as $postTypeKey => $postTypeValue) {
                    ?>
                    <option <?php if ($currentPostType === $postTypeKey) { echo 'selected="selected"'; } ?> value="<?php echo $postTypeKey; ?>"><?php echo $postTypeValue; ?></option>
		            <?php
                }

	            echo '</optgroup>';
            }
            ?>
        </select>
        <?php
    }

    // [wpacu_pro]
	/**
	 * @param $taxonomies
	 *
	 * @return mixed
	 */
	public function filterTaxonomiesList($taxonomies)
    {
	    foreach ($taxonomies as $taxonomyKey => $taxonomyValue) {
		    if ($taxonomyKey === 'category') {
			    $taxonomies[$taxonomyKey] = 'category (Location: Posts &#10230; Categories)';
		    }

		    if ($taxonomyKey === 'post_tag') {
			    $taxonomies[$taxonomyKey] = 'post_tag (Location: Posts &#10230; Tags)';
		    }

		    if ($taxonomyKey === 'download_category') {
			    $taxonomies[$taxonomyKey] = 'download_category (Location: Downloads &#10230; Categories)';
		    }

		    if ($taxonomyKey === 'download_tag') {
			    $taxonomies[$taxonomyKey] = 'download_tag (Location: Downloads &#10230; Tags)';
		    }
	    }

        return $taxonomies;
    }
	// [/wpacu_pro]

    /**
     *
     */
    public function update()
    {
        if ( ! Misc::getVar('post', 'wpacu_bulk_unloads_update_nonce') ) {
            return;
        }

	    check_admin_referer('wpacu_bulk_unloads_update', 'wpacu_bulk_unloads_update_nonce');

        $wpacuUpdate = new Update;

        if ($this->wpacuFor === 'everywhere') {
            $removed = $wpacuUpdate->removeEverywhereUnloads(array(), array(), 'post');

            if ($removed) {
                add_action('wpacu_admin_notices', array($this, 'noticeGlobalsRemoved'));
            }
        }

        // 'Custom Post Type Archive Page' or 'Custom Post Type Singular Page'
	    $isCustomPostTypeArchivePageRequest = (strpos($this->wpacuPostType, 'wpacu_custom_post_type_archive_') !== false);
        if ($this->wpacuFor === 'post_types') {
	        if ( ! $isCustomPostTypeArchivePageRequest ) {
		        $removed = $wpacuUpdate->removeBulkUnloads( $this->wpacuPostType );

		        if ( $removed ) {
			        add_action( 'wpacu_admin_notices', array( $this, 'noticePostTypesRemoved' ) );
		        }
	        } else {
		        $targetCustomPostTypeKey = str_replace('wpacu_custom_post_type_archive_', 'custom_post_type_archive_', $this->wpacuPostType);

		        $removed = $wpacuUpdate->removeBulkUnloads( '', $targetCustomPostTypeKey );

		        if ( $removed ) {
			        add_action( 'wpacu_admin_notices', array( $this, 'noticeArchivePostTypesRemoved' ) );
		        }
	        }
        }

	    if ($this->wpacuFor === 'post_types') {
		    $removed = $wpacuUpdate->removeBulkUnloads($this->wpacuPostType);

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'noticePostTypesRemoved'));
		    }
	    }

	    // [wpacu_pro]
	    if ($this->wpacuFor === 'taxonomies') {
		    $removed = $wpacuUpdate->removeBulkUnloads($this->wpacuTaxonomy, 'taxonomy');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'noticeTaxonomiesRemoved'));
		    }
	    }

	    if ($this->wpacuFor === 'authors') {
		    $removed = $wpacuUpdate->removeBulkUnloads('all', 'author');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'noticeAuthorsRemoved'));
		    }
        }

	    if ($this->wpacuFor === 'search_results') {
		    $removed = $wpacuUpdate->removeBulkUnloads('', 'search');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'noticeAuthorsRemoved'));
		    }
	    }

	    if ($this->wpacuFor === 'dates') {
		    $removed = $wpacuUpdate->removeBulkUnloads('', 'date');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'noticeDatesRemoved'));
		    }
	    }

	    if ($this->wpacuFor === '404_not_found') {
		    $removed = $wpacuUpdate->removeBulkUnloads('', '404');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'notice404Removed'));
		    }
	    }

	    if ($this->wpacuFor === '404_not_found') {
		    $removed = $wpacuUpdate->removeBulkUnloads('', '404');

		    if ($removed) {
			    add_action('wpacu_admin_notices', array($this, 'notice404Removed'));
		    }
	    }
	    // [/wpacu_pro]
    }

    /**
     *
     */
    public function noticeGlobalsRemoved()
    {
    ?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span>
                <?php
                _e('The selected styles/scripts were removed from the global unload list and they will now load in the pages/posts, unless you have other rules that would prevent them from loading.', 'wp-asset-clean-up');
                ?>
            </p>
        </div>
    <?php
    }

	/**
	 *
	 */
	public function noticePostTypesRemoved()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span>
				<?php
				echo sprintf(
					__('The selected styles/scripts were removed from the unload list for <strong><u>%s</u></strong> post type and they will now load in the pages/posts, unless you have other rules that would prevent them from loading.', 'wp-asset-clean-up'),
					$this->wpacuPostType
				);
				?>
            </p>
        </div>
		<?php
	}

	// [wpacu_pro]
	/**
	 *
	 */
	public function noticeArchivePostTypesRemoved()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span>
				<?php
				echo sprintf(
					__('The unload rule for the chosen styles/scripts were removed from the archive page of <strong><u>%s</u></strong> custom post type and they will now load again, unless you have other rules that would prevent them from loading.', 'wp-asset-clean-up'),
					str_replace('wpacu_custom_post_type_archive_', '', $this->wpacuPostType)
				);
				?>
            </p>
        </div>
		<?php
	}

	/**
	 *
	 */
	public function noticeTaxonomiesRemoved()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span> The selected styles/scripts were removed from the unload list for <strong><u><?php echo $this->wpacuTaxonomy; ?></u></strong>
                taxonomy type and they will now load again, unless you have other rules that would prevent them from loading.</p>
        </div>
		<?php
	}

	/**
	 *
	 */
	public function noticeAuthorsRemoved()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span> The selected styles/scripts were removed from the unload list for all <strong>Author</strong> pages, and they will now load again, unless you have other rules that would prevent them from loading.</p>
        </div>
		<?php
	}

	/**
	 *
	 */
	public function noticeDatesRemoved()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span> The selected styles/scripts were removed from the unload list for all <strong>Dates</strong> pages (any date), and they will now load again, unless you have other rules that would prevent them from loading.</p>
        </div>
		<?php
	}

	/**
	 *
	 */
	public function notice404Removed()
	{
		?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span> The selected styles/scripts were removed from the unload list for all <strong>404 Not Found</strong> pages (any URL), and they will now load again, unless you have other rules that would prevent them from loading.</p>
        </div>
		<?php
	}
	// [/wpacu_pro]
}
