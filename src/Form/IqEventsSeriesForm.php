<?php

namespace Drupal\iq_events\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Datetime\DateHelper;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IqEventsSeriesForm extends FormBase {

  /**
   * The Event.
   *
   * @var \Drupal\node\Entity\Node
   */
  protected $event;

  public function __construct()
  {
    $this->event = $this->getRouteMatch()->getParameter('node');
  }

  public function getFormId()
  {
    // TODO: Implement getFormId() method.
    return 'iq_events_series_form';
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state)
  {
    if (!empty($this->event) && $this->event->hasField('field_iq_event_instances')) {
      $event_instances = $this->event->field_iq_event_instances->getValue();

      $event_instance_options = [];
      $event_instance_options['new'] = 'Create new';
      $default_value_instance = 'new';
      foreach ($event_instances as $key => $event_instance_id) {
        $event_instance = Node::load($event_instance_id['target_id']);
        if (!empty($event_instance)) {
          $event_instance_options[$event_instance->id()] = $event_instance->label();
          $default_value_instance = $event_instance->id();
        }
      }

        // condition for the field (only if the new is selected)
        //'#states' => [
        //        'visible' => [':input[name*="uniqid_enabled"]' => ['checked' => TRUE]],
        //      ],
        // search for the form api.
      $form['event_instance'] = [
        '#type' => 'select',
        '#options' => $event_instance_options,
        '#title' => t('Event instance'),
        '#default_value' => $default_value_instance
      ];
      // Load a form for the event instance.
      $values = array('type' => 'iq_event_instance');

      $node = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->create($values);

      $form['new_event_instance'] = [
        '#type' => 'details',
        '#title' => t('New Event Instance'),
        '#states' => [
          'visible' => ['select[name="event_instance"]' => ['value' => 'new']],
        ]
      ];
      //\Drupal::logger('iq_events')->notice($node->id());
      $form['new_event_instance']['inline_entity_form'] = [
        '#type' => 'inline_entity_form',
        '#entity_type' => 'node',
        '#bundle' => 'iq_event_instance',
        '#form_mode' => 'default',
        '#default_value' => $node
      ];
      //$form['new_event_instance']['form'] = \Drupal::service('entity.form_builder')->getForm($node, 'default');

      $form['repeat_week'] = [
        '#type' => 'number',
        '#title' => t('Repeat every X-th week'),
      ];
      $form['days'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Days'),
        '#options' => [
          '0' => t('Sunday'),
          '1' => t('Monday'),
          '2' => t('Tuesday'),
          '3' => t('Wednesday'),
          '4' => t('Thursday'),
          '5' => t('Friday'),
          '6' => t('Saturday'),
        ],
      ];
      $form['from'] = [
        '#type' => 'date',
        '#title' => t('From'),
        '#time' => FALSE,
      ];
      $form['end_date_or_repetitions'] = [
        '#type' => 'select',
        '#title' => t('Ends at'),
        '#options' => [
          'end_date' => t('End date'),
          'repetitions' => t('Repetitions')
        ],
        '#attributes' => [
          'class' => ['container-inline'],
        ],
      ];
      $form['end_date'] = [
        '#type' => 'date',
        '#title' => t('To'),
        '#time' => FALSE,
        '#states' => [
          'visible' => [':input[name*="end_date_or_repetitions"]' => ['value' => 'end_date']],
        ],
        '#attributes' => [
          'class' => ['container-inline'],
        ],
      ];
      $form['repetitions'] = [
        '#type' => 'number',
        '#title' => t('Repetitions'),
        '#time' => FALSE,
        '#states' => [
          'visible' => [':input[name*="end_date_or_repetitions"]' => ['value' => 'repetitions']],
        ],
        '#attributes' => [
          'class' => ['container-inline'],
        ],
      ];
    }
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Create Series',
      '#ajax' => array(
        'callback' => '::ajaxSubmitForm',
        'event' => 'click',
      ),
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {

  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // TODO: Implement submitForm() method.
  }

  public function ajaxSubmitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state)
  {

    // clone the instance for repetitions times or on every day until the end date.
    // or if new instance - create the instance that many times or until end date with the data from the form embedded previously.
    $response = new AjaxResponse();
    $instance_id = $form_state->getValue('event_instance');
    if ($instance_id != 'new') {
      $event_instance = Node::load($instance_id);
    }
    else {
      /** @var Node $event_instance */
      $event_instance = $form['new_event_instance']['inline_entity_form']['#entity'];
      $event_instance->save();
      \Drupal::logger('iq_events')->notice($event_instance->getTitle());
    }
    $all_instances = $this->event->get('field_iq_event_instances')->getValue();
    if (!empty($event_instance)) {
      /** @var DrupalDateTime $start_date */
      $start_date = DrupalDateTime::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $event_instance->field_iq_date->value);
      $day_options = $form_state->getValue('days');
      $selected_days = [];
      foreach ($day_options as $key => $value) {
        if ($value != 0) {
          $selected_days[] = $key;
        }
      }

      $end_date = DrupalDateTime::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $event_instance->field_iq_date->end_value);
      $interval = $start_date->diff($end_date);
      $start_time = explode(':', $start_date->format('H:i:s'));
      $new_start_date = $form_state->getValue('from');
      $year = explode('-', $new_start_date)[0];
      $month = explode('-', $new_start_date)[1];
      $day = explode('-', $new_start_date)[2];
      $start_date = $start_date->setDate($year, $month, $day);
      // If the day is selected, add an instance for that day.
      $repeat_per_week = $form_state->getValue('repeat_week');

