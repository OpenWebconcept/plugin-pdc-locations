# README #

This README documents whatever steps are necessary to get this plugin up and running.

### How do I get set up? ###

* Unzip and/or move all files to the /wp-content/plugins/pdc-locations directory
* Log into WordPress admin and activate the ‘PDC Locations’ plugin through the ‘Plugins’ menu

### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

##### Action for changing main Plugin object

```php
'owc/pdc-locations/plugin'
```

See OWC\PDC\Locations\Config->set method for a way to change this plugins config.

Via the plugin object the following config settings can be adjusted

* metaboxes
* rest_api_fields

### Translations ###

If you want to use your own set of labels/names/descriptions and so on you can do so.
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/pdc-locations/languages/pdc-locations.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the 'Loco Translate' plugin.
<https://wordpress.org/plugins/loco-translate/>

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advice you to read the Beginners's guide page on their website: <https://localise.biz/wordpress/plugin/beginners>
or start at the homepage: <https://localise.biz/wordpress/plugin>

### Running tests ###

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

### Contribution guidelines ###

##### Writing tests

Have a look at the code coverage reports to see where more coverage can be obtained.
Write tests
Create a Pull request to the OWC repository

### Who do I talk to? ###

If you have questions about or suggestions for this plugin, please contact <a href="mailto:hpeters@Buren.nl">Holger Peters</a> from Gemeente Buren.

### Hooks

In this section, you can customize the messages displayed for the opening hours of a location. The hooks allow you to modify the "open now" message dynamically, based on the location's open and close times.

#### Example: Customizing the "Open Now" Message

The following filters let you customize the message that appears, in the REST API output, when a location is currently open. By default, the message format is: Now open from %s to %s hour.

You can use the filters below to change this to a more concise format, such as displaying only the closing time of the location.

```php
/**
 * Modify the "Open Now" message for the general opening hours.
 * Default message: "Now open from %s to %s hour"
 * Custom message: "Nu open tot %s" (Dutch for "Now open until %s")
 */
add_filter('owc/pdc-locations/openingshours/open-now-message', function ($openNowMessage, $openTime, $closeTime) {
    return sprintf('Nu open tot %s', $closeTime);
}, 10, 3);

/**
 * Modify the "Open Now" message for custom opening hours.
 * This hook works similarly to the above but targets locations with specific custom schedules.
 * Custom message: "Nu open tot %s" (Dutch for "Now open until %s")
 */
add_filter('owc/pdc-locations/custom-openingshours/open-now-message', function ($openNowMessage, $openTime, $closeTime) {
    return sprintf('Nu open tot %s', $closeTime);
}, 10, 3);
```

You can easily modify the message by updating the sprintf statement to suit your needs.
