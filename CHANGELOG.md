# CHANGELOG

## Version 2.2.0

### Fix

-   Wrong open messages, don't show 'is closed' per default but look for upcoming timeslots.

## Version 2.1.2

### Fix

-   Switch to central media site when network media library plugin is active, when necessary.

## Version 2.1.1

### Feat

-   Switch to central media site when network media library plugin is active, when necessary.

## Version 2.1.0

### Feat

-   Support php8 + update deps

## Version 2.0.14

### Fix

-   Upcoming special days messages overwrites tomorrow messages in API output.

## Version 2.0.13

### Refactor

-   Remove isOpen check from special message check

## Version 2.0.12

### Refactor

-   Delimiter in Timeslot class.
-   Remove past timeslots of current day.
-   Add weekend validation in CustomOpeningHours
-   Add translations

## Version 2.0.11

### Fix

-   Special opening hours not showing in week list.

## Version 2.0.10

### Chore

-   Update dependencies + reference pdc-base plugin from BitBucket to GitHub

## Version 2.0.9

### Fix

-   Openinghours metabox type from text to time

## Version 2.0.8

### Refactor

-   pdc-special-opening-date optional instead of required

## Version 2.0.7

### Features

-   Add special location opening times to default openinghours

## Version 2.0.6

### Features

-   Add special location opening times

## Version 2.0.5

### Features

-   Add id to locations endpoint.

## Version 2.0.4

### Fixed

-   Openinghours monday when tomorrow is weekend

## Version 2.0.3

### Features

-   Add: boolean 'openNow' to openinghours in endpoint

## Version 2.0.2

### Fix

-   Permission callback in registration rest routes

## Version 2.0.1

-   (fix): incorrect use statement in Location model

## Version 2.0.0

### Changed

-   Architecture change in the pdc-base plug-in, used as dependency, affects namespaces used

## Version 1.0.7

### Features:

-   (chore): add custom openinghours with timeslots.

## Version 1.0.6

### Fix:

-   (fix): check if required file for `is_plugin_active` is already loaded, otherwise load it. Props @Jasper Heidebrink

## Version 1.0.5

### Features:

-   Change: closed message

## Version 1.0.4

### Features:

-   Change: add all image sizes with meta data to api output

## Version 1.0.3

### Features:

-   Change: change openinghour messages

## Version 1.0.2

### Features:

-   Change: logic for messages in api

## Version 1.0.1

### Features:

-   Add: image to api

## Version 1.0.0

### Features:

-   Initial version
