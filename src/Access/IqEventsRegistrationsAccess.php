<?php

namespace Drupal\iq_events\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Determines whether the requested node can have registrations tab.
 */
class IqEventsRegistrationsAccess implements AccessInterface {

  public function checkAccess(RouteMatchInterface $route_match, AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');

    // Only show the tab for event instances.
    return AccessResult::allowedIf($node->bundle() === 'iq_event');
  }

}
