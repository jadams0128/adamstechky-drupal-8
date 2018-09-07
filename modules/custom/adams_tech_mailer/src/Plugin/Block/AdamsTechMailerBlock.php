<?php

namespace Drupal\adams_tech_mailer\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Adams Tech Mailer' Block
 *
 * @Block(
 * id = "adams_tech_mailer_block",
 * admin_label = @Translation("Adams Tech: Mailer Block"),
 * )
 */
class AdamsTechMailerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\adams_tech_mailer\Form\AdamsTechMailerForm');
    return $form;
  }

}