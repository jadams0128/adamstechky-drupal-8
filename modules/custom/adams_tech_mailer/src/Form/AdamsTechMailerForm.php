<?php
/**
 * @file
 * Contains Drupal\adams_tech_mailer\AdamsTechMailerForm
 */
namespace Drupal\adams_tech_mailer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AdamsTechMailerForm extends FormBase {

  public function getFormId() {
    return 'adams_tech_mailer_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
   
    $user = \Drupal::currentUser();
    $currentUserName = "";
    $currentUserEmail = "";
    
    if(!$user->isAnonymous()){
       $currentUserEmail = $user->getEmail();
       $currentUserName = $user->getAccountName();
    }
    
    $form['sender_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => true,
      '#default_value' => $currentUserName ? $currentUserName : ""
    );

    $form['sender_email'] = array(
      '#type' => 'email',
      '#title'=> t('Email:'),
      '#required' => true,
      '#default_value' => $currentUserEmail ? $currentUserEmail : ""
    );

    $form['sender_phone'] = array(
      '#type' => 'textfield',
      '#title'=> t('Phone:'),
    );

    $form['sender_subject'] = array(
      '#type' => 'textfield',
      '#title'=> t('Subject:'),
      '#required' => true
    );

    $form['sender_message'] = array(
      '#type' => 'textarea',
      '#title'=> t('Message:'),
      '#required' => true
    );

    $form['sender_submit'] = array(
      '#type' => 'submit',
      '#value' => 'Send Message'
    );
    
    $form['captcha_element'] = array(
      '#type' => 'captcha',
      '#captcha_type' => 'captcha/Math',
    );
    
    $form['#theme'] = 'adams_tech_mailer_form';

    return $form;
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
  
   $phone = $form_state->getValue('sender_phone');
   
   if (!empty($phone)) {
        $phoneValidation = $this->ValidatePhone($phone);
        if($phoneValidation == false){
           $form_state->setErrorByName('sender_phone', $this->t('Phone not formatted correctly.'));
        } 
   }
   
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('sender_name');
    $email = $form_state->getValue('sender_email');
    $phone = $form_state->getValue('sender_phone');
    $subject = $form_state->getValue('sender_subject');
    $message = $form_state->getValue('sender_message');
    
    $result = $this->sendEmail($name, $email, $subject, $message, $phone);
     
    if($result){
      drupal_set_message(t('Your message was successfully sent.'), 'status', TRUE);
    }else{
      drupal_set_message(t('Message Failed to send, contact the administrator.'), 'error', TRUE);
    }
  }

  public function sendEmail($name, $email, $subject, $message, $phone){
    $url = 'https://api.sendgrid.com/';
    $user = 'adams.jamie@adamstechky.com';
    $pass = '!Julieann30!';
    
    $json_string = array(
      'to' => array($user),
      'category' => 'Contact_form'
    );
    
    $params = array(
      'api_user'  => $user,
      'api_key'   => $pass,
      'x-smtpapi' => json_encode($json_string),
      'to'        => 'adams.jamie@adamstechky.com',
      'subject'   => $subject,
      'html'      => $this->HtmlBody($message, $name, $phone),
      'from'      => $email
    );
    
    $request =  $url.'api/mail.send.json';
    $session = curl_init($request);
    
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($session);
    curl_close($session);
    \Drupal::logger('custom_mailer_form')->info($response);
    return $response;
  }
  
  public function HtmlBody($message, $name, $phone){
    $htmlBody = "";
    $htmlBody .="<div>";
      $htmlBody .="<h1 style='text-align: center;'>";
        $htmlBody .="New Contact Message";
      $htmlBody .="</h1>";
      $htmlBody .="<span>";
        $htmlBody .="You have recieved a new message from " . $name;
      $htmlBody .="</span><br />";
      if ($phone) {
        $htmlBody .="<span>";
          $htmlBody .="<strong>Phone:</strong> " . $phone;
        $htmlBody .="</span><br />";
      }
      $htmlBody .="<span>";
        $htmlBody .="<strong>Message:</strong> " . $message;
      $htmlBody .="</span>";
    $htmlBody .="</div>";

    return $htmlBody;
  }
  
  public function ValidatePhone($phone){
		$validation = "/^(?:(?:\((?=\d{3}\)))?(\d{3})(?:(?<=\(\d{3})\))?[\s.\/-]?)?(\d{3})[\s\.\/-]?(\d{4})\s?(?:(?:'extension'(?:(?:e|x|ex|ext)\.?|)\s?)(?=\d+)(\d+))?$/x";
	
    if (preg_match($validation, $phone)) {
          return TRUE;
    } else {
          return FALSE;
    }
  }

}