      if ($form_state->getValue('end_date_or_repetitions') == 'repetitions') {
        $repetitions = $form_state->getValue('repetitions');
        for ($j = 1; $j <= $repetitions; $j++) {
          $week_start_date = $start_date;
          for ($i = 0 ; $i < 7; $i++) {
            if (in_array($week_start_date->format('w'), $selected_days)) {
              /** @var Node $duplicate */
              $duplicate = $event_instance->createDuplicate();
              $duplicate->setTitle($event_instance->label() .' (' . $week_start_date->format('d.m.Y') . ')');
              $week_start_date->setTime($start_time[0], $start_time[1], $start_time[2]);
              $duplicate->field_iq_date->value = $week_start_date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
              $week_end_date = $week_start_date;
              $week_end_date->add($interval);
              $duplicate->field_iq_date->end_value = $week_end_date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
              $duplicate->save();
              $all_instances[] = ['target_id' => $duplicate->id()];
            }
            $week_start_date->modify('+24 hours');
          }
          $start_date->modify('+' . $repeat_per_week * 7 . ' days');
        }
      }
      else {
        $end_date_for_repetitions = $form_state->getValue('end_date');
        $end_date_for_repetitions = DrupalDateTime::createFromFormat('Y-m-d',$end_date_for_repetitions);
        $before_end_date_flag = $start_date->diff($end_date_for_repetitions);
        while ($before_end_date_flag->invert != 1) {
          $week_start_date = DrupalDateTime::createFromFormat(DateTimeItemInterface::DATETIME_STORAGE_FORMAT, $start_date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
          for ($i = 0 ; $i < 7; $i++) {
            if (in_array($week_start_date->format('w'), $selected_days)) {
              /** @var Node $duplicate */
              $duplicate = $event_instance->createDuplicate();
              $duplicate->setTitle($event_instance->label() .' (' . $week_start_date->format('d.m.Y') . ')');
              $week_start_date->setTime($start_time[0], $start_time[1], $start_time[2]);
              $duplicate->field_iq_date->value = $week_start_date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
              $week_end_date = $week_start_date;
              $week_end_date->add($interval);
              $duplicate->field_iq_date->end_value = $week_end_date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
              $duplicate->save();
              $all_instances[] = ['target_id' => $duplicate->id()];
            }
            $week_start_date->modify('+24 hours');
          }
          $start_date->modify('+' . ($repeat_per_week * 7) . ' days');
          $before_end_date_flag = $start_date->diff($end_date_for_repetitions);
        }
      }
    }
    $this->event->field_iq_event_instances = $all_instances;
    $this->event->save();

    $response->addCommand(new CloseModalDialogCommand());
    return $response;
  }
}
