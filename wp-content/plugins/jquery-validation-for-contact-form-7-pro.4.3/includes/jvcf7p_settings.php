<form method="post" action="options.php">
<?php settings_fields( 'jvcf7p-settings-group' ); ?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Settings</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            
            <table class="form-table jvcf7p_form">
                <tr valign="top">
                    <td scope="row">Show Error Message Next to the Field ?</td>
                    <td>
                        <select name="jvcf7p_show_label_error" style="width:150px;"  />
                            <option value="errorMsgshow" <?php echo $jvcf7p_show_label_error == 'errorMsgshow'?'selected="selected"':''; ?>>Yes</option>
                            <option value="noErrorMsg" <?php echo $jvcf7p_show_label_error == 'noErrorMsg'?'selected="selected"':''; ?>>No</option>
                        </select>
                        
                    </td>
                    <?php /*
                    <td><em>Demo:</em> <br/>
                        <img src="<?php echo plugins_url('jquery-validation-for-contact-form-7/img/show_error_label.png'); ?>" /></td>
					*/ ?>
                </tr>
                
                <tr valign="top">
                    <td scope="row">Invalid Field Indication</td>
                    <td>
                        <select name="jvcf7p_invalid_field_design" style="width:150px;"  />
                            <option value="theme_0" <?php echo $optionValues['jvcf7p_invalid_field_design'] == 'theme_0'?'selected="selected"':''; ?>>-None-</option>
                            <option value="theme_1" <?php echo $optionValues['jvcf7p_invalid_field_design'] == 'theme_1'?'selected="selected"':''; ?>>Theme 1</option>
                            <option value="theme_2" <?php echo $optionValues['jvcf7p_invalid_field_design'] == 'theme_2'?'selected="selected"':''; ?>>Theme 2</option>
                            <option value="theme_3" <?php echo $optionValues['jvcf7p_invalid_field_design'] == 'theme_3'?'selected="selected"':''; ?>>Theme 3</option>
                            <option value="theme_4" <?php echo $optionValues['jvcf7p_invalid_field_design'] == 'theme_4'?'selected="selected"':''; ?>>Theme 4</option>
                        </select>
                    </td>
                    <?php /*
                    <td>
                    <em>Demo:</em> <br/>
                        <img src="<?php echo plugins_url('jquery-validation-for-contact-form-7/img/highlight_invalid_fields.png'); ?>" />
                    </td>
					*/ ?>
                    
                </tr>
            </table>
            
            <p class="submit">
            <input type="submit" name="submit-jvcf7" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
            
            
        </td>
        
    </tr>
    </tbody>
</table>
<br/>
</form>