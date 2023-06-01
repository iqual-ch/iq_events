<?php

namespace Drupal\iq_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\node\NodeInterface;

/**
 * Controller for the event series.
 */
class IqEventsSeriesController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Generates a form to create series of an event.
   *
   * @param \Drupal\node\NodeInterface $node
   *   A node object.
   *
   * @return array
   *   An array as expected by \Drupal\Core\Render\RendererInterface::render().
   */
  public function registrationsOverview(NodeInterface $node) {

    $build = NULL;
    return $build;
  }

}
