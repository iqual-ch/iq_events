<?php

namespace Drupal\iq_events\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\webform\WebformSubmissionInterface;

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
 * @package Drupal\iq_group\Plugin\WebformHandler
 */
class IqEventsWebformSubmissionHandler extends \Drupal\webform\Plugin\WebformHandlerBase {

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {

    \Drupal::logger('iq_events')->notice('Eyoo yo ');
    $values = $webform_submission->getData();
    $user = NULL;
    /*foreach ($values as $key => $element) {

    }*/
      if (!empty($values['iq_event_instance'])) {
        $event_instance = Node::load($form_state->getValue($values['iq_event_instance']));
        if (!empty($event_instance)) {

          $number_of_registrations = $event_instance->field_iq_number_registrations->value;
          $event_instance->set('field_iq_number_registrations', $number_of_registrations - 1);
          $event_instance->save();
        }
      }

  }
}
