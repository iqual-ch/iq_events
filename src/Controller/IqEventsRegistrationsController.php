<?php

namespace Drupal\iq_events\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\node\NodeStorageInterface;
use Drupal\node\NodeTypeInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
