<?php
/**
 * Capability Manager Admin Menu Permissions.
 * Hide and block selected Admin Menus per-role.
 *
 *    Copyright 2020, PublishPress <help@publishpress.com>
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    version 2 as published by the Free Software Foundation.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

global $capsman, $menu, $submenu;
$roles = $capsman->roles;
$default_role = $capsman->current;
$role_caption = translate_user_role($roles[$default_role]);

$admin_global_menu = PPC_ADMIN_GLOBAL_MENU;
$admin_global_submenu = PPC_ADMIN_GLOBAL_SUBMENU;

$admin_menu_option = !empty(get_option('capsman_admin_menus')) ? get_option('capsman_admin_menus') : [];
$admin_menu_option = array_key_exists($default_role, $admin_menu_option) ? (array)$admin_menu_option[$default_role] : [];

$admin_child_menu_option = !empty(get_option('capsman_admin_child_menus')) ? get_option('capsman_admin_child_menus') : [];
$admin_child_menu_option = array_key_exists($default_role, $admin_child_menu_option) ? (array)$admin_child_menu_option[$default_role] : [];
?>

<div class="wrap publishpress-caps-manage pressshack-admin-wrapper pp-capability-menus-wrapper">
    <div id="icon-capsman-admin" class="icon32"></div>
    <h2><?php _e('Admin Menu Restrictions', 'capabilities-pro'); ?></h2>

    <form method="post" id="ppc-admin-menu-form" action="admin.php?page=pp-capabilities-admin-menus">
        <?php wp_nonce_field('pp-capabilities-admin-menus'); ?>

        <fieldset>
            <table id="akmin">
                <tr>
                    <td class="content">

                        <div class="publishpress-headline">
                            <span class="cme-subtext">
                            <span class='pp-capability-role-caption'>
                            <?php
                            _e('Note: You are only restricting access to admin menu screens. Some plugins may also add features to other areas of WordPress.', 'capabilities-pro');
                            ?>
                            </span>
                            </span>
                        </div>
                        <div class="publishpress-filters">
                            <select name="ppc-admin-menu-role" class="ppc-admin-menu-role">
                                <?php
                                foreach ($roles as $role => $name) :
                                    $name = translate_user_role($name);
                                    ?>
                                    <option value="<?php echo $role;?>" <?php selected($default_role, $role);?>><?php echo $name;?></option>
                                <?php
                                endforeach;
                                ?>
                            </select> &nbsp;

                            <img class="loading" src="<?php echo $capsman->mod_url; ?>/images/wpspin_light.gif" style="display: none">

                            <input type="submit" name="admin-menu-submit"
                                value="<?php _e('Save Changes', 'capabilities-pro') ?>"
                                class="button-primary ppc-admin-menu-submit" style="float:right" />
                        </div>

                        <div id="pp-capability-menu-wrapper" class="postbox">
                            <div class="pp-capability-menus">

                                <div class="pp-capability-menus-wrap">
                                    <div id="pp-capability-menus-general"
                                         class="pp-capability-menus-content editable-role" style="display: block;">

                                        <table class="wp-list-table widefat fixed striped pp-capability-menus-select">

                                            <thead>
                                                <tr class="ppc-menu-row parent-menu">

                                                    <td class="restrict-column ppc-menu-checkbox">
                                                        <input id="check-all-item" class="check-item check-all-menu-item" type="checkbox"/>
                                                    </td>
                                                    <td class="menu-column ppc-menu-item">
                                                        <label for="check-all-item">
                                                        <span class="menu-item-link check-all-menu-link">
                                                            <strong>
                                                            <?php _e('Toggle all', 'capabilities-pro'); ?>
                                                            </strong>
                                                        </span></label>
                                                    </td>

                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr class="ppc-menu-row parent-menu">

                                                    <td class="restrict-column ppc-menu-checkbox">
                                                        <input id="check-all-item-2" class="check-item check-all-menu-item" type="checkbox"/>
                                                    </td>
                                                    <td class="menu-column ppc-menu-item">
                                                        <label for="check-all-item-2">
                                                            <span class="menu-item-link check-all-menu-link">
                                                            <strong>
                                                                <?php _e('Toggle all', 'capabilities-pro'); ?>
                                                            </strong>
                                                            </span>
                                                        </label>
                                                    </td>

                                                </tr>
                                            </tfoot>

                                            <tbody>

                                            <?php

                                            if (isset($admin_global_menu) && '' !== $admin_global_menu) {

                                                ksort($admin_global_menu);

                                                if (!get_option('link_manager_enabled')) {
                                                    if (isset($admin_global_menu[15]) && ('edit-tags.php?taxonomy=link_category' == $admin_global_menu[15][2])) {
                                                        unset($admin_global_menu[15]);
                                                    }

                                                    unset($admin_global_submenu['edit-tags.php?taxonomy=link_category']);
                                                }

                                                $sn = 0;
                                                foreach ($admin_global_menu as $key => $item) {

                                                    $item_menu_slug = $item[2];

                                                    if ('' === $item_menu_slug || (!$item[0] && !isset($admin_global_submenu[$item_menu_slug]))) {
                                                        continue;
                                                    }

                                                    //disable capmans checkbox if admin is editing own role
                                                    if ($item_menu_slug === 'pp-capabilities' && in_array($default_role, wp_get_current_user()->roles)) {
                                                        $disabled_field = ' disabled="disabled"';
                                                        $disabled_class = ' disabled';

                                                        $disabled_info = '<div class="tooltip"><i class="dashicons dashicons-info"></i> <span class="tooltiptext">' 
                                                        . __('This option is disabled to prevent complete lockout', 'capabilities-pro') 
                                                        . '</span></div>';

                                                    } else {
                                                        $disabled_field = $disabled_class = $disabled_info = '';
                                                    }

                                                    $menu_title = ppc_process_admin_menu_title($item[0]);

                                                    // Which icon should we use?
                                                    $icon_name = null;
                                                    switch(strip_tags($item[0])) {
                                                        default:
                                                            $icon_name = 'open-folder';
                                                            break;
                                                        case 'Dashboard':
                                                            $icon_name = 'dashboard';
                                                            break;
                                                        case 'Media':
                                                            $icon_name = 'admin-media';
                                                            break;
                                                        case 'Links':
                                                            $icon_name = 'admin-links';
                                                            break;
                                                        case 'Posts':
                                                            $icon_name = 'admin-post';
                                                            break;
                                                        case 'Pages':
                                                            $icon_name = 'admin-page';
                                                            break;
                                                        case 'Appearance':
                                                            $icon_name = 'admin-appearance';
                                                            break;
                                                        case 'Plugins':
                                                            $icon_name = 'admin-plugins';
                                                            break;
                                                        case 'Comments':
                                                            $icon_name = 'admin-comments';
                                                            break;
                                                        case 'Users':
                                                            $icon_name = 'admin-users';
                                                            break;
                                                        case 'Tools':
                                                            $icon_name = 'admin-tools';
                                                            break;
                                                        case 'Settings':
                                                            $icon_name = 'admin-settings';
                                                            break;
                                                        case 'Capabilities':
                                                            $icon_name = 'admin-network';
                                                            break;
                                                        case 'PublishPress Blocks':
                                                            $icon_name = 'layout';
                                                            break;
                                                        case 'Authors':
                                                            $icon_name = 'groups';
                                                            break;
                                                        case 'Revisions':
                                                            $icon_name = 'backup';
                                                            break;
                                                            break;
                                                        case 'PublishPress':
                                                            $icon_name = 'calendar-alt';
                                                            break;
                                                        case 'Checklists':
                                                            $icon_name = 'yes-alt';
                                                            break;
                                                        case 'Notifications':
                                                            $icon_name = 'bell';
                                                            break;

                                                    }
                                                    ?>

                                                    <tr class="ppc-menu-row parent-menu">

                                                        <td class="restrict-column ppc-menu-checkbox">
                                                        <input id="check-item-<?php echo $sn;?>"<?php echo $disabled_field;?> class="check-item" type="checkbox" 
                                                            name="pp_cababilities_disabled_menu<?php echo $disabled_class;?>[]" 
                                                            value="<?php echo $item_menu_slug;?>"<?php echo (in_array($item_menu_slug, $admin_menu_option)) ? ' checked' : '';?> />
                                                        </td>
                                                        <td class="menu-column ppc-menu-item <?php echo $disabled_class;?>">

                                                            <label for="check-item-<?php echo $sn;?>">
                                                                <span class="menu-item-link<?php echo (in_array($item_menu_slug, $admin_menu_option)) ? ' restricted' : '';?>">
                                                                <strong><i class="dashicons dashicons-<?php echo $icon_name ?>"></i>
                                                                    <?php echo $menu_title; ?>
                                                                </strong></span>
                                                            </label> 

                                                            <?php echo $disabled_info;?>
                                                        </td>

                                                    </tr>

                                                    <?php
                                                    if (!isset($admin_global_submenu[$item_menu_slug])) {
                                                        continue;
                                                    }

                                                    
                                                    $last_subitem = false;
                                                    $last_sn = 0;

                                                    foreach ($admin_global_submenu[$item_menu_slug] as $subkey => $subitem) {
                                                        $sn++;
                                                        $submenu_slug = $subitem[2];

                                                        //skip item if it's double custom bacakground
                                                        //if($submenu_slug === 'custom-background'){
                                                        if (isset($subitem[4]) && ('hide-if-no-customize' == $subitem[4])) {
                                                            continue;
                                                        }

                                                        //disable pp-capabilities-admin-menus checkbox if admin is editing own role
                                                        if ( $submenu_slug === 'pp-capabilities-admin-menus' && in_array($default_role, wp_get_current_user()->roles)) {
                                                            $disabled_field = ' disabled="disabled"';
                                                            $disabled_class = ' disabled';

                                                            $disabled_info = '<div class="tooltip"><i class="dashicons dashicons-info"></i> <span class="tooltiptext">' 
                                                            . __('This option is disabled to prevent complete lockout', 'capabilities-pro') 
                                                            . '</span></div>';

                                                        } else {
                                                            $disabled_field = $disabled_class = $disabled_info = '';
                                                        }

                                                        $sub_menu_value = $item_menu_slug . $subkey;

                                                        $sub_menu_title = ppc_process_admin_menu_title($subitem[0]);
                                                        ?>
                                                        <tr class="ppc-menu-row child-menu">

                                                            <td class="restrict-column ppc-menu-checkbox">
                                                                <input id="check-item-<?php echo $sn;?>"<?php echo $disabled_field;?> class="check-item" type="checkbox" 
                                                                    name="pp_cababilities_disabled_child_menu<?php echo $disabled_class;?>[]" 
                                                                    value="<?php echo $sub_menu_value;?>" 
                                                                    <?php echo (in_array($sub_menu_value, $admin_child_menu_option)) ? 'checked' : '';?> 
                                                                    data-val="<?php echo $sub_menu_value;?>" />
                                                            </td>
                                                            <td class="menu-column ppc-menu-item'<?php echo $disabled_class;?>">

                                                                <label for="check-item-<?php echo $sn;?>">
                                                                    <span class="menu-item-link<?php echo (in_array($sub_menu_value, $admin_child_menu_option)) ? ' restricted' : '';?>">
                                                                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &mdash;
                                                                    <?php echo $sub_menu_title;?>
                                                                    </strong></span>
                                                                </label>

                                                                <?php echo $disabled_info;?>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                        if ($last_subitem && ($subitem[0] == $last_subitem[0])) {
                                                            // If there is ambiguity due to two consective submenu items with the same caption, hide both                                           
                                                            ?>
                                                            <script type="text/javascript">
                                                            /* <![CDATA[ */
                                                            var elem = document.getElementById('check-item-<?php echo $last_sn;?>');
                                                            var parent = elem.closest('tr');
                                                            parent.style.display = 'none';
                                                            
                                                            elem = document.getElementById('check-item-<?php echo $sn;?>');
                                                            parent = elem.closest('tr');
                                                            parent.style.display = 'none';
                                                            /* ]]> */
                                                            </script>
                                                            <?php
                                                        }

                                                        $last_subitem = $subitem;
                                                        $last_sn = $sn;

                                                    }  // end foreach admin_global_submenu

                                                    $sn++;

                                                } // end foreach admin_global_menu

                                            } else {
                                                ?>
                                                <tr><td style="color: red;"> <?php _e('No menu found', 'capabilities-pro');?></td></tr>
                                                <?php
                                            } 

                                            ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <input type="submit" name="admin-menu-submit"
                               value="<?php _e('Save Changes', 'capabilities-pro') ?>"
                               class="button-primary ppc-admin-menu-submit"/>
                    </td>
                </tr>
            </table>

        </fieldset>

    </form>

    <script type="text/javascript">
        /* <![CDATA[ */
        jQuery(document).ready(function ($) {

            // -------------------------------------------------------------
            //   reload page for instant reflection if user is updating own role
            // -------------------------------------------------------------
            <?php if((int)$ppc_admin_menu_reload === 1){ ?>
                window.location = '<?php echo admin_url('admin.php?page=pp-capabilities-admin-menus&role=' . $default_role . ''); ?>';
            <?php } ?>

            // -------------------------------------------------------------
            //   Set form action attribute to include role
            // -------------------------------------------------------------
            $('#ppc-admin-menu-form').attr('action', '<?php echo admin_url('admin.php?page=pp-capabilities-admin-menus&role=' . $default_role . ''); ?>');

            // -------------------------------------------------------------
            //   Instant restricted item class
            // -------------------------------------------------------------
            $(document).on('change', '.pp-capability-menus-wrapper .ppc-menu-row .check-item', function () {

                if ($(this).is(':checked')) {
                    //add class if value is checked
                    $(this).parent().parent().find('.menu-item-link').addClass('restricted');

                    //toggle all checkbox
                    if ($(this).hasClass('check-all-menu-item')) {
                        $("input[type='checkbox'][name='pp_cababilities_disabled_menu[]']").prop('checked', true);
                        $("input[type='checkbox'][name='pp_cababilities_disabled_child_menu[]']").prop('checked', true);
                        $('.menu-item-link').addClass('restricted');
                    } else {
                        $('.check-all-menu-link').removeClass('restricted');
                        $('.check-all-menu-item').prop('checked', false);
                    }

                } else {
                    //unchecked value
                    $(this).parent().parent().find('.menu-item-link').removeClass('restricted');

                    //toggle all checkbox
                    if ($(this).hasClass('check-all-menu-item')) {
                        $("input[type='checkbox'][name='pp_cababilities_disabled_menu[]']").prop('checked', false);
                        $("input[type='checkbox'][name='pp_cababilities_disabled_child_menu[]']").prop('checked', false);
                        $('.menu-item-link').removeClass('restricted');
                    } else {
                        $('.check-all-menu-link').removeClass('restricted');
                        $('.check-all-menu-item').prop('checked', false);
                    }

                }

            });

            // -------------------------------------------------------------
            //   Load selected roles menu
            // -------------------------------------------------------------
            $(document).on('change', '.pp-capability-menus-wrapper .ppc-admin-menu-role', function () {

                //disable select
                $('.pp-capability-menus-wrapper .ppc-admin-menu-role').attr('disabled', true);

                //hide button
                $('.pp-capability-menus-wrapper .ppc-admin-menu-submit').hide();

                //show loading
                $('#pp-capability-menu-wrapper').hide();
                $('div.publishpress-caps-manage img.loading').show();

                //go to url
                window.location = '<?php echo admin_url('admin.php?page=pp-capabilities-admin-menus&role='); ?>' + $(this).val() + '';

            });
        });
        /* ]]> */
    </script>

    <?php if (!defined('PUBLISHPRESS_CAPS_PRO_VERSION') || get_option('cme_display_branding')) {
        cme_publishpressFooter();
    }
    ?>
</div>
<?php
