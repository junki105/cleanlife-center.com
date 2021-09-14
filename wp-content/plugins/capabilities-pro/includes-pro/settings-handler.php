<?php

namespace PublishPress\Capabilities;

class Pro_Settings_Handler {
    public function __construct() {
        $this->handleUpdate();
    }

    public function handleUpdate() {
        if (!empty($_POST['all_options_pro'])) {
            foreach (explode(',', $_POST['all_options_pro']) as $option_name) {
                $value = isset($_POST[$option_name]) ? $_POST[$option_name] : '';
    
                if (!is_array($value)) {
                    $value = trim($value);
                }
    
                update_option($option_name, stripslashes_deep($value));
            }
        }
    }
}
