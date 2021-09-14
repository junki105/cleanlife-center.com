<?php
use WpAssetCleanUpPro\Positions;

/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$assetsPositionsClass = new Positions();
$assetsPositions = $assetsPositionsClass->getAssetsPositions();

$hasChangedStylesPositions  = isset($assetsPositions['styles'])  && ! empty($assetsPositions['styles']);
$hasChangedScriptsPositions = isset($assetsPositions['scripts']) && ! empty($assetsPositions['scripts']);

$isUpdateable = $hasChangedStylesPositions || $hasChangedScriptsPositions;

do_action('wpacu_admin_notices');
?>
<p>This is the list of all the CSS/JS that had its original position changed (e.g. from <code>&lt;HEAD&gt;</code> to <code>&lt;BODY&gt;</code> (also known as: footer) to reduce render blocking resources, or from <code>&lt;BODY&gt;</code> to <code>&lt;HEAD&gt;</code> for early triggering).</p>

<form action="" method="post">
    <h2>Styles (.css)</h2>
<?php if ($hasChangedStylesPositions) { ?>
    <table style="width: 96%;" class="wp-list-table widefat fixed striped">
        <tr>
            <td style="width: 320px;"><strong>Handle</strong></td>
            <td style="width: 150px;">Initial Position</td>
            <td style="width: 150px;"><strong>Current Position</strong></td>
            <td><strong>Actions</strong></td>
        </tr>

        <?php
        ksort($assetsPositions['styles']);

        foreach ($assetsPositions['styles'] as $styleHandle => $styleNewPosition) {
            $initialPosition = ($styleNewPosition === 'body') ? '&lt;HEAD&gt;' : '&lt;BODY&gt;';
            $newPosition     = ($styleNewPosition === 'body') ? '&lt;BODY&gt;' : '&lt;HEAD&gt;';
        ?>
            <tr class="wpacu_restore_position_row">
                <td><?php wpacuRenderHandleTd($styleHandle, 'styles', $data); ?></td>
                <td><code><?php echo $initialPosition; ?></code></td>
                <td><code style="color: #004567; font-weight: bold;"><?php echo $newPosition; ?></code></td>
                <td>
                    <label><input type="checkbox"
                                  class="wpacu_restore_position"
                                  name="wpacu_styles_new_positions[<?php echo $styleHandle; ?>]"
                                  value="remove" /> Move CSS link tag back to <?php echo $initialPosition; ?></label>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php } else { ?>
    <p>There are no changes regarding LINK tag position for any of the CSS files.</p>
    <?php } ?>

<div style="margin: 20px 0; width: 96%;">
    <hr/>
</div>

    <h2>Scripts (.js)</h2>
<?php if ($hasChangedScriptsPositions) { ?>
    <table style="width: 96%;" class="wp-list-table widefat fixed striped">
        <tr>
            <td style="width: 320px;"><strong>Handle</strong></td>
            <td style="width: 150px;">Initial Position</td>
            <td style="width: 150px;"><strong>Current Position</strong></td>
            <td><strong>Actions</strong></td>
        </tr>

	    <?php
	    ksort($assetsPositions['scripts']);

	    foreach ($assetsPositions['scripts'] as $scriptHandle => $scriptNewPosition) {
		    $initialPosition = ($scriptNewPosition === 'body') ? '&lt;HEAD&gt;' : '&lt;BODY&gt;';
		    $newPosition     = ($scriptNewPosition === 'body') ? '&lt;BODY&gt;' : '&lt;HEAD&gt;';
		    ?>
            <tr class="wpacu_restore_position_row">
                <td><?php wpacuRenderHandleTd($scriptHandle, 'scripts', $data); ?></td>
                <td><code><?php echo $initialPosition; ?></code></td>
                <td><code style="color: #004567; font-weight: bold;"><?php echo $newPosition; ?></code></td>
                <td>
                    <label><input type="checkbox"
                                  class="wpacu_restore_position"
                                  name="wpacu_scripts_new_positions[<?php echo $scriptHandle; ?>]"
                                  value="remove" /> Move JS script tag back to <?php echo $initialPosition; ?></label>
                </td>
            </tr>
		    <?php
	    }
	    ?>
    </table>
<?php } else { ?>
    <p>There are no changes regarding SCRIPT tag position for any of the JavaScript files.</p>
<?php } ?>

    <?php
    if ($isUpdateable) {
	    wp_nonce_field('wpacu_restore_assets_positions', 'wpacu_restore_assets_positions_nonce');
    }
    ?>
    <div id="wpacu-update-button-area" class="no-left-margin">
        <p style="margin: 20px 0 0 0;">
            <input type="submit"
                   name="submit"
                   <?php if (! $isUpdateable) { ?>disabled="disabled"<?php } ?>
                   class="wpacu-restore-pos-btn button button-primary"
                   value="Restore position of chosen CSS/JS" />

            <?php
            if (! $isUpdateable) {
                ?>
                &nbsp;&nbsp; <small>Note: As there are no positions changed for any CSS/JS, the update button is not enabled.</small>
                <?php
            }
            ?>
        </p>
        <div id="wpacu-updating-settings" style="margin-left: 266px; top: 21px;">
            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
        </div>
    </div>
</form>