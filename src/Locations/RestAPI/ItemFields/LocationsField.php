<?php

/**
 * Adds connected/related fields to the output.
 */

namespace OWC\PDC\Locations\RestAPI\ItemFields;

use OWC\PDC\Base\Support\CreatesFields;
use OWC\PDC\Locations\Models\Location;
use WP_Post;

/**
 * Adds connected/related fields to the output.
 */
class LocationsField extends CreatesFields
{

    /**
     * Creates an array of connected posts.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        $connections = array_filter($this->plugin->config->get('p2p_connections.connections'), function ($connection) {
            return in_array('pdc-item', $connection, true);
        });

        $result = [];

        foreach ($connections ?? [] as $connection) {
            $type   = $connection['from'] . '_to_' . $connection['to'];
            $result = $this->getConnectedItems($post->ID, $type);
        }

        return $result;
    }

    /**
     * Get connected items of a post, for a specific connection type.
     *
     * @param int    $postID
     * @param string $type
     *
     * @return array
     */
    protected function getConnectedItems(int $postID, string $type): array
    {
        if (!\function_exists('p2p_type')) {
            return [];
        }

        $connection = \p2p_type($type);

        if (!$connection) {
            return [
                'error' => sprintf(__('Connection type "%s" does not exist', 'pdc-base'), $type),
            ];
        }

        return array_map(function (WP_Post $post) {
            return (new Location($this->plugin))->transform($post);
        }, $connection->get_connected($postID)->posts);
    }
}
