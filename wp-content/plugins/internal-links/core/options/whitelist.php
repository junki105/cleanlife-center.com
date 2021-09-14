<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;

/**
 * Option: Whitelist
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class Whitelist extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'whitelist';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return ['page', 'post'];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Whitelist of post types, that should be used for linking', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('All posts within the allowed post types can link to other posts automatically.', 'internal-links');
    }

    /**
     * Gets all post types that can be used with the plugin
     *
     * @since  1.2.0
     * @return array
     */
    public static function getEditorPostTypes()
    {
        $editor_post_types = [];

        $post_types_public = get_post_types(
            [
            'public'   => true,
            '_builtin' => false
            ], 'objects', 'or'
        );

        $post_types_with_editor = get_post_types_by_support(
            ['editor']
        );

        if (!count($post_types_public)) {
            return $editor_post_types;
        }

        foreach ($post_types_public as $post_type) {
            if (in_array($post_type->name, $post_types_with_editor) ) {
                $editor_post_types[] = $post_type;
            }
        }

        return $editor_post_types;
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        if ($value == "") {
            $value = [];
        }

        $editor_post_types = self::getEditorPosttypes();

        if (count($editor_post_types)) {
            echo '<select name="' . self::getKey() . '[]" id="' . self::getKey() . '" multiple="multiple">';
            foreach ($editor_post_types as $post_type) {
                echo '<option value="' . $post_type->name . '" ' . (in_array($post_type->name, $value) ? ' selected' : '') . '>' . $post_type->label . '</option>';
            }
            echo '</select> ' . Help::getOptionsLink('whitelist-blacklist/', 'whitelist', 'whitelist');
        }
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        if (!is_array($value)) {
            return false;
        }

        $editor_post_types = self::getEditorPostTypes();
        $editor_post_types_names = array_map(
            function ($post_type) {
                return $post_type->name;
            }, $editor_post_types
        );

        foreach($value as $val) {
            if (!in_array($val, $editor_post_types_names)) {
                return false;
            }
        }

        return true;
    }
}
