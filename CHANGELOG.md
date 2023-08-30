# CHANGELOG

## Version 2.2.0

- Fix: wrong open messages, don't show 'is closed' per default but look for upcoming time slots.

## Version 2.1.2

- Fix: switch to central media site when network media library plugin is active, when necessary.

## Version 2.1.1

- Feat: switch to central media site when network media library plugin is active, when necessary.

## Version 2.1.0

- Feat: support PHP 8 + update deps.

## Version 2.0.14

- Fix: upcoming special days messages overwrites tomorrow messages in API output.

## Version 2.0.13

- Change: remove isOpen check from special message check.

## Version 2.0.12

- Change: delimiter in Time slot class.
- Change: remove past time slots of current day.
- Feat: add weekend validation in CustomOpeningHours.
- Feat: translations.

## Version 2.0.11

- Fix: apecial opening hours not showing in week list.

## Version 2.0.10

- Chore: update dependencies + reference pdc-base plugin from BitBucket to GitHub.

## Version 2.0.9

- Fix: opening hours metabox type from text to time.

## Version 2.0.8

- Change: pdc-special-opening-date optional instead of required.

## Version 2.0.7

### Features

- Feat: special location opening times to default opening hours.

## Version 2.0.6

- Feat: special location opening times.

## Version 2.0.5

- Feat: ID to locations endpoint.

## Version 2.0.4

- Fix: opening hours monday when tomorrow is weekend.

## Version 2.0.3

- Feat: boolean 'openNow' to opening hours in endpoint.

## Version 2.0.2

- Fix: Permission callback in registration rest routes.

## Version 2.0.1

- Fix: incorrect use statement in Location model.

## Version 2.0.0

- Change: architecture in the pdc-base plugin, used as dependency, affects namespaces used.

## Version 1.0.7

- Chore: add custom opening hours with time slots.

## Version 1.0.6

- Fix: check if required file for `is_plugin_active` is already loaded, otherwise load it. Props @Jasper Heidebrink.

## Version 1.0.5

- Change: closed message.

## Version 1.0.4

- Change: add all image sizes with meta data to API output.

## Version 1.0.3

- Change: change opening hour messages.

## Version 1.0.2

- Change: logic for messages in API.

## Version 1.0.1

- Feat: image to API.

## Version 1.0.0

- Init: version.
