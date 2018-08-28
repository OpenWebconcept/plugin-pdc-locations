<?php
/**
 * Model for the item
 */

namespace OWC\PDC\Locations\Models;

use OWC\PDC\Base\Models\Model;
use \WP_Post;

/**
 * Model for the item
 */
class Location extends Model
{

    /**
     * Type of model.
     *
     * @var string $posttype
     */
    protected $posttype = 'pdc-location';

    /**
     * Container with fields, associated with this model.
     *
     * @var array $globalFields
     */
    protected static $globalFields = [];

	/**
     * Transform a single WP_Post item.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function transform(WP_Post $post)
    {
        $data = [
            'id'      => $post->ID,
            'title'   => $post->post_title,
			'date'    => $post->post_date,
			'address' => get_post_meta( $post->ID, '_owc_pdc-location-address', true )
        ];

        $data = $this->assignFields($data, $post);

        return $data;
	}
}
