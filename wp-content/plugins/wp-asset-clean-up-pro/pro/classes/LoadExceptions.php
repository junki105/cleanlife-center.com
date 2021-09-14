<?php
namespace WpAssetCleanUpPro;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Misc;

/*
 * Note: Methods here are triggered by actions from \WpAssetCleanUp\saveLoadExceptions()
 * e.g. wpacu_pro_clear_load_exceptions, wpacu_pro_update_load_exceptions
 */

/**
 * Class LoadExceptions
 * @package WpAssetCleanUpPro
 */
class LoadExceptions
{
	/**
	 * @var array
	 */
	public $extrasLoadExceptions = array();

	/**
	 *
	 */
	public function init()
	{
		// Load exceptions for specific pages: taxonomy (category, tag, etc.), author, date, 404, search results, custom post type archive page
		add_action('wpacu_pro_clear_load_exceptions', array($this, 'clearLoadExceptions'));
		add_action('wpacu_pro_update_load_exceptions', array($this, 'updateLoadExceptions'));

		// Load it (make an exception) for URLs matching this RegExp is made in UpdatePro.php
	}

	/**
	 * @return mixed|string
	 */
	public function getLoadExceptions()
	{
		global $wp_query;

		/*
		* Taxonomy page (e.g. 'product_cat' (WooCommerce) or default WordPress 'category', 'post_tag')
		*/
		// For Front-End Trigger & Update (for both the visitor and admin if he/she has enabled managing the CSS/JS in the front-end view)
		if (! is_admin()) {
			$object = $wp_query->get_queried_object();

			if (isset( $object->taxonomy )) {
				$term_id = $object->term_id;
				return get_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', true );
			}
		}

		// For Dashboard Update (pon pages such as "Edit Category" (e.g. /wp-admin/term.php?taxonomy=category&tag_ID=1&post_type=post)
		if (isset($_REQUEST['tag_id']) && is_admin() && Main::instance()->settings['dashboard_show']) {
			$term_id = (int)$_REQUEST['tag_id'];

			if ($term_id > 0) {
				return get_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', true );
			}
		}

		// The code below should trigger only in front-end view, no need to have it run within the Dashboard
		// because managing CSS/JS for author, 404, search and date pages is done ONLY within the front-end view
		if (is_admin()) {
			return '';
		}

		/*
		 * Author page (individual, not for all authors)
		 */
		if (is_author()) {
			$author_id = $object->data->ID;

			return get_user_meta($author_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', true);
		}

		/*
		 * 404 `Not Found` Page (any URL)
		*/
		if (is_404()) {
			return $this->getLoadExceptionsForExtraPage('404');
		}

		/*
		 * Default WordPress Search Page (any keyword)
		 */

		if (Main::isWpDefaultSearchPage()) {
			return $this->getLoadExceptionsForExtraPage('search');
		}

		/*
		* Date Page
		*/
		if (is_date()) {
			return $this->getLoadExceptionsForExtraPage('date');
		}


		/*
		* Archive custom post type page
		* */
		if ($customPostTypeObj = Main::isCustomPostTypeArchivePage()) {
			$targetKey = 'custom_post_type_archive_' . $customPostTypeObj->name;
			return $this->getLoadExceptionsForExtraPage($targetKey);
		}

		return '';
	}

	/**
	 * @param $for
	 *
	 * @return mixed|string
	 */
	public function getLoadExceptionsForExtraPage($for)
	{
		$extrasExceptions = $this->getAllExtrasLoadExceptions();

		if (isset($extrasExceptions[$for]) && ! empty($extrasExceptions[$for])) {
			return json_encode($extrasExceptions[$for]);
		}

		return '';
	}

	/**
	 * This will retrieve all load exceptions for any `extra` page (404, search, date)
	 *
	 * @return array|mixed|object
	 */
	public function getAllExtrasLoadExceptions()
	{
		if (empty($this->extrasLoadExceptions)) {
			$extrasLoadExceptionsJson = get_option( WPACU_PLUGIN_ID . '_extras_load_exceptions', '');

			if ($extrasLoadExceptionsJson === '') {
				$this->extrasLoadExceptions = array(); // no exceptions stored in the `options` table
			} else {

				$this->extrasLoadExceptions = json_decode( $extrasLoadExceptionsJson, true );

				if ( Misc::jsonLastError() !== JSON_ERROR_NONE ) {
					$this->extrasLoadExceptions = array();
				}
			}

			// No errors? The JSON format is valid and there are exceptions; return them
			return $this->extrasLoadExceptions;
		}

		return $this->extrasLoadExceptions;
	}

	/*
	 * Triggers for: is_archive(), author, search, 404 pages
	 * Called from \WpAssetCleanUp\saveLoadExceptions which triggers by default
	 * for singular page and home/front page (in the lite version)
	*/
	public function clearLoadExceptions()
	{
		global $wp_query, $wpdb;

		$object = $wp_query->get_queried_object();

		/*
		* Taxonomy page (e.g. 'product_cat' (WooCommerce) or default WordPress 'category', 'post_tag')
		*/
		if ((! is_admin()) && Main::instance()->isFrontendEditView) {
			// Front-end View
			if ( isset( $object->taxonomy ) ) {
				$term_id = $object->term_id;
				delete_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions' );
			}
		} elseif (isset($_REQUEST['tag_ID']) && Main::instance()->settings['dashboard_show'] && is_admin()) {
			// Dashboard View
			$term_id = (int)$_REQUEST['tag_ID'];

			if ($term_id) {
				delete_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions' );
			}
		}

		// The checks below are for front-end submits only
		if (! Main::instance()->isFrontendEditView) {
			return;
		}

		/*
		 * Author page
		 * */
		if (is_author()) {
			$author_id = $object->data->ID;
			$wpdb->delete(
				$wpdb->usermeta,
				array('user_id' => $author_id, 'meta_key' => '_' . WPACU_PLUGIN_ID . '_load_exceptions')
			);
		}

		/*
		 * 404 (Not Found) Page
		*/
		elseif (is_404()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();

			if (isset($loadExtrasExceptions['404']) && ! empty($loadExtrasExceptions['404'])) {
				unset($loadExtrasExceptions['404']); // clear
				$this->updateExtrasLoadExceptions($loadExtrasExceptions);
			}
		}

		/*
		 * Search page / WooCommerce Search page
		 * */
		elseif (Main::isWpDefaultSearchPage()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();

			if (isset($loadExtrasExceptions['search']) && ! empty($loadExtrasExceptions['search'])) {
				unset($loadExtrasExceptions['search']); // clear
				$this->updateExtrasLoadExceptions($loadExtrasExceptions);
			}
		}

		/*
		 * Date Page
		*/
		elseif (is_date()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();

			if (isset($loadExtrasExceptions['date']) && ! empty($loadExtrasExceptions['date'])) {
				unset($loadExtrasExceptions['date']); // clear
				$this->updateExtrasLoadExceptions($loadExtrasExceptions);
			}
		}

		/*
		* Archive custom post type page
		* */
		elseif ($customPostTypeObj = Main::isCustomPostTypeArchivePage()) {
			$targetKey = 'custom_post_type_archive_' . $customPostTypeObj->name;

			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();

			if (isset($loadExtrasExceptions[$targetKey]) && ! empty($loadExtrasExceptions[$targetKey])) {
				unset($loadExtrasExceptions[$targetKey]); // clear
				$this->updateExtrasLoadExceptions($loadExtrasExceptions);
			}
		}
	}

	/**
	 * @param $jsonLoadExceptions
	 */
	public function updateLoadExceptions($jsonLoadExceptions)
	{
		global $wp_query;

		$object = $wp_query->get_queried_object();

		/*
		* Taxonomy page (e.g. 'product_cat' (WooCommerce) or default WordPress 'category', 'post_tag')
		*/
		if ((! is_admin()) && Main::instance()->isFrontendEditView) {
			if ( isset( $object->taxonomy ) ) {
				$term_id = $object->term_id;

				if ( ! add_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions,
					true ) ) {
					update_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions );
				}
			}
		} elseif (isset($_REQUEST['tag_ID']) && is_admin() && Main::instance()->settings['dashboard_show']) {
			// Dashboard View
			$term_id = (int)$_REQUEST['tag_ID'];

			if ( ! add_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions,
				true ) ) {
				update_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions );
			}
		}

		// The checks below are for front-end submits only
		if (! Main::instance()->isFrontendEditView) {
			return;
		}

		/*
		 * Author page
		 * */
		if (is_author()) {
			$author_id = $object->data->ID;

			if (! add_user_meta($author_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions, true)) {
				update_user_meta($author_id, '_' . WPACU_PLUGIN_ID . '_load_exceptions', $jsonLoadExceptions);
			}
		}

		/*
		 * 404 (Not Found) Page
		 * */
		elseif (is_404()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();
			$loadExtrasExceptions['404'] = json_decode($jsonLoadExceptions, true);
			$this->updateExtrasLoadExceptions($loadExtrasExceptions);
		}

		/*
		* WordPress Default Search Page
		* */
		elseif (Main::isWpDefaultSearchPage()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();
			$loadExtrasExceptions['search'] = json_decode($jsonLoadExceptions, true);
			$this->updateExtrasLoadExceptions($loadExtrasExceptions);
		}

		/*
		* WordPress Date Page
		* */
		elseif (is_date()) {
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();
			$loadExtrasExceptions['date'] = json_decode($jsonLoadExceptions, true);
			$this->updateExtrasLoadExceptions($loadExtrasExceptions);
		}

		/*
		* Archive custom post type page
		* */
		elseif ($customPostTypeObj = Main::isCustomPostTypeArchivePage()) {
			$targetKey = 'custom_post_type_archive_' . $customPostTypeObj->name;
			$loadExtrasExceptions = $this->getAllExtrasLoadExceptions();
			$loadExtrasExceptions[$targetKey] = json_decode($jsonLoadExceptions, true);
			$this->updateExtrasLoadExceptions($loadExtrasExceptions);
		}
	}

	/**
	 * @param array $loadExtrasExceptions
	 */
	public function updateExtrasLoadExceptions($loadExtrasExceptions = array())
	{
		$loadExtrasExceptionsJson = json_encode(Misc::filterList($loadExtrasExceptions));
		Misc::addUpdateOption( WPACU_PLUGIN_ID . '_extras_load_exceptions', $loadExtrasExceptionsJson);
	}
}
