<?php

/**
* @file
* Contains \Drupal\adamstech\Form\adamstechSendGridForm.
*/

namespace Drupal\adamstech\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class adamstechSendGridForm extends ConfigFormBase {

  //Sets the form id
  public function getFormId() {
    return 'adams_tech_send_grid_form';
  }

  //Retreives system setting variables
  protected function getEditableConfigNames() {
    return ['adamstechSendGrid.settings'];
  }

  //Builds the form
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('adamstechSendGrid.settings');

    //Send Grid URL
    $form['sendgrid_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Send Grid URL'),
      '#default_value' => $config->get('sendgrid_url')
    );

    //Send Grid Username
    $form['sendgrid_username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Send Grid Username'),
      '#default_value' => $config->get('sendgrid_username')
    );

    //Send Grid Password
    $form['sendgrid_password'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Send Grid Password'),
      '#default_value' => $config->get('sendgrid_password')
    );

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Form Validation
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    //Submits Form
    parent::submitForm($form, $form_state);

    //Sets form state variables.
    $config = $this->config('adamstechSendGrid.settings');
    $sendgrid_url = $form_state->getValue('sendgrid_url');
    $sendgrid_username = $form_state->getValue('sendgrid_username');
    $sendgrid_password = $form_state->getValue('sendgrid_password');

    //Sets the system Send Grid URL variable.
    \Drupal::state()->set('sendgrid_url', $sendgrid_url);
    \Drupal::state()->set('sendgrid_username', $sendgrid_username);
    \Drupal::state()->set('sendgrid_password', $sendgrid_password);

    //sets values as saved values in database.
    $config->set('sendgrid_url', $sendgrid_url);
    $config->set('sendgrid_username', $sendgrid_username);
    $config->set('sendgrid_password', $sendgrid_password);
    $config->save();

  }
}