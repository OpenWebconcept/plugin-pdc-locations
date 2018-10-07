<?php
/**
 * Provider which handles registration of posttype.
 */

namespace OWC\PDC\Locations\PostType;

use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider which handles registration of posttype.
 */
class LocationsPostTypeServiceProvider extends ServiceProvider
{
    /**
     * Name of posttype.
     *
     * @var string $postType
     */
    protected $postType = 'pdc-location';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostType');
    }

    /**
     * Register the Locations posttype.
     *
     * @return void
     */
    public function registerPostType()
    {

        if (!function_exists('register_extended_post_type')) {
            require_once $this->plugin->getRootPath() . '/src/Locations/vendor/johnbillion/extended-cpts/extended-cpts.php';
        }

        $labels = [
            'name'               => __('Locations', 'pdc-locations'),
            'singular_name'      => __('Location', 'pdc-locations'),
            'menu_name'          => __('Locations', 'pdc-locations'),
            'name_admin_bar'     => __('Locations', 'pdc-locations'),
            'add_new'            => __('Add new location', 'pdc-locations'),
            'add_new_item'       => __('Add new location', 'pdc-locations'),
            'new_item'           => __('New location', 'pdc-locations'),
            'edit_item'          => __('Edit location', 'pdc-locations'),
            'view_item'          => __('View location', 'pdc-locations'),
            'all_items'          => __('All locations', 'pdc-locations'),
            'search_items'       => __('Search locations', 'pdc-locations'),
            'parent_item_colon'  => __('Parent locations:', 'pdc-locations'),
            'not_found'          => __('No locations found.', 'pdc-locations'),
            'not_found_in_trash' => __('No locations found in Trash.', 'pdc-locations'),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('PDC Locations', 'pdc-locations'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => ['title', 'thumbnail', 'revisions', 'page-attributes'],
            'show_in_feed'       => false,
            'archive'            => false,
            'admin_cols'         => [
                'published' => [
                    'title'       => __('Published', 'pdc-locations'),
                    'post_field'  => 'post_date',
                    'date_format' => 'd M Y',
                ],
            ],
        ];

        return register_extended_post_type($this->postType, $args, $labels);
    }
}
