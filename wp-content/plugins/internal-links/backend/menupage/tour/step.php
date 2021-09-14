<?php
namespace ILJ\Backend\MenuPage\Tour;

/**
 * Abstract tour step
 *
 * Abstract class for all steps within the onboarding tour
 *
 * @package ILJ\Backend\Tour
 * @since   1.1.0
 */
abstract class Step
{

    /**
     * @var   int
     * @since 1.1.0
     */
    protected $feature_row_counter = 1;

    /**
     * Renders the content frame of the step
     *
     * @since  1.1.0
     * @return type
     */
    public function renderContent()
    {
    }

    /**
     * Block for a feature row
     *
     * @since  1.1.0
     * @param  array $data
     * @return void
     */
    protected function renderFeatureRow($data)
    {
        echo '<div class="ilj-row substep">';
        echo '<div class="counter">' . $this->feature_row_counter . '</div>';
        echo '<div class="content"><h2>' . $data['title'] . '</h2><p>' . $data['description'] . '</p></div>';
        echo '<div class="video"><iframe width="100%" height="250" src="https://www.youtube-nocookie.com/embed/' . $data['video'] . '?rel=0&color=white&showinfo=1&cc_load_policy=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
        echo '<div class="clear"></div></div>';

        $this->feature_row_counter++;
    }
}
