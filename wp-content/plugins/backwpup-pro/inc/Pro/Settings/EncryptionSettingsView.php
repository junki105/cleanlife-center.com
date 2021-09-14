<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\Settings;

use Inpsyde\BackWPup\Settings\SettingTab;

/**
 * Class EncryptionSettings
 */
class EncryptionSettingsView implements SettingTab
{

    public function tab()
    {

        if (!\BackWPup::is_pro()) {
            return;
        }

        ?>
        <div class="table ui-tabs-hide" id="backwpup-tab-encryption">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Encryption Type', 'backwpup') ?>
                    </th>
                    <td>
                        <?php self::encryption_option_fieldsets() ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Encryption Key', 'backwpup') ?>
                    </th>
                    <td>
                        <?php self::encryption_key_fieldsets() ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        $this->asymmetric_generate_key_modal();
        $this->asymmetric_key_pair_validate_modal();
    }

    private function encryption_option_fieldsets()
    {
        $option = get_site_option('backwpup_cfg_encryption', 'symmetric');
        ?>
        <fieldset class="bwu-setting-fieldset bwu-setting-fieldset--symmetric">
            <label for="bwu_encryption_symmetric">
                <input
                    type="radio"
                    name="encryption"
                    id="bwu_encryption_symmetric"
                    class="bwu-encryption-input"
                    value="symmetric"
                    <?php checked($option, 'symmetric') ?>
                />
                <?php esc_html_e('Symmetric (public key only)', 'backwpup') ?>
            </label>
            <div class="bwu-field-description">
                <p>
                    <?php
                    echo wp_kses(
                        __(
                            'Generate a single unique 256-bit key. This is a one time option, key cannot be changed and new keys cannot be created.',
                            'backwpup'
                        ),
                        array('strong' => array())
                    )
                    ?>
                </p>
                <p>
                    <i>
                        <?php
                        esc_html_e(
                            'Note: Download the encryption key to be able to restore the backups using the standalone application.',
                            'backwpup'
                        )
                        ?>
                    </i>
                </p>
            </div>
        </fieldset>
        <fieldset class="bwu-setting-fieldset bwu-setting-fieldset--asymmetric">
            <label for="bwu_encryption_asymmetric">
                <input
                    type="radio"
                    name="encryption"
                    id="bwu_encryption_asymmetric"
                    class="bwu-encryption-input"
                    value="asymmetric"
                    <?php checked($option, 'asymmetric') ?>
                />
                <?php esc_html_e('Asymmetric (public and private key)', 'backwpup') ?>
            </label>
            <div class="bwu-field-description">
                <p>
                    <?php esc_html_e('Generate a RSA public/private key pair (more secure).', 'backwpup') ?>
                </p>
                <p>
                    <?php
                    esc_html_e(
                        'You can create as many key pairs as you want, but they will override the previous one.”',
                        'backwpup'
                    )
                    ?>
                </p>
                <p>
                    <i>
                        <?php
                        echo wp_kses(
                            __(
                                'Note: You will be asked to download the keys for safe storage. The plugin does not keep a copy of the private key, so if you lose this, your data cannot be decrypted!',
                                'backwpup'
                            ),
                            array('strong' => array())
                        )
                        ?>
                    </i>
                </p>
                <p>
                    <?php
                    echo wp_kses(
                        __(
                            'You can also <strong>Validate</strong> that you have the correct private key for the given public key.',
                            'backwpup'
                        ),
                        array('strong' => array())
                    )
                    ?>
                </p>
            </div>
        </fieldset>
        <?php
    }

