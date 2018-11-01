<?php
/**
 * Model for the item
 */

namespace OWC\PDC\Locations\Models;

use OWC\PDC\Base\Models\Model;
use OWC\PDC\Locations\Entities\Openinghours;
use OWC\PDC\Locations\Foundation\Plugin;
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
     * All meta data of post
     *
     * @var array
     */
    protected $allPostMeta = [];

    /**
     * Container with fields, associated with this model.
     *
     * @var array $globalFields
     */
    protected static $globalFields = [];

    /**
     * Instance of the plugin
     *
     * @var Plugin $plugin
     */
    protected $plugin;

    /**
     * Constructor of Location Model.
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Transform a single WP_Post item.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function transform(WP_Post $post)
    {
        $this->allPostMeta = $this->getAllPostMeta($post);
        $fields            = $this->getFields($this->plugin->config->get('metaboxes.locations.fields'));
        $data              = [
            'title' => $post->post_title,
            'date'  => $post->post_date,

        ];

        $data                             = $this->assignFields(array_merge($data, $fields), $post);
        $data                             = $this->hydrate($data);
        $data['location']['image']        = $this->getFeaturedImage($post);
        $data['openinghours']['messages'] = (new Openinghours($data['openinghours']['days']))->render();

        return $data;
    }

    /**
     * Gets the featured image of a post.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function getFeaturedImage(WP_Post $post): array
    {
        if (!has_post_thumbnail($post->ID)) {
            return [];
        }

        $id         = get_post_thumbnail_id($post->ID);
        $attachment = get_post($id);
        $imageSize  = 'large';

        $result = [];

        $result['title']       = $attachment->post_title;
        $result['description'] = $attachment->post_content;
        $result['caption']     = $attachment->post_excerpt;
        $result['alt']         = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

        $meta = $this->getAttachmentMeta($id);

        $result['rendered'] = wp_get_attachment_image($id, $imageSize);
        $result['sizes']    = wp_get_attachment_image_sizes($id, $imageSize, $meta);
        $result['srcset']   = wp_get_attachment_image_srcset($id, $imageSize, $meta);
        $result['meta']     = $meta;

        return $result;
    }

    /**
     * Get meta data of an attachment.
     *
     * @param $id
     *
     * @return array
     */
    private function getAttachmentMeta($id): array
    {
        $meta = wp_get_attachment_metadata($id, false);

        if (empty($meta['sizes'])) {
            return [];
        }

        foreach (array_keys($meta['sizes']) as $size) {
            $src                         = wp_get_attachment_image_src($id, $size);
            $meta['sizes'][$size]['url'] = $src[0];
        }

        unset($meta['image_meta']);

        return $meta;
    }

    /**
     * Return the defaults.
     *
     * @TODO should be generated from metabox config, to avoid duplicate code.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'title'         => '',
            'date'          => '',
            'general'       => [
                'description' => '',
            ],
            'location'      => [
                'street'     => '',
                'zipcode'    => '',
                'city'       => '',
                'postalcode' => '',
                'postalcity' => '',
                'maplink'    => '',
                'image'      => '',
            ],
            'communication' => [
                'telephone-description' => '',
                'telephone'             => '',
                'whatsapp'              => '',
                'fax'                   => '',
                'email'                 => '',
            ],
            'openinghours'  => [
                'message-active' => '0',
                'message'        => '',
                'days'           => [
                    'monday'    => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'tuesday'   => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'wednesday' => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'thursday'  => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'friday'    => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'saturday'  => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'sunday'    => [
                        'closed'      => '0',
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                ],

                'messages'       => [
                    'today'    => '',
                    'tomorrow' => '',
                ],
            ],
        ];
    }

    /**
     * Fill all the fields this their defaults.
     *
     * @param array $data
     *
     * @return arry
     */
    protected function hydrate($data)
    {
        return array_replace_recursive($this->getDefaults(), $data);
    }

    /**
     * Get all meta assigned to post
     *
     * @param WP_Post $post
     *
     * @return array
     */
    protected function getAllPostMeta($post)
    {
        return get_metadata('post', $post->ID);
    }

    /**
     * Filter fields from config.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function getFields($fields): array
    {
        $fields = $this->removeUnnecessaryFieldsByKey($fields);
        $fields = array_map(function ($field) use ($fields) {
            return $this->manipulate($field);
        }, $fields);

        return $fields;
    }

    /**
     * Remove the unnecessary fields, such as dividers.
     * And rename the keys to readable keys.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function removeUnnecessaryFieldsByKey($fields)
    {
        $toRemove = ['divider'];
        $fields   = array_filter($fields, function ($key) use ($toRemove) {
            return (!in_array($key, $toRemove));
        }, ARRAY_FILTER_USE_KEY);

        return $fields;
    }

    /**
     * Manipulate the fields array.
     *
     * @param array $fields
     *
     * @return array
     */
    protected function manipulate($fields)
    {
        if (count($fields) < 1) {
            return $fields;
        }

        foreach ($fields as $key => $field) {
            if (!array_key_exists('_owc_' . $field['id'], $this->allPostMeta) and (!in_array('_owc_' . $field['id'], $this->allPostMeta))) {
                unset($fields[$key]);
                continue;
            }

            $fieldName          = str_replace($this->posttype . '-', '', $field['id']);
            $content            = $this->allPostMeta['_owc_' . $field['id']][0] ?? '';
            $fields[$fieldName] = maybe_unserialize($content);
            unset($fields[$key]);
        }

        return $fields;
    }
}
