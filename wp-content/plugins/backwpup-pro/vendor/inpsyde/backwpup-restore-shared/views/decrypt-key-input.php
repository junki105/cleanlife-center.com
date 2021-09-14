<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>

<div id="decrypt_key" style="display: none;">
	<p>
		<?php echo $this->translator->trans( 'Please enter your decryption key to decrypt your backup.' ) ?>
	</p>
	<p>
		<label for="decryption_key">
			<?php echo $this->translator->trans( 'Decryption Key' ) ?>
		</label>
		<textarea id="decryption_key" name="decryption_key" style="width: 100%; overflow: scroll;" rows="8"></textarea>
	</p>
	<button id="submit_decrypt_key" class="button button-primary">
		<?php echo $this->translator->trans( 'Submit' ) ?>
	</button>
</div>
