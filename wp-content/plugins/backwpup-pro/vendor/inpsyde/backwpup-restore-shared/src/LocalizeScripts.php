<?php
/**
 * Localize Scripts
 */

namespace Inpsyde\Restore;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LocalizeScripts
 *
 * @package Inpsyde\Restore
 */
class LocalizeScripts
{

    /**
     * Translator
     *
     * @var TranslatorInterface The translator instance
     */
    private $translator;

    /**
     * Strings List
     *
     * @var array The list of the strings to localize
     */
    private $list;

    /**
     * LocalizeScripts constructor
     *
     * @param TranslatorInterface $translator The translator instance.
     * @param array $list The list of the strings to localize.
     */
    public function __construct(TranslatorInterface $translator, array $list)
    {
        $this->translator = $translator;
        $this->list = $list;
    }

    /**
     * Localize
     *
     * @return LocalizeScripts For concatenation
     */
    public function localize()
    {
        foreach ($this->list as &$item) {
            $item = $this->translator->trans($item);
        }

        return $this;
    }

    /**
     * Output Localized strings
     *
     * @return LocalizeScripts For concatenation
     */
    public function output()
    {
        ?>
        <script type="text/javascript">
          /* <![CDATA[ */
          <?php echo 'var backwpupRestoreLocalized = ' . wp_json_encode($this->list) . "\n"; ?>
          /* ]]> */
        </script>
        <?php

        return $this;
    }
}
