<div class="dcform">
	<form action="" method="POST">
		<p>
			<label>Show Error Message Next to the Field ?</label>
			<select name="jvcf7_show_label_error" style="width:240px;"  />
		        <option value="errorMsgshow" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_show_label_error'] == 'errorMsgshow'?'selected="selected"':''; ?>>Yes</option>
		        <option value="noErrorMsg" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_show_label_error'] == 'noErrorMsg'?'selected="selected"':''; ?>>No</option>
		    </select>
		</p>

		<p>
			<label>Invalid Field Indication</label>
			<select name="jvcf7_invalid_field_design" style="width:240px;"  />
			    <option value="theme_0" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_invalid_field_design'] == 'theme_0'?'selected="selected"':''; ?>>Default</option>
			    <option value="theme_1" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_invalid_field_design'] == 'theme_1'?'selected="selected"':''; ?> disabled >Theme 1 (Pro Version Only)</option>
			    <option value="theme_2" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_invalid_field_design'] == 'theme_2'?'selected="selected"':''; ?> disabled >Theme 2 (Pro Version Only)</option>
			    <option value="theme_3" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_invalid_field_design'] == 'theme_3'?'selected="selected"':''; ?> disabled >Theme 3 (Pro Version Only)</option>
			    <option value="theme_4" <?php echo $GLOBALS['jvcf7_default_settings']['jvcf7_invalid_field_design'] == 'theme_4'?'selected="selected"':''; ?> disabled >Theme 4 (Pro Version Only)</option>
			</select>
		</p>

		<p>
			<label>&nbsp;</label>
			<input type="submit" name="save-jvcf7-options" class="button-primary" value="Save Settings" />
		</p>
	</form>
</div>