    private function encryption_key_fieldsets()
    {

        $public_key_option = get_site_option('backwpup_cfg_publickey');
        $symmetric_enc_key = get_site_option('backwpup_cfg_encryptionkey');
        $encryption_textarea_readonly = $public_key_option ? 'readonly="readonly"' : '';
        ?>
        <fieldset id="symmetric_key_container">
            <code
                id="symmetric_key_code"><?php echo esc_html(sanitize_text_field($symmetric_enc_key)) ?></code>
            <input
                type="hidden"
                name="symmetric_key"
                id="symmetric_key"
                readonly="readonly"
                value="<?php echo esc_attr(get_site_option('backwpup_cfg_encryptionkey')) ?>"
            />
            <p class="encryption-actions">
                <button
                    id="symmetric_key_generator"
                    class=" button button-primary"
                    <?php echo $symmetric_enc_key ? 'style="display:none"' : '' ?>
                >
                    <?php esc_html_e('Generate Key', 'backwpup') ?>
                </button>
                <a
                    id="symmetric_key_downloader"
                    class="button button-primary"
                    download="backwpup_encrypt_key.txt"
                    href="data:application/octet-stream;charset=utf-16le;base64,<?php echo esc_attr(base64_encode($symmetric_enc_key)) ?>"
                    <?php echo (!$symmetric_enc_key) ? 'style="display:none"' : '' ?>
                >
                    <?php esc_html_e('Download Key', 'backwpup') ?>
                </a>
            </p>
        </fieldset>
        <fieldset id="asymmetric_key_container">
            <code
                id="asymmetric_public_key_code"><?php echo $this->format_public_key($public_key_option) ?></code>
            <input
                type="hidden"
                name="asymmetric_public_key"
                id="asymmetric_public_key"
                value="<?php echo esc_attr($public_key_option) ?>"
                <?php echo esc_attr($encryption_textarea_readonly) ?>
            />
            <p class="encryption-actions">
                <a
                    href="#TB_inline?height=440&width=630&inlineId=asymmetric_key_pair_container"
                    id="asymmetric_key_pair_generator"
                    class="thickbox button button-primary"
                >
                    <?php esc_html_e('Generate Key Pair', 'backwpup') ?>
                </a>
                <a
                    href="#TB_inline?height=440&width=630&inlineId=asymmetric_key_pair_validate_container"
                    id="asymmetric_key_open_validate_modal"
                    class="thickbox button"
                >
                    <?php esc_html_e('Validate', 'backwpup') ?>
                </a>
            </p>
        </fieldset>
        <?php
    }

    private function asymmetric_key_pair_validate_modal()
    {

        ?>
        <div id="asymmetric_key_pair_validate_container" style="display: none;">
            <div id="asymmetric_key_pair_validate">
                <p>
                    <?php
                    esc_html_e(
                        'Enter your private key below to verify it will work with the provided public key.',
                        'backwpup'
                    ); ?>
                    <br/>
                    <?php
                    esc_html_e(
                        'The private key will not be stored, so you must still securely store it yourself.',
                        'backwpup'
                    );
                    ?>
                </p>
                <p>
                    <label for="private_key_validate_area">
                        <?php esc_html_e('Private Key', 'backwpup') ?>
                    </label>
                    <textarea id="private_key_validate_area" rows="8" cols="40"></textarea>
                </p>
                <button id="asymmetric_key_pair_do_validation" class="button button-primary">
                    <?php esc_html_e('Validate', 'backwpup') ?>
                </button>
            </div>
        </div>
        <?php
    }

    private function asymmetric_generate_key_modal()
    {

        ?>
        <div id="asymmetric_key_pair_container" style="display: none;">
            <p id="asymmetric_key_generation_waiting">
                <?php esc_html_e('Your keys are being generated. Please hold on &hellip;', 'backwpup') ?>
            </p>

            <div id="asymmetric_generated_key_modal" style="display: none;">
                <div class="bwu-generated-key">
                    <section class="bwu-generated-key__public">
                        <h4><?php esc_html_e('Public Key', 'backwpup') ?></h4>
                        <code class="bwu-the-key"></code>
                        <a
                            id="asymmetric_generated_public_key_downloader"
                            download="id_rsa_backwpup.pub"
                            class="button button-primary"
                        >
                            <?php esc_html_e('Download Public Key', 'backwpup') ?>
                        </a>
                    </section>

                    <section class="bwu-generated-key__private">
                        <h4><?php esc_html_e('Private Key', 'backwpup') ?></h4>
                        <code class="bwu-the-key"></code>
                        <a
                            id="asymmetric_generated_private_key_downloader"
                            download="id_rsa_backwpup.pri"
                            class="button button-primary"
                        >
                            <?php esc_html_e('Download Private Key', 'backwpup') ?>
                        </a>
                    </section>
                </div>

                <p>
                    <?php
                    esc_html_e(
                        'Please download at least the private key you entered above. The plugin will not store this key, and without it your backups cannot be decrypted.',
                        'backwpup'
                    );
                    ?>
                </p>
                <p>
                    <?php esc_html_e('Click the button below to use the generated keys.', 'backwpup') ?>
                </p>
                <p>
                    <button id="asymmetric_keys_selector" class="button">
                        <?php esc_html_e('Use These Keys', 'backwpup') ?>
                    </button>
                </p>
            </div>
        </div>
        <?php
    }

    private function format_public_key($public_key_option)
    {

        return str_replace(
            array('-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----'),
            array('-----BEGIN PUBLIC KEY-----<br/>', '<br/>-----END PUBLIC KEY-----'),
            $public_key_option
        );
    }
}
