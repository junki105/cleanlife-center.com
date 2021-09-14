
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>License</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            
            <form method="post" action="">
		
			<table class="form-table jvcf7p_form">
				<tbody>
					<tr valign="top">	
						<td scope="row" valign="top">
							<?php _e('Status'); ?>
						</td>
						
                        <td>
							 <?php $licenseStatus = get_option('jvcf7p_license_status'); ?>
                			<?php $licenseKey 	 = trim(get_option('jvcf7p_license_key')); ?>
                            <strong class="<?php echo $licenseStatus; ?>"><?php echo ucfirst($licenseStatus); ?></strong>	
                            
                        </td>
					</tr>
                    <tr valign="top">	
						<td scope="row" valign="top">
							<?php _e('License Key'); ?>
						</td>
						
                        <td>
							<input id="jvcf7p_license_key" name="jvcf7p_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
						</td>
					</tr>
					
						<tr valign="top">	
							<td scope="row" valign="top">&nbsp;
								
							</td>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<?php wp_nonce_field( 'jvcf7p_nonce', 'jvcf7p_nonce' ); ?>
									<input type="submit" class="button-secondary" name="jvcf7p_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
								<?php } else {
									wp_nonce_field( 'jvcf7p_nonce', 'jvcf7p_nonce' ); ?>
									<input type="submit" class="button-secondary" name="jvcf7p_license_activate" value="<?php _e('Activate License'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					
				</tbody>
			</table>	
			<?php //submit_button(); ?>
		
		</form>
            
        </td>
        
    </tr>
    </tbody>
</table>
<br/>
