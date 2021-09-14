<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

if (! defined('WPACU_USE_MODAL_BOX')) {
	define('WPACU_USE_MODAL_BOX', true);
}
?>
<nav class="nav-tab-wrapper">
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads'); ?>" class="nav-tab <?php if ($data['for'] === 'everywhere') { ?>nav-tab-active<?php } ?>">Everywhere</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=post_types'); ?>" class="nav-tab <?php if ($data['for'] === 'post_types') { ?>nav-tab-active<?php } ?>">Posts, Pages &amp; Custom Post Types</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=taxonomies'); ?>" class="nav-tab <?php if ($data['for'] === 'taxonomies') { ?>nav-tab-active<?php } ?>">Taxonomies</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=authors'); ?>" class="nav-tab <?php if ($data['for'] === 'authors') { ?>nav-tab-active<?php } ?>">Authors</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=search_results'); ?>" class="nav-tab <?php if ($data['for'] === 'search_results') { ?>nav-tab-active<?php } ?>">Search Results</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=dates'); ?>" class="nav-tab <?php if ($data['for'] === 'dates') { ?>nav-tab-active<?php } ?>">Dates</a>
    <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=404_not_found'); ?>" class="nav-tab <?php if ($data['for'] === '404_not_found') { ?>nav-tab-active<?php } ?>">404 Not Found</a>
</nav>

<div class="wpacu-clearfix"></div>

<?php
do_action('wpacu_admin_notices');

if ($data['for'] === 'post_types') {
	?>
    <div style="margin: 15px 0;">
        <form id="wpacu_post_type_form" method="get" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="page" value="wpassetcleanup_bulk_unloads" />
            <input type="hidden" name="wpacu_for" value="post_types" />

            <div style="margin: 0 0 10px 0;">Select the page or post type (including custom ones) for which you want to see the unloaded scripts &amp; styles:</div>
            <?php \WpAssetCleanUp\BulkChanges::buildPostTypesListDd($data['post_types_list'], $data['post_type']); ?>
        </form>
    </div>
	<?php
}

// [wpacu_pro]
if ($data['for'] === 'taxonomies') {
	?>
    <div style="margin: 15px 0;">
        <form id="wpacu_taxonomy_form" method="get" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="page" value="wpassetcleanup_bulk_unloads" />
            <input type="hidden" name="wpacu_for" value="taxonomies" />

            <div style="margin: 0 0 10px 0;">Select the page or post type (including custom ones) for which you want to see the unloaded scripts &amp; styles:</div>
            <select id="wpacu_taxonomy_select" name="wpacu_taxonomy">
				<?php foreach ($data['taxonomies_list'] as $taxonomyKey => $taxonomyValue) { ?>
                    <option <?php if ($data['taxonomy'] === $taxonomyKey) { echo 'selected="selected"'; } ?> value="<?php echo $taxonomyKey; ?>"><?php echo $taxonomyValue; ?></option>
				<?php } ?>
            </select>
        </form>
    </div>
	<?php
}
// [/wpacu_pro]
?>

