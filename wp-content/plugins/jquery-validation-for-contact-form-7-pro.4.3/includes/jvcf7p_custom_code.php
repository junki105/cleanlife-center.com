<?php 
if (isset($_POST['submit-jvcf7-code'])):
	$jvcf7p_custom_codes 		= $_POST['jvcf7p_custom_codes'];
	$jvcf7p_custom_code_array 	= explode("\n", $jvcf7p_custom_codes);
	$jvcf7p_custom_code_array	= array_map('trim', $jvcf7p_custom_code_array);
	$jvcf7p_custom_code_array   = array_filter($jvcf7p_custom_code_array);
	$jvcf7p_custom_code_json	= json_encode($jvcf7p_custom_code_array);
	update_option('jvcf7p_custom_codes', $jvcf7p_custom_code_json);
	echo '<div class="message updated"><p>Custom Code Updated</p></div>';
endif;
$jvcf7p_custom_codes_json 		= get_option('jvcf7p_custom_codes');
$jvcf7p_custom_codes_array		= json_decode($jvcf7p_custom_codes_json, true);
$jvcf7p_custom_codes 			= '';
if (!empty($jvcf7p_custom_codes_array)){
	$jvcf7p_custom_codes = implode("\n", $jvcf7p_custom_codes_array);
}
?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Custom Code Validation</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
        <h4>Please enter your custom code here. Each line indicate 1 entry of the code. And please use class <strong style="color:#090">customCode</strong> to validate the field with these codes.</h4>  
        <form method="post">
        <textarea name="jvcf7p_custom_codes" cols="60" rows="10"><?php echo $jvcf7p_custom_codes; ?></textarea>
		<p class="submit">
            <input type="submit" name="submit-jvcf7-code" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>	
        </form>
</td>
</tr>
</tbody>
</table>
<br/>