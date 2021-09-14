<?php
/**
 * Interface Storage
 */

namespace Inpsyde\Restore;

/**
 * StorageInterface
 *
 * @package Inpsyde\Restore
 */
interface StorageInterface
{

    /**
     * Set
     *
     * @param string $key The key to reference this item.
     * @param mixed $value The value to store.
     *
     * @return void
     */
    public function set($key, $value);

    /**
     * Get
     *
     * @param string $key The key used to retrieve the item.
     *
     * @return mixed|null The item value or null if not exists
     */
    public function get($key);

    /**
     * Delete
     *
     * @param string $key The key used to delete the item.
     *
     * @return void
     */
    public function delete($key);
}
