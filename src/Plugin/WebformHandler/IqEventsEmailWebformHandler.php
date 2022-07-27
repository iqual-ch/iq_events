<?php
namespace Drupal\iq_events\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Emails a webform submission.
 *
 * @WebformHandler(
 *   id = "custom_recipient_email",
 *   label = @Translation("Custom Recipient email"),
 *   category = @Translation("Notification"),
 *   description = @Translation("Sends a webform submission to a different email address."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class IqEventsEmailWebformHandler extends EmailWebformHandler {

  public function sendMessage(WebformSubmissionInterface $webform_submission, array $message) {

    $values = $webform_submission->getData();
    if (!empty($values['to_mail']) && \Drupal::service('email.validator')->isValid($values['to_mail'])) {
      $message['to_mail'] = $values['to_mail'];
    }


    parent::sendMessage($webform_submission, $message);
  }

}
