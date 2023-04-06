<?php

namespace Drupal\iq_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\node\NodeInterface;

/**
 * Returns response for the registrations tab.
 */
class IqEventsRegistrationsController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Generates an overview table of registrations for an event node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The event node object.
   *
   * @return array
   *   An array as expected by \Drupal\Core\Render\RendererInterface::render().
   */
  public function registrationsOverview(NodeInterface $node) {
    $build = views_embed_view('iq_registrations', 'block_1', $node->id());
    return $build;
  }

}
