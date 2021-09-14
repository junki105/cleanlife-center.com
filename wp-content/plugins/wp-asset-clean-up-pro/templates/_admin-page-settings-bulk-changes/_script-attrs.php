<?php
use WpAssetCleanUpPro\MainPro;

/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$scriptGlobalAttrs = MainPro::instance()->getScriptGlobalAttributes();

$hasAsyncSiteWide = isset($scriptGlobalAttrs['async']) && ! empty($scriptGlobalAttrs['async']);
$hasDeferSiteWide = isset($scriptGlobalAttrs['defer']) && ! empty($scriptGlobalAttrs['defer']);

$isUpdateable = $hasAsyncSiteWide || $hasDeferSiteWide;

do_action('wpacu_admin_notices');
?>
<p>This is the list of all the JavaScript files (<code>&lt;script&gt;</code> tags) that have "async" and "defer" attributes applied site-wide (everywhere).</p>

<form action="" method="post">
    <h2>async - <code>&lt;script async&gt;</code></h2>
	<?php if ($hasAsyncSiteWide) { ?>
        <table style="width: 96%;" class="wp-list-table widefat fixed striped">
            <tr>
                <td style="width: 280px;"><strong>Handle</strong></td>
                <td><strong>Actions</strong></td>
            </tr>

			<?php
			ksort($scriptGlobalAttrs['async']);

			foreach ($scriptGlobalAttrs['async'] as $scriptHandle) {
				?>
                <tr class="wpacu_remove_global_attr_row">
                    <td><?php wpacuRenderHandleTd($scriptHandle, 'scripts', $data); ?></td>
                    <td>
                        <label><input type="checkbox"
                                      class="wpacu_remove_global_attr"
                                      name="wpacu_options_global_attribute_scripts[async][<?php echo $scriptHandle; ?>]"
                                      value="remove" /> Remove site-wide attribute for this script</label>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
	<?php } else { ?>
        <p>There are no site-wide "async" attributes applied to any script tag.</p>
	<?php } ?>

    <div style="margin: 20px 0; width: 96%;">
        <hr/>
    </div>

    <h2>defer - <code>&lt;script defer&gt;</code></h2>
	<?php if ($hasDeferSiteWide) { ?>
        <table style="width: 96%;" class="wp-list-table widefat fixed striped">
            <tr>
                <td style="width: 280px;"><strong>Handle</strong></td>
                <td><strong>Actions</strong></td>
            </tr>

			<?php
			ksort($scriptGlobalAttrs['defer']);

			foreach ($scriptGlobalAttrs['defer'] as $scriptHandle) {
				?>
                <tr class="wpacu_remove_global_attr_row">
                    <td><?php wpacuRenderHandleTd($scriptHandle, 'scripts', $data); ?></td>
                    <td>
                        <label><input type="checkbox"
                                      class="wpacu_remove_global_attr"
                                      name="wpacu_options_global_attribute_scripts[defer][<?php echo $scriptHandle; ?>]"
                                      value="remove" /> Remove site-wide attribute for this script</label>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
	<?php } else { ?>
        <p>There are no site-wide "defer" attributes applied to any script tag.</p>
	<?php } ?>

	<?php
    if ($isUpdateable) {
	    wp_nonce_field('wpacu_remove_global_attrs', 'wpacu_remove_global_attrs_nonce');
    }
	?>
    <div id="wpacu-update-button-area" class="no-left-margin">
        <p style="margin: 20px 0 0 0;">
            <input type="submit"
                   name="submit"
                   <?php if (! $isUpdateable) { ?>disabled="disabled"<?php } ?>
                   class="wpacu-restore-pos-btn button button-primary"
                   value="Remove chosen site-wide attributes" />

            <?php
            if (! $isUpdateable) {
                ?>
                &nbsp;&nbsp; <small>Note: As there are no site-wide async/defer attributes applied for any script tags, the update button is not enabled.</small>
                <?php
            }
            ?>
        </p>
        <div id="wpacu-updating-settings" style="margin-left: 275px; top: 20px;">
            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
        </div>
    </div>
</form>