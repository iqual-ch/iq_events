<?php

namespace Drupal\iq_events\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * IQ Events Webform submission handler.
 *
 * @WebformHandler(
 *     id = "iq_events_submission_handler",
 *     label = @Translation("IQ Event Submission Handler"),
 *     category = @Translation("Form Handler"),
 *     description = @Translation("Updates capacity of events on submissions"),
 *     cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *     results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class IqEventsWebformSubmissionHandler extends WebformHandlerBase {

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {

    $values = $webform_submission->getData();
    /*foreach ($values as $key => $element) {

    }*/
    if (!empty($values['iq_event_instance'])) {
      // @todo See how many people are registered with the webform.
      $nr_persons = 1;
      $event_instance = Node::load($values['iq_event_instance']);
      $event = Node::load($values['iq_event']);
      if (!empty($event_instance)) {
        if (!empty($values['iq_nr_persons'])) {
          $nr_persons += $values['iq_nr_persons'];
        }

        $number_of_registrations = $event_instance->field_iq_number_registrations->value;

        $event_instance->set('field_iq_number_registrations', $number_of_registrations + $nr_persons);
        $event_instance->save();
        if (!empty($event)) {
          $url = Url::fromRoute('entity.node.canonical', ['node' => $event->id()]);
          $response = new RedirectResponse($url->toString());
          $response->send();
        }
      }
    }

  }

}
