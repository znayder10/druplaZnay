<?php

namespace Drupal\new_email_formatter\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'new_email_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "new_email_field_formatter",
 *   label = @Translation("New email field formatter"),
 *   field_types = {
 *     "email"
 *   }
 * )
 */
class NewEmailFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = [];
      /*
       * [
       * "email"=> "nich.gonse@gmail.com"
       * ]
     * */
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
          '#type' => 'markup',
          '#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
      $url= Url::fromUri('mailto:' . $item->value);/*genera la url a donde va el correo*/
      $link= Link::fromTextAndUrl($this->t('Send email'),$url);/*recibe 2 parametros (el texto, la url)*/
      /*al final lo que esta creando es lo siguiente:
      <a href='mailto:nick.gonse@gmail.com'>Send Email</a>*/

      return $link->toString();

    //return nl2br(Html::escape($item->value));
  }

}
