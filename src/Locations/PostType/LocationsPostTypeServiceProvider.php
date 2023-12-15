<?php

namespace OWC\PDC\Locations\PostType;

use OWC\PDC\Base\Foundation\ServiceProvider;

class LocationsPostTypeServiceProvider extends ServiceProvider
{
    /**
     * Name of posttype.
     */
    protected $postType = 'pdc-location';

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostType');
    }

    /**
     * Register the Locations posttype.
     */
    public function registerPostType(): void
    {
        if (! function_exists('register_extended_post_type')) {
            require_once $this->plugin->getRootPath() . '/src/Locations/vendor/johnbillion/extended-cpts/extended-cpts.php';
        }

        $labels = [
            'name'               => __('Locaties', 'pdc-locations'),
            'singular_name'      => __('Locatie', 'pdc-locations'),
            'menu_name'          => __('Locaties', 'pdc-locations'),
            'name_admin_bar'     => __('Locaties', 'pdc-locations'),
            'add_new'            => __('Voeg nieuwe locatie toe', 'pdc-locations'),
            'add_new_item'       => __('Voeg nieuwe locatie toe', 'pdc-locations'),
            'new_item'           => __('Nieuwe locatie', 'pdc-locations'),
            'edit_item'          => __('Wijzig locatie', 'pdc-locations'),
            'view_item'          => __('Bekijk locatie', 'pdc-locations'),
            'all_items'          => __('Alle locaties', 'pdc-locations'),
            'search_items'       => __('Zoek locaties', 'pdc-locations'),
            'parent_item_colon'  => __('Hoofd locaties:', 'pdc-locations'),
            'not_found'          => __('Geen locatie gevonden.', 'pdc-locations'),
            'not_found_in_trash' => __('Geen locatie gevonden de prullenbak.', 'pdc-locations'),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('PDC Locaties', 'pdc-locations'),
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
                    'title'       => __('Gepubliceerd', 'pdc-locations'),
                    'post_field'  => 'post_date',
                    'date_format' => 'd M Y',
                ],
            ],
        ];

        \register_extended_post_type($this->postType, $args, $labels);
    }
}
