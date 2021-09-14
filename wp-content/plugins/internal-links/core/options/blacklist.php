<?php
namespace ILJ\Core\Options;

use ILJ\Helper\Help;

/**
 * Option: Blacklist
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class Blacklist extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'blacklist';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Blacklist of posts that should not be used for linking', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('Posts that get configured here do not link to others automatically.', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        if ($value == "") {
            $value = [];
        }
        echo '<select name="' . self::getKey() . '[]" id="' . self::getKey() . '" multiple="multiple">';
        foreach ($value as $val) {
            echo '<option value="' . $val . '" selected="selected">' . get_the_title($val) . '</option>';
        }
        echo '</select> ' . Help::getOptionsLink('whitelist-blacklist/', 'blacklist', 'blacklist');
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        if (!is_array($value)) {
            return false;
        }

        foreach($value as $val) {
            if (!is_numeric($val)) {
                return false;
            }
        }

        return true;
    }
}
