<form method="post" action="options.php">
<?php settings_fields( 'jvcf7p-error-settings-group' ); ?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Error Message</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            
           
            <table class="form-table jvcf7p_form">
                <tr valign="top">
                    <td scope="row">Required</td>
                    <td>
                       <input name="jvcf7p_msg_required" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_required'] ; ?>" type="text" />                    </td>                    
                </tr>
                
                <tr valign="top">
                    <td scope="row">Email</td>
                    <td>
                       <input name="jvcf7p_msg_email" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_email'] ; ?>" type="text" />                    </td>                    
                </tr>
                
                <tr valign="top">
                    <td scope="row">Url</td>
                    <td>
                       <input name="jvcf7p_msg_url" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_url'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Date</td>
                    <td>
                       <input name="jvcf7p_msg_date" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_date'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Date ISO</td>
                    <td>
                       <input name="jvcf7p_msg_dateISO" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_dateISO'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Numbers Only</td>
                    <td>
                       <input name="jvcf7p_msg_number" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_number'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Digits Only</td>
                    <td>
                       <input name="jvcf7p_msg_digits" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_digits'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Alpha Numeric</td>
                    <td>
                       <input name="jvcf7p_msg_alpha_numeric" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_alpha_numeric'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Letters Only</td>
                    <td>
                       <input name="jvcf7p_msg_letters_only" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_letters_only'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Letters and Space Only</td>
                    <td>
                       <input name="jvcf7p_msg_letters_space" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_letters_space'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Credit Card</td>
                    <td>
                       <input name="jvcf7p_msg_creditcard" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_creditcard'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">US Phone number</td>
                    <td>
                       <input name="jvcf7p_msg_phoneUS" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_phoneUS'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Equal To</td>
                    <td>
                       <input name="jvcf7p_msg_equalTo" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_equalTo'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">File Extension</td>
                    <td>
                       <input name="jvcf7p_msg_extension" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_extension'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Require From Group</td>
                    <td>
                       <input name="jvcf7p_msg_require_from_group" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_require_from_group'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Max Length</td>
                    <td>
                       <input name="jvcf7p_msg_maxlength" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_maxlength'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Min Length</td>
                    <td>
                       <input name="jvcf7p_msg_minlength" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_minlength'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Range Length</td>
                    <td>
                       <input name="jvcf7p_msg_rangelength" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_rangelength'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Range</td>
                    <td>
                       <input name="jvcf7p_msg_range" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_range'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Max</td>
                    <td>
                       <input name="jvcf7p_msg_max" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_max'] ; ?>" type="text" />                    </td>                    
                </tr>
                 <tr valign="top">
                    <td scope="row">Min</td>
                    <td>
                       <input name="jvcf7p_msg_min" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_min'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">IBAN</td>
                    <td>
                       <input name="jvcf7p_msg_iban" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_iban'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">BIC</td>
                    <td>
                       <input name="jvcf7p_msg_bic" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_bic'] ; ?>" type="text" />                    </td>                    
                </tr>
                <tr valign="top">
                    <td scope="row">Custom Code</td>
                    <td>
                       <input name="jvcf7p_msg_custom_code" style="width:500px;" value="<?php echo $optionValues['jvcf7p_msg_custom_code'] ; ?>" type="text" />                    </td>                    
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