<?php
/*
 * The file is included from _assets-hardcoded-list.php and it's relevant only for the hardcoded styles (non-enqueued)
*/
if (! isset($data)) {
	exit; // no direct access
}

$isCoreFile       = isset($data['row']['obj']->wp) && $data['row']['obj']->wp;
$isGroupUnloaded  = $data['row']['is_group_unloaded'];

// Unloaded site-wide
if ($data['row']['global_unloaded']) {
	$data['row']['class'] .= ' wpacu_is_global_unloaded';
}

// Unloaded site-wide OR on all posts, pages etc.
if ($isGroupUnloaded) {
	$data['row']['class'] .= ' wpacu_is_bulk_unloaded';
}

$rowIsContracted   = '';
$dashSign          = 'minus';
$dataRowStatusAttr = 'expanded';

if (isset($data['handle_rows_contracted']['styles'][$data['row']['obj']->handle]) && $data['handle_rows_contracted']['styles'][$data['row']['obj']->handle]) {
	$rowIsContracted   = 1;
	$dashSign          = 'plus';
	$dataRowStatusAttr = 'contracted';
}
?>
<tr data-style-handle-row="<?php echo $data['row']['obj']->handle; ?>"
    data-is-hardcoded-asset="true"
    class="wpacu_asset_row <?php echo $data['row']['class']; ?>">
    <td style="position: relative;" data-wpacu-row-status="<?php echo $dataRowStatusAttr; ?>">
        <div class="wpacu_handle_row_expand_contract_area">
            <a data-wpacu-handle="<?php echo $data['row']['obj']->handle; ?>"
               data-wpacu-handle-for="style"
               class="wpacu_handle_row_expand_contract"
               href="#"><span class="dashicons dashicons-<?php echo $dashSign; ?>"></span></a>
            <input type="hidden"
                   id="wpacu_style_<?php echo $data['row']['obj']->handle; ?>_row_contracted_area"
                   name="wpacu_handle_row_contracted_area[styles][<?php echo $data['row']['obj']->handle; ?>]"
                   value="<?php echo $rowIsContracted; ?>" />
        </div>
        <?php
        $insideIeCommentHtml = '<span class="wpacu_inside_cond_comm"><img style="vertical-align: middle;" width="25" height="25" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-ie.svg" alt="" title="Microsoft / Public domain" />&nbsp;<span style="font-weight: 400; color: #1C87CF;">Loads only in Internet Explorer based on the following condition:</span> <em>if '.$data['row']['obj']->inside_conditional_comment.'</em></span>';

        if (isset($data['row']['obj']->src) && $data['row']['obj']->src) {
	        // Source
	        include '_asset-style-single-row-hardcoded/_source.php';
	        ?>
            <div class="wpacu_file_size_area">File Size: <?php echo apply_filters('wpacu_get_asset_file_size', $data['row']['obj'], 'for_print'); ?></div>
	        <?php
	        if ($data['row']['obj']->inside_conditional_comment) {
		        echo $insideIeCommentHtml;
	        }
	        ?>
            <div class="wpacu_hardcoded_part_if_expanded">
                <div style="margin: 10px 0;" class="wpacu-hardcoded-code-area">
                    HTML Output: <code><?php echo htmlentities( $data['row']['obj']->tag_output ); ?></code>
                </div>
            </div>
            <?php
            } else {
            // STYLE inline tag
            $tagOutput = trim($data['row']['obj']->tag_output);

	        // default values (could be changed below)
            $totalCodeLines = 1;
	        $enableViewMore = false;

            if (strpos($tagOutput, "\n") !== false) {
                $totalCodeLines = count(explode("\n", $tagOutput));

                if ($totalCodeLines > 18) {
                    $enableViewMore = true;
                }
            }

	        if (strlen($tagOutput) > 600) {
		        $enableViewMore = true;
	        }
            ?>
            <div class="wpacu-hardcoded-code-area">
                <?php
                if ($tagBelongsTo = \WpAssetCleanUp\HardcodedAssets::belongsTo($data['row']['obj']->tag_output)) {
	                echo '<div style="margin-bottom: 10px;">'.__('Belongs to', 'wp-asset-clean-up').': <strong>'.$tagBelongsTo . '</strong></div>';
                }

                if ($data['row']['obj']->inside_conditional_comment) {
	                echo $insideIeCommentHtml;
                }
                ?>

                <div class="wpacu_hardcoded_part_if_expanded <?php if ($enableViewMore) { ?>wpacu-has-view-more<?php } ?>">
                    <div>
                        <pre><code><?php echo htmlentities( $data['row']['obj']->tag_output ); ?></code></pre>
                    </div>
                    <?php if ($enableViewMore) {
                        $wpacuViewMoreCodeBtnClass = ! is_admin() ? 'wpacu-view-more-code' : 'button';
                        ?>
                        <p class="wpacu-view-more-link-area" style="margin: 0 !important; padding: 15px !important;"><a href="#" class="<?php echo $wpacuViewMoreCodeBtnClass; ?>"><?php _e('View more', 'wp-asset-clean-up'); ?></a></p>
                    <?php } ?>
                </div>

                <div class="wpacu_hardcoded_part_if_contracted">
                    <code>
			            <?php
			            if (strlen($data['row']['obj']->tag_output) > 100) {
				            $tagOutputPart = substr( $data['row']['obj']->tag_output, 0, 100 ). '...';
			            } else {
				            $tagOutputPart = $data['row']['obj']->tag_output;
			            }

			            echo htmlentities($tagOutputPart);
			            ?>
                    </code>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="wpacu_handle_row_expanded_area <?php if ($rowIsContracted) { echo 'wpacu_hide'; } ?>">
	        <div class="wrap_bulk_unload_options">
		        <?php
		        $tagType = '';

		        if (isset($data['row']['obj']->tag_output)) {
			        $tagType = ( strpos( $data['row']['obj']->tag_output, '<link' ) !== false ) ? 'LINK' : 'STYLE';
		        }

		        // Unload on this page
		        include '_asset-style-single-row-hardcoded/_unload-per-page.php';

		        // Unload site-wide (everywhere)
		        include '_asset-style-single-row-hardcoded/_unload-site-wide.php';

		        // Unload on all pages of [post] post type (if applicable)
		        include '_asset-style-single-row-hardcoded/_unload-post-type.php';

		        // Unload via RegEx (if site-wide is not already chosen)
	            include '_asset-style-single-row-hardcoded/_unload-via-regex.php';

	            do_action('wpacu_pro_bulk_unload_output', $data, $data['row']['obj'], 'css');

	            // [wpacu_pro]
		        // If any bulk unload rule is set, show the load exceptions
		        include '_asset-style-single-row-hardcoded/_load-exceptions.php';
		        // [/wpacu_pro]
	            ?>
	            <div class="wpacu-clearfix"></div>
	        </div>
	        <?php
	        // Handle Note
	        include '_asset-style-single-row-hardcoded/_notes.php';
            ?>
        </div>
        <img style="display: none;"
             class="wpacu-ajax-loader"
             src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-ajax-loading-spinner.svg" alt="" />

        <!-- [wpacu_pro] -->
        <input type="hidden"
               id="<?php echo $data['row']['obj']->handle; ?>_hardcoded_data"
               name="wpacu_assets_info_hardcoded_data[styles][<?php echo $data['row']['obj']->handle; ?>]"
               value="<?php echo $data['row']['obj']->hardcoded_data; ?>" />
        <!-- [/wpacu_pro] -->
	</td>
</tr>