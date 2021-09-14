<?php
/**
 * Render a textarea field
 */

defined( 'ABSPATH' ) || exit;

$name        = isset( $field['name'] ) ? $field['name'] : '';
$class       = isset( $field['class'] ) ? $field['class'] : '';
$label       = isset( $field['label'] ) ? $field['label'] : '';
$tooltip     = isset( $field['tooltip'] ) ? cf7r_tooltip( $field['tooltip'] ) : '';
$sub_title   = isset( $field['sub_title'] ) ? $field['sub_title'] : '';
$input_class = isset( $field['input_class'] ) ? $field['input_class'] : '';
$input_attr  = isset( $field['input_attr'] ) ? $field['input_attr'] : '';
$footer      = isset( $field['footer'] ) ? $field['footer'] : '';
$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
$value       = isset( $field['value'] ) ? $field['value'] : '';

?>
<div class="field-wrap field-wrap-<?php echo esc_html( $name ); ?> <?php echo esc_html( $class ); ?>">
	<label for="wpcf7-redirect-<?php echo esc_html( $name ); ?>">
		<strong><?php echo esc_html( $label ); ?></strong>
	</label>
	<?php if ( $sub_title ) : ?>
		<div class="wpcf7-subtitle">
			<?php echo esc_html( $sub_title ); ?>
		</div>
	<?php endif; ?>
	<textarea rows="10" class="wpcf7-redirect-<?php echo esc_html( $name ); ?>-fields" placeholder="<?php echo esc_html( $placeholder ); ?>" name="wpcf7-redirect<?php echo esc_html( $prefix ); ?>[<?php echo esc_html( $name ); ?>]"><?php echo esc_html( $value ); ?></textarea>
	<div class="field-footer">
		<?php echo  $footer ; ?>
	</div>
</div>
