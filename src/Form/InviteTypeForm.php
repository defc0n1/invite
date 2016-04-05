<?php

/**
 * @file
 * Contains \Drupal\invite\Form\InviteTypeForm.
 */

namespace Drupal\invite\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class InviteTypeForm.
 *
 * @package Drupal\invite\Form
 */
class InviteTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $invite_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $invite_type->label(),
      '#description' => $this->t("Label for the Invite type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $invite_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\invite\Entity\InviteType::load',
      ),
      '#disabled' => !$invite_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $invite_type = $this->entity;
    $status = $invite_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Invite type.', [
          '%label' => $invite_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Invite type.', [
          '%label' => $invite_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($invite_type->urlInfo('collection'));
  }

}
