# iq_events
Iq Events is a module that offers functionality for creating and managing events.

## Installation on Drupal 9:

Install module as usual:

    composer require iqual/iq_events
    drush en iq_events

## Installation on Drupal 10:

Dependency "drupal/vefl" does not have a Drupal 10 release. You need to use the `mglaman/composer-drupal-lenient` on your project to allow installation:

    composer config minimum-stability dev
    composer require mglaman/composer-drupal-lenient
    composer config --merge --json extra.drupal-lenient.allowed-list '["drupal/address_formatter"]'