<form action="" method="post">
	<?php
    $isCustomPostTypeArchivePageRequest = isset($data['post_type']) && (strpos($data['post_type'], 'wpacu_custom_post_type_archive_') !== false);

	if ($data['for'] === 'everywhere') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded everywhere</strong> (site-wide) on all pages (including home page). &nbsp;&nbsp;<a id="wpacu-add-bulk-rules-info-target" href="#wpacu-add-bulk-rules-info" style="text-decoration: none;"><span class="dashicons dashicons-info"></span> How the list below gets filled with site-wide rules?</a></p>
            <p>If you want to remove this rule and have them loading, use the "Remove site-wide rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you select "<em>Unload everywhere</em>" when you edit posts/pages for the assets that you want to prevent from loading on every page.</li>
                    <li>On this page you can only remove the global rules that were added while editing the pages/posts.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

		<div style="padding: 0 10px 0 0;">
			<p style="margin-bottom: 10px;"><strong><?php _e('Stylesheets (.css) Unloaded', 'wp-asset-clean-up'); ?></strong></p>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td>
                                <?php wpacuRenderHandleTd($handle, 'styles', $data); ?>
                            </td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove site-wide rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
				<p><?php _e('There are no site-wide unloaded styles.', 'wp-asset-clean-up'); ?></p>
				<?php
			}
			?>

            <hr style="margin: 15px 0;"/>

			<p style="margin-bottom: 10px;"><strong><?php _e('Scripts (.js) Unloaded', 'wp-asset-clean-up'); ?></strong></p>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td>
	                            <?php wpacuRenderHandleTd($handle, 'scripts', $data); ?>
                            </td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove site-wide rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
				<p><?php _e('There are no site-wide unloaded scripts.', 'wp-asset-clean-up'); ?></p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === 'post_types' && ! $isCustomPostTypeArchivePageRequest) {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all pages belonging to the <strong><u><?php echo $data['post_type']; ?></u></strong> post type. &nbsp;&nbsp;<a id="wpacu-add-bulk-rules-info-target" href="#wpacu-add-bulk-rules-info" style="text-decoration: none;"><span class="dashicons dashicons-info"></span> How the list below gets filled with site-wide rules?</a></p>
            <p>If you want to make an asset load again, use the "Remove bulk rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you select "<em>Unload on All Pages of <strong><?php echo $data['post_type']; ?></strong> post type</em>" when you edit posts/pages for the assets that you want to prevent from loading.</li>
                    <li>On this page you can only remove the global rules that were added while editing <strong><?php echo $data['post_type']; ?></strong> post types.</li>
                </ul>
            </div>
        </div>

		<div class="wpacu-clearfix"></div>

		<div style="padding: 0 10px 0 0;">
            <p style="margin-bottom: 10px;"><strong><?php _e('Stylesheets (.css) Unloaded', 'wp-asset-clean-up'); ?></strong></p>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_post_type_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong><?php echo $data['post_type']; ?></strong> post type.</p>
				<?php
			}
			?>

            <hr style="margin: 15px 0;"/>

            <p style="margin-bottom: 10px;"><strong><?php _e('Scripts (.js) Unloaded', 'wp-asset-clean-up'); ?></strong></p>

			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_post_type_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong><?php echo $data['post_type']; ?></strong> post type.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	// [wpacu_pro]
	if ($data['for'] === 'post_types' && $isCustomPostTypeArchivePageRequest) {
	    $targetCustomPostTypeKey = str_replace('wpacu_custom_post_type_archive_', 'custom_post_type_archive_', $data['post_type']);
	    $targetCustomPostType = str_replace('wpacu_custom_post_type_archive_', '', $data['post_type']);
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on the archive page (any pagination) belonging to the <strong><u><?php echo $targetCustomPostType; ?></u></strong> post type. &nbsp;&nbsp;<a id="wpacu-add-bulk-rules-info-target" href="#wpacu-add-bulk-rules-info" style="text-decoration: none;"><span class="dashicons dashicons-info"></span> How the list below gets filled with site-wide rules?</a></p>
            <p>If you want to make an asset load again, use the "Remove rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you select `<em>Unload on this "<?php echo ucfirst($targetCustomPostType); ?>` post type archive page</em>` when you edit posts/pages for the assets that you want to prevent from loading.</li>
                    <li>On this page you can only remove the unload rules that were added while editing the <strong><?php echo $targetCustomPostType; ?></strong> archive page.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <p style="margin-bottom: 10px;"><strong><?php _e('Stylesheets (.css) Unloaded', 'wp-asset-clean-up'); ?></strong></p>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_<?php echo $targetCustomPostTypeKey; ?>_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no unloaded styles in the <strong><?php echo $targetCustomPostType; ?></strong> archive page.</p>
				<?php
			}
			?>

            <hr style="margin: 15px 0;"/>

            <p style="margin-bottom: 10px;"><strong><?php _e('Scripts (.js) Unloaded', 'wp-asset-clean-up'); ?></strong></p>

			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_<?php echo $targetCustomPostTypeKey; ?>_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts in the <strong><?php echo $targetCustomPostType; ?></strong> archive page.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === 'taxonomies') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all taxonomies pages belonging to the <strong><u><?php echo $data['taxonomy']; ?></u></strong> type. &nbsp;&nbsp;<a id="wpacu-add-bulk-rules-info-target" href="#wpacu-add-bulk-rules-info" style="text-decoration: none;"><span class="dashicons dashicons-info"></span> How the list below gets filled with site-wide rules?</a></p>
            <p>If you want to make an asset load again, use the "Remove bulk rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you select "<em>Unload on All Pages of <strong><?php echo $data['taxonomy']; ?></strong> taxonomy type</em>" when you edit taxonomy pages for the assets that you want to prevent from loading.</li>
                    <li>On this page you can only remove the global rules that were added while editing <strong><?php echo $data['taxonomy']; ?></strong> taxonomy pages.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <h3>Styles</h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_taxonomy_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong><?php echo $data['taxonomy']; ?></strong> taxonomy type.</p>
				<?php
			}
			?>

            <h3>Scripts</h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_taxonomy_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong><?php echo $data['taxonomy']; ?></strong> taxonomy type.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === 'authors') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all authors pages.</p>
            <p>If you want to make an asset load again, use the "Remove rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you select "<em>Unload on All <strong>Author</strong> Pages</em>" when you edit author pages for the assets that you want to prevent from loading.</li>
                    <li>On this page you can only remove the global rules that were added while editing author pages.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <h3>Styles</h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_author_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong>Author</strong> archive page.</p>
				<?php
			}
			?>

            <h3>Scripts</h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_author_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong>Author</strong> archive page.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === 'search_results') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all default WordPress search results pages.</p>
            <p>If you want to make an asset load again, use the "Remove bulk rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you choose an asset to unload on the default WordPress search page (any results).</li>
                    <li>On this page you can only remove the global rules that were added while editing search results pages.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <h3>Styles</h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_search_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong>default WordPress search results</strong> page.</p>
				<?php
			}
			?>

            <h3>Scripts</h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_search_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong>default WordPress search results</strong> page.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === 'dates') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all date archive pages.</p>
            <p>If you want to make an asset load again, use the "Remove bulk rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you choose an asset to unload on the default WordPress date page (any date).</li>
                    <li>On this page you can only remove the global rules that were added while editing date pages.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <h3>Styles</h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_date_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong>date</strong> page.</p>
				<?php
			}
			?>

            <h3>Scripts</h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_date_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong>date</strong> page.</p>
				<?php
			}
			?>
        </div>
		<?php
	}

	if ($data['for'] === '404_not_found') {
		?>
        <div class="wpacu-clearfix"></div>

        <div class="alert">
            <p>This is the list of the assets that are <strong>unloaded</strong> on all date archive pages.</p>
            <p>If you want to make an asset load again, use the "Remove bulk rule" checkbox.</p>
            <div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
                <ul>
                    <li>This list fills once you choose an asset to unload on the 404 Not Found page (any URL).</li>
                    <li>On this page you can only remove the global rules that were added while editing 404 Not Found.</li>
                </ul>
            </div>
        </div>

        <div class="wpacu-clearfix"></div>

        <div style="padding: 0 10px 0 0;">
            <h3>Styles</h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'styles', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_404_styles[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded styles for the <strong>404 Not Found</strong> page.</p>
				<?php
			}
			?>

            <h3>Scripts</h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><strong>Handle</strong></td>
                        <td><strong>Actions</strong></td>
                    </tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
                        <tr class="wpacu_global_rule_row wpacu_bulk_change_row">
                            <td><?php wpacuRenderHandleTd($handle, 'scripts', $data); ?></td>
                            <td>
                                <label><input type="checkbox"
                                              class="wpacu_bulk_rule_checkbox"
                                              name="wpacu_options_404_scripts[<?php echo $handle; ?>]"
                                              value="remove" /> Remove bulk rule</label>
                            </td>
                        </tr>
						<?php
					}
					?>
                </table>
				<?php
			} else {
				?>
                <p>There are no bulk unloaded scripts for the <strong>404 Not Found</strong> page.</p>
				<?php
			}
			?>
        </div>
		<?php
	}
	// [/wpacu_pro]

	$noAssetsToRemove = (empty($data['values']['styles']) && empty($data['values']['scripts']));
	?>
	<?php wp_nonce_field($data['nonce_action'], $data['nonce_name']); ?>

    <input type="hidden" name="wpacu_for" value="<?php echo $data['for']; ?>" />
    <input type="hidden" name="wpacu_update" value="1" />

	<?php
	if ($data['for'] === 'post_types' && isset($data['post_type'])) {
		?>
        <input type="hidden" name="wpacu_post_type" value="<?php echo $data['post_type']; ?>" />
		<?php
	}

	// [wpacu_pro]
	if ($data['for'] === 'taxonomies' && isset($data['taxonomy'])) {
		?>
        <input type="hidden" name="wpacu_taxonomy" value="<?php echo $data['taxonomy']; ?>" />
		<?php
	}
	// [/wpacu_pro]
	?>

    <div class="wpacu-clearfix"></div>

    <div id="wpacu-update-button-area" class="no-left-margin">
        <p class="submit">
			<?php
			wp_nonce_field('wpacu_bulk_unloads_update', 'wpacu_bulk_unloads_update_nonce' );
			?>
            <input type="submit"
                   name="submit"
                   id="submit"
				<?php if ($noAssetsToRemove) { ?>
                    disabled="disabled"
				<?php } ?>
                   class="button button-primary"
                   value="<?php esc_attr_e('Apply changes', 'wp-asset-clean-up'); ?>" />
			<?php
			if ($noAssetsToRemove) {
				?>
				&nbsp;<small><?php _e('Note: As there are no unloaded assets (scripts &amp; styles) to be managed, the button is disabled.', 'wp-asset-clean-up'); ?></small>
				<?php
			}
			?>
        </p>
        <div id="wpacu-updating-settings" style="margin-left: 150px;">
            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
        </div>
    </div>
</form>
<!-- Start Site-Wide Modal -->
<div id="wpacu-add-bulk-rules-info" class="wpacu-modal">
    <div class="wpacu-modal-content">
        <span class="wpacu-close">&times;</span>
        <h2><?php _e('Unloading CSS/JS site-wide or for a group of pages', 'wp-asset-clean-up'); ?></h2>
        <p>This is an overview of all the assets that have bulk changes applied. Anything you see on this page is filled the moment you go to edit a page via the "CSS/JS Load Manager" (e.g. homepage or a post) and use options such as:</p>

        <ul style="list-style: disc; margin-left: 20px;">
            <li>Unload site-wide (everywhere)</strong></li>
            <li>Unload on All Pages of `product` post type</li>
            <li>Unload on All Pages of `product_cat` taxonomy type etc.</li>
            <li>Unload on this `[custom post type name here]` post type archive page</li>
            <li>Unload on this page type (any 404 Not Found URL), etc.</li>
        </ul>

        <p>A bulk change is considered anything that is applied once and it has effect on multiple pages of the same kind or site-wide.</p>
    </div>
</div>
<!-- End Site-Wide Modal -->