# Documentation

## Setup

* Unzip and/or move all files to the /wp-content/plugins/pdc-locations directory
* Log into WordPress admin and activate the 'PDC Locations' plugin through the 'Plugins' menu

### Composer

This plugin can also be installed through [Composer](https://getcomposer.org/), `Dependency Manager for PHP`.

Under `repositories` in `composer.json`, add the follow respository:

```ruby
{
    "type": "vcs",
    "url": "git@bitbucket.org:openwebconcept/plugin-pdc-locations.git"
}
```

And under the `require`, add the following:

```ruby
"plugin/pdc-locations": "^2.0"
```

## REST API

Main endpoint: [https://{domain}/wp-json/owc/pdc/v1](https://{domain}/wp-json/owc/pdc/v1)

Location items: [https://{domain}/wp-json/owc/pdc/v1/locations](https://{domain}/wp-json/owc/pdc/v1)

Location item detail: [https://{domain}/wp-json/owc/pdc/v1/locations/{id}](https://{domain}/wp-json/owc/pdc/v1)

For API specifications, we use the [OpenAPI specs](api/index.html).

## Hooks

### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

#### Action for changing main Plugin object

```php
'owc/pdc-locations/plugin'
```

See OWC\PDC\Locations\Config->set method for a way to change this plugins config.

Via the plugin object the following config settings can be adjusted

* metaboxes
* rest_api_fields

## Translations

If you want to use your own set of labels/names/descriptions and so on you can do so.
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/pdc-locations/languages/pdc-locations.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the '[Loco Translate](https://wordpress.org/plugins/loco-translate/)' plugin.

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advice you to read the Beginners's guide page on their [website](https://localise.biz/wordpress/plugin/beginners) or start at the [homepage](https://localise.biz/wordpress/plugin).

## Tests

To run the Unit tests go to a command-line.

```bash
cd /path/to/wordpress/htdocs/wp-content/plugins/pdc-locations/
composer install
phpunit
```

For code coverage report, generate report with command line command and view results with browser.

```bash
phpunit --coverage-html ./tests/coverage
```
