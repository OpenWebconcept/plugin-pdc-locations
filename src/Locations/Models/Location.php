<?php

declare(strict_types=1);

/**
 * Model for the item
 */

namespace OWC\PDC\Locations\Models;

use \WP_Post;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Base\Repositories\AbstractRepository;
use OWC\PDC\Locations\Entities\CustomOpeninghours;
use OWC\PDC\Locations\Entities\Day;
use OWC\PDC\Locations\Entities\Openinghours;
use OWC\PDC\Locations\Entities\SpecialOpeningHours;
use OWC\PDC\Locations\Entities\Timeslot;
use OWC\PDC\Locations\Entities\Week;

/**
 * Model for the item
 */
class Location extends AbstractRepository
{
    /**
     * Type of model.
     *
     * @var string
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
     * @var array
     */
    protected static $globalFields = [];

    /**
     * Instance of the plugin
     *
     * @var Plugin
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
            'id'    => $post->ID,
            'title' => $post->post_title,
            'date'  => $post->post_date,
        ];

        $data                             = $this->assignFields(array_merge($data, $fields), $post);
        $data                             = $this->hydrateCustomOpeninghours($data);
        $data                             = $this->hydrate($data);
        $data['location']['image']        = $this->getFeaturedImage($post);
        $data['openinghours']['openNow']  = (new Openinghours($data['openinghours']['days']))->isOpenNow();
        $data['openinghours']['messages'] = (new Openinghours($data['openinghours']['days']))->getMessages();

        $data['special_openingdays'] = (new SpecialOpeningHours($data['special_openingdays']))->make();

        $week = new Week();
        foreach ($data['openinghours']['days'] as $name => $timeslot) {
            $day                                 = new Day($name);
            $day->addTimeslot(Timeslot::make($timeslot));
            $day                                 = (new SpecialOpeningHours($data['special_openingdays']))->asPossibleSpecial($day);

            if ($day->isSpecial()) {
                $data['openinghours']['days'][$name] = $day->toRest()[0];
            }
            
            $week->addDay($name, $day);
        }

        $data['openinghours']['openNow']  = (new CustomOpeninghours($week))->isOpenNow();
        $data['openinghours']['messages'] = (new CustomOpeninghours($week))->getMessages(false); // Don't show special message when normal openinghours.

        $week = new Week();
        foreach ($data['custom-openinghours']['custom-days'] as $name => $timeslots) {
            $day = new Day($name);
            foreach ($timeslots as $timeslot) {
                $day->addTimeslot(Timeslot::make($timeslot));
            }

            $day = (new SpecialOpeningHours($data['special_openingdays']))->asPossibleSpecial($day);

            $week->addDay($name, $day);
            $data['custom-openinghours']['custom-days'][$name] = $day->toRest();
        }
        
        $data['custom-openinghours']['openNow']  = (new CustomOpeninghours($week))->isOpenNow();
        $data['custom-openinghours']['messages'] = (new CustomOpeninghours($week))->getMessages();

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
        if (! has_post_thumbnail($post->ID)) {
            return [];
        }

        $id         = (int) get_post_thumbnail_id($post->ID);
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
     * @param int $id
     *
     * @return array
     */
    private function getAttachmentMeta(int $id): array
    {
        $meta = wp_get_attachment_metadata($id, false);

        if (false === $meta) {
            return [];
        }

        if (empty($meta['sizes'])) {
            return [];
        }

        foreach (array_keys($meta['sizes']) as $size) {
            $src                         = wp_get_attachment_image_src($id, $size);
            $meta['sizes'][$size]['url'] = (false === $src) ? '' : $src[0];
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
            'id'            => '',
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
                'message-active' => false,
                'message'        => '',
                'days'           => [
                    'monday'    => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'tuesday'   => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'wednesday' => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'thursday'  => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'friday'    => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'saturday'  => [
                        'closed'      => false,
                        'message'     => '',
                        'open-time'   => '',
                        'closed-time' => '',
                    ],
                    'sunday'    => [
                        'closed'      => false,
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
     * @return array
     */
    protected function hydrate(array $data): array
    {
        return array_replace_recursive($this->getDefaults(), $data);
    }

    /**
     * Fill all the fields this their defaults.
     *
     * @param array $data
     *
     * @return array
     */
    protected function hydrateCustomOpeninghours(array $data): array
    {
        $default =   [
            'closed'      => false,
            'message'     => '',
            'open-time'   => '',
            'closed-time' => '',
        ];

        $daysDefault = [
            'monday'    => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'tuesday'   => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'wednesday' => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'thursday'  => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'friday'    => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'saturday'  => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
            'sunday'    => [
                [
                    'closed'      => false,
                    'message'     => '',
                    'open-time'   => '',
                    'closed-time' => '',
                ],
            ],
        ];

        if (! isset($data['custom-openinghours']['custom-days'])) {
            foreach ($daysDefault as $day => $fields) {
                if (isset($data['custom-openinghours']['custom-days'][$day])) {
                    continue;
                }
                $data['custom-openinghours']['custom-days'][$day] = $fields;
            }

            return $data;
        }

        foreach ($data['custom-openinghours']['custom-days'] as $key => $day) {
            foreach ($day as $fieldKey => $field) {
                $field['closed']                                             = isset($field['closed']) ? (bool) $field['closed'] : false;
                $data['custom-openinghours']['custom-days'][$key][$fieldKey] = array_replace($default, $field);
            }
        }

        foreach ($daysDefault as $day => $fields) {
            if (isset($data['custom-openinghours']['custom-days'][$day])) {
                continue;
            }
            $data['custom-openinghours']['custom-days'][$day] = $fields;
        }

        return $data;
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
        return array_map(function ($field) {
            return $this->manipulate($field);
        }, $this->removeUnnecessaryFieldsByKey($fields));
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
            return (! in_array($key, $toRemove));
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
        if (1 > count($fields)) {
            return $fields;
        }

        foreach ($fields as $key => $field) {
            if (! array_key_exists('_owc_' . $field['id'], $this->allPostMeta) && (! in_array('_owc_' . $field['id'], $this->allPostMeta))) {
                unset($fields[$key]);

                continue;
            }

            $fieldName          = str_replace($this->posttype . '-', '', $field['id']);
            $content            = $this->allPostMeta['_owc_' . $field['id']][0] ?? '';
            if (in_array($content, ['0', '1', "0", "1"])) {
                $content = filter_var($content, FILTER_VALIDATE_BOOLEAN);
            }
            $fields[$fieldName] = maybe_unserialize($content);
            unset($fields[$key]);
        }

        return $fields;
    }
}
