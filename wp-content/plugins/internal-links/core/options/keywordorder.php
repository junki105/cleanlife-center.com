<?php
namespace ILJ\Core\Options;

use ILJ\Enumeration\KeywordOrder as KeywordOrderEnum;

/**
 * Option: Order of keywords
 *
 * @since   1.1.3
 * @package ILJ\Core\Options
 */
class KeywordOrder extends AbstractOption
{
    /**
     * @inheritdoc
     */
    public static function getKey()
    {
        return self::ILJ_OPTIONS_PREFIX . 'keyword_order';
    }

    /**
     * @inheritdoc
     */
    public static function getDefault()
    {
        return KeywordOrderEnum::FIFO;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return __('Order for configured keywords while linking', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return __('Set the order of how your set keywords get used for building links.', 'internal-links');
    }

    /**
     * @inheritdoc
     */
    public function renderField($value)
    {
        echo '<select name="' . self::getKey() . '" id="' . self::getKey() . '">';
        $order_types = KeywordOrderEnum::getValues();

        foreach ($order_types as $order_type) {
            echo '<option value="' . $order_type . '"' . ($order_type == $value ? ' selected' : '') . '>' . KeywordOrderEnum::translate($order_type) . '</option>';
        }
        echo '</select> ';
    }

    /**
     * @inheritdoc
     */
    public function isValidValue($value)
    {
        return in_array($value, KeywordOrderEnum::getValues());
    }
}
