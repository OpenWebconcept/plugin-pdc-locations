<?php

declare(strict_types=1);

namespace OWC\PDC\Locations\Models;

use DateTime;
use DateTimeZone;
use Exception;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Base\Repositories\AbstractRepository;
use OWC\PDC\Base\Support\Traits\CheckPluginActive;
use OWC\PDC\Locations\Traits\Attachment;
use Spatie\OpeningHours\OpeningHours as SpatieOpeningHours;
use WP_Post;

class Location extends AbstractRepository
{
    use Attachment;
    use CheckPluginActive;

    protected $posttype = 'pdc-location';
    protected array $allPostMeta = [];
    protected static $globalFields = [];
    protected array $days = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected DateTimeZone $timezone;
    protected DateTime $now;

    protected Plugin $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->timezone = new DateTimeZone(wp_timezone_string());
        $this->now = new DateTime('now', $this->timezone);
    }

    /**
     * Transform a single WP_Post item.
     */
    public function transform(WP_Post $post): array
    {
        $this->allPostMeta = $this->getAllPostMeta($post);
        $fields = $this->getFields($this->plugin->config->get('cmb2_metaboxes.locations.fields'));

        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'date' => $post->post_date,
        ];

        $data = $this->assignFields(array_merge($data, $fields), $post);
        $data['location']['image'] = $this->getFeaturedImage($post);

        $openinghours = SpatieOpeningHours::create($this->prepareOpeningHours($post));

        $data = $this->formatOpeninghours($data, $openinghours);
        $data = $this->getMessages($data, $openinghours);
        $data = $this->populateOpenNow($data, $openinghours);
        $data = $this->formatSpecialDays($data, $this->prepareSpecialDays($post));

        return $data;
    }

    /**
     * Get all meta assigned to post
     */
    protected function getAllPostMeta(WP_Post $post): array
    {
        $meta = get_metadata('post', $post->ID);

        return is_array($meta) ? $meta : [];
    }

    protected function prepareOpeningHours(WP_Post $post): array
    {
        $openinghours = [];

        foreach ($this->days as $day) {
            $dayMetaKey = sprintf('_owc_pdc-location-openinghours-%s', $day);
            $timeslots = get_post_meta($post->ID, $dayMetaKey, true) ?: [];
            $openinghours[$day] = [];

            foreach ($timeslots ?? [] as $timeslot) {
                $open = $this->validateTime($timeslot[sprintf('pdc-location-openinghours-timeslot-%s-open-time', $day)] ?? '');
                $closed = $this->validateTime($timeslot[sprintf('pdc-location-openinghours-timeslot-%s-closed-time', $day)] ?? '');
                $message = $timeslot[sprintf('pdc-location-openinghours-timeslot-%s-message', $day)] ?? '';

                if (empty($open) || empty($closed)) {
                    continue;
                }

                $openinghours[$day][] = ['hours' => sprintf('%s-%s', $open, $closed), 'data' => $message];
            }
        }

        return $openinghours;
    }

    protected function formatOpeninghours(array $data, SpatieOpeningHours $openinghours): array
    {
        foreach ($this->days as $day) {
            $data['openinghours']['days'][$day] =
                $openinghours->forDay($day)->map(function ($time) {
                    return [
                        'open-time' => $time->start()->format('H.i'),
                        'closed-time' => $time->end()->format('H.i'),
                        'message' => $time->getData(),
                    ];
                });
        }

        return $this->hydrate($data);
    }

    /**
     * Fill all the fields this their defaults.
     */
    protected function hydrate(array $data): array
    {
        return array_replace_recursive($this->getDefaults(), $data);
    }

    protected function getMessages(array $data, SpatieOpeningHours $openinghours): array
    {
        $data['openinghours']['messages']['open'] = [
            'today' => $this->getTodayMessage($openinghours),
            'tomorrow' => $this->getTomorrowMessage($openinghours),
        ];

        return $data;
    }

    protected function getTodayMessage(SpatieOpeningHours $openinghours): string
    {
        $range = $openinghours->currentOpenRange($this->now);
        $todayMsg = $range ? sprintf(__('Nu geopend van %s tot %s', 'pdc-locations'), $range->start(), $range->end()) : 'Nu gesloten';

        if (! $range) {
            $todayMsg = $this->getNextOpenCloseWhenNowClosed($openinghours, $todayMsg);
        } else {
            $todayMsg = $this->getNextOpenCloseWhenOpenNow($openinghours, $todayMsg);
        }

        return $todayMsg;
    }

    /**
     * When not open now, overwrite today message with next open and close message.
     */
    protected function getNextOpenCloseWhenNowClosed(SpatieOpeningHours $openinghours, string $todayMsg): string
    {
        $searchFrom = new DateTime($this->now->format('Y-m-d H:i'), $this->timezone);
        $searchTill = (new DateTime($this->now->format('Y-m-d'), $this->timezone))->setTime(23, 59);

        try {
            $nextOpenSearched = $openinghours->nextOpen($searchFrom, $searchTill)->format('H.i');
            $nextClosedSearched = $openinghours->nextClose($searchFrom, $searchTill)->format('H.i');
            $todayMsg .= sprintf(__(', straks geopend van %s tot %s', 'pdc-locations'), $nextOpenSearched, $nextClosedSearched);
        } catch(Exception $e) {
            $todayMsg = 'Nu gesloten';
        }

        return $todayMsg;
    }

    /**
     * When open now, append next open and close message.
     */
    protected function getNextOpenCloseWhenOpenNow(SpatieOpeningHours $openinghours, string $todayMsg): string
    {
        $searchFrom = new DateTime($openinghours->currentOpenRangeEnd($this->now)->format('Y-m-d H:i'), $this->timezone);
        $searchTill = (new DateTime($this->now->format('Y-m-d'), $this->timezone))->setTime(23, 59);

        try {
            $nextOpenSearched = $openinghours->nextOpen($searchFrom, $searchTill)->format('H.i');
            $nextClosedSearched = $openinghours->nextClose($searchFrom, $searchTill)->format('H.i');
            $todayMsg .= sprintf(__(', straks geopend van %s tot %s', 'pdc-locations'), $nextOpenSearched, $nextClosedSearched);
        } catch(Exception $e) {
            return $todayMsg;
        }

        return $todayMsg;
    }

    protected function getTomorrowMessage(SpatieOpeningHours $openinghours): string
    {
        $tomorrow = (new DateTime($this->now->format('Y-m-d'), $this->timezone))->modify('+ 1 day');
        $searchTill = (new DateTime($this->now->format('Y-m-d'), $this->timezone))->modify('+ 6 day')->setTime(23, 59); // Not include this day in next week.

        try {
            $nextOpen = $openinghours->nextOpen($tomorrow, $searchTill);
        } catch(Exception $e) {
            return '';
        }

        return sprintf(__('%s geopend vanaf %s tot %s', 'pdc-locations'), ucfirst(date_i18n('l', $nextOpen->getTimestamp())), $nextOpen->format('H.i'), $openinghours->nextClose()->format('H.i'));
    }

    protected function populateOpenNow(array $data, SpatieOpeningHours $openinghours): array
    {
        $data['openinghours']['openNow'] = $openinghours->isOpenAt($this->now);

        return $data;
    }

    protected function formatSpecialDays(array $data, array $specialDays): array
    {
        $data['special_openingdays'] = array_map(function ($specialDay) {
            $specialDay['open-time'] = $this->validateTime($specialDay['open-time']) ? (new DateTime($specialDay['open-time']))->format('H.i') : '';
            $specialDay['closed-time'] = $this->validateTime($specialDay['closed-time']) ? (new DateTime($specialDay['closed-time']))->format('H.i') : '';

            return $specialDay;
        }, $specialDays);

        return $data;
    }

    protected function prepareSpecialDays(WP_Post $post): array
    {
        $specials = get_post_meta($post->ID, '_owc_pdc-location-openinghours-exception-day', true);

        return array_map(function ($specialDay) {
            return [
                'date' => $specialDay['pdc-location-openinghours-timeslot-exception-date'] ?? '',
                'message' => $specialDay['pdc-location-openinghours-timeslot-exception-message'] ?? '',
                'open-time' => $specialDay['pdc-location-openinghours-timeslot-exception-open-time'] ?? '',
                'closed-time' => $specialDay['pdc-location-openinghours-timeslot-exception-closed-time'] ?? '',
            ];
        }, $specials ?: []);
    }

    protected function validateTime(string $time): string
    {
        if (empty($time)) {
            return '';
        }

        return strtotime($time) ? $time : '';
    }

    /**
     * Return the defaults.
     *
     * @TODO should be generated from metabox config, to avoid duplicate code.
     */
    protected function getDefaults(): array
    {
        return [
            'id' => '',
            'title' => '',
            'date' => '',
            'general' => [
                'description' => '',
            ],
            'location' => [
                'street' => '',
                'zipcode' => '',
                'city' => '',
                'postalcode' => '',
                'postalcity' => '',
                'maplink' => '',
                'image' => '',
            ],
            'communication' => [
                'telephone-description' => '',
                'telephone' => '',
                'whatsapp' => '',
                'fax' => '',
                'email' => '',
            ],
            'openinghours' => [
                'days' => [
                    'monday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'tuesday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'wednesday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'thursday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'friday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'saturday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                    'sunday' => [
                        [
                            'open-time' => null,
                            'closed-time' => null,
                            'message' => '',
                        ],
                    ],
                ],
                'messages' => [
                    'open' => [
                        'today' => '',
                        'tomorrow' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * Filter fields from config.
     */
    protected function getFields(array $fields): array
    {
        return array_map(function ($field) {
            return $this->manipulate($field);
        }, $this->removeUnnecessaryFieldsByKey($fields));
    }

    /**
     * Remove the unnecessary fields, such as dividers.
     * And rename the keys to readable keys.
     */
    protected function removeUnnecessaryFieldsByKey(array $fields)
    {
        $toRemove = ['divider'];
        $fields = array_filter($fields, function ($key) use ($toRemove) {
            return (! in_array($key, $toRemove));
        }, ARRAY_FILTER_USE_KEY);

        return $fields;
    }

    /**
     * Manipulate the fields array.
     */
    protected function manipulate(array $fields): array
    {
        if (1 > count($fields)) {
            return $fields;
        }

        foreach ($fields as $key => $field) {
            $metaKey = sprintf('_owc_%s', $field['id']);

            // Does the key exists in the meta data?
            if (! array_key_exists($metaKey, $this->allPostMeta) && (! in_array($metaKey, $this->allPostMeta))) {
                unset($fields[$key]);

                continue;
            }

            $metaValue = $this->allPostMeta[$metaKey][0] ?? '';

            // Convert specific values to a boolean.
            if (in_array($metaValue, ['0', '1', "0", "1"])) {
                $metaValue = filter_var($metaValue, FILTER_VALIDATE_BOOLEAN);
            }

            // Clean-up field name by removing the prefix 'pdc-location-'.
            $fieldName = str_replace($this->posttype . '-', '', $field['id']);

            // Set value in the field array with a cleaned up fieldname as array key.
            $fields[$fieldName] = maybe_unserialize($metaValue);

            // Unset the old value by key.
            unset($fields[$key]);
        }

        return $fields;
    }
}
