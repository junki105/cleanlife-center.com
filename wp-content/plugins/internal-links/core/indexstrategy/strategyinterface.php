<?php

namespace ILJ\Core\IndexStrategy;

/**
 * IndexBuilder Strategy Interface
 *
 * Defines the interface for different indexbuilder strategies
 *
 * @package ILJ\Core\Indexbuilder;
 *
 * @since 1.2.0
 */
interface StrategyInterface
{

    /**
     * Responsible for building the index and writing possible internal links to it
     *
     * @since 1.0.1
     *
     * @return int The count of built index entries
     */
    public function setIndices();

    /**
     * Sets the link options
     *
     * @since 1.2.0
     * @param array $link_options The options
     *
     * @return void
     */
    public function setLinkOptions(array $link_options);
}