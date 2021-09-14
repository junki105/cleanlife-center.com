<?php
namespace PublishPress\Capabilities;

require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/pro-maint.php');

switch ($_GET['publishpress_caps_ajax_settings']) {
    case 'activate_key':
        check_admin_referer('wp_ajax_pp_activate_key');
        if (
            is_multisite() && !is_super_admin() && (Maint::isNetworkActivated() || Maint::isMuPlugin())
        ) {
            return;
        }

        $request_vars = [
            'edd_action' => "activate_license",
            'item_id' => PUBLISHPRESS_CAPS_EDD_ITEM_ID,
            'license' => sanitize_key($_GET['key']),
            'url' => site_url(''),
        ];

        $response = Maint::callHome('activate_license', $request_vars);

        $result = json_decode($response);
        if (is_object($result) && ('valid' == $result->license)) {
            $setting = ['license_status' => $result->license, 'license_key' => $_GET['key'], 'expire_date' => $result->expires];
            update_option('cme_edd_key', $setting);
        }

        echo $response;
        exit();

        break;

    case 'deactivate_key':
        check_admin_referer('wp_ajax_pp_deactivate_key');
        if (
            is_multisite() && !is_super_admin() && (Maint::isNetworkActivated() || Maint::isMuPlugin())
        ) {
            return;
        }

        $support_key = get_option('cme_edd_key');
        $request_vars = [
            'edd_action' => "deactivate_license",
            'item_id' => PUBLISHPRESS_CAPS_EDD_ITEM_ID,
            'license' => $support_key['license_key'],
            'url' => site_url(''),
        ];

        $response = Maint::callHome('deactivate_license', $request_vars);

        $result = json_decode($response);
        if (is_object($result) && $result->license != 'valid') {
            delete_option('cme_edd_key');
        }

        echo $response;
        exit();

        break;
}
