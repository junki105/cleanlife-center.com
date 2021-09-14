<?php

namespace ILJ\Helper;

/**
 * Capabilities toolset
 *
 * Methods for handling capabilities settings
 *
 * @package ILJ\Helper
 * @since   1.0.0
 */
class Capabilities
{
    /**
     * Generates a select input for predefined user roles
     *
     * @since  1.0.0
     * @param  string|bool $selected The role that gets preselected
     * @return void
     */
    public static function rolesDropdown($selected = false)
    {
        $roles_preselected = array(
            'administrator' => translate_user_role('Administrator'),
            'editor'        => translate_user_role('Editor'),
            'author'        => translate_user_role('Author'),
            'contributor'   => translate_user_role('Contributor'),
        );
        foreach ($roles_preselected as $role => $name) {
            echo '<option value="' . $role . '"' . ($role == $selected ? ' selected="selected"' : '') . '>' . $name . '</option>';
        }
    }

    public static function getValidRoles()
    {
        return [
        'administrator',
        'editor',
        'author',
        'contributor'
        ];
    }

    /**
     * Translates a given user role to its accredited capability
     *
     * @since  1.0.0
     * @param  string $role The role to translate
     * @return string
     */
    public static function mapRoleToCapability($role)
    {
        switch ($role) {
        case 'administrator':
            return 'manage_options';
                break;
        case 'editor':
            return 'publish_pages';
                break;
        case 'author':
            return 'publish_posts';
                break;
        case 'contributor':
            return 'edit_posts';
                break;
        default:
            return $role;
                break;
        }
    }
}
