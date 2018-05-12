<?php

namespace Drupal\entityreferenceview\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'entity_reference_view_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_reference_view_field_formatter",
 *   label = @Translation("Entity reference view field formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class EntityReferenceViewFieldFormatter extends EntityReferenceFormatterBase {// cambiamos el entityreferences por EntityReferenceFormatterBase para usar mas metodos

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {//es para datos por defectox|
    return [
      // Implement default settings.
            'path_view'=>''
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {//crear formulario y el usuario agregar información

      $formulario= parent::settingsForm($form, $form_state);
      $formulario['path_view']=[
          '#type'=> 'textfield',
          '#title'=> $this->t('Link a la vista'),
          '#default_value' => $this->getSetting('path_view'),
          '#size' => 60,
          '#maxlenght' => 128,
          '#required' => TRUE
          ];
    return $formulario;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {//hacer un resumen de la info que el usuario ingreso
    $summary = [];
    // Implement settings summary.
      if (!empty($this->getSetting('path_view'))){
          $summary[]=$this->t('El enlace a la vista es: @path',
              ['@path' => $this->getSetting('path_view')]
          );
      }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) { //imprime el valor que le agregemos en el html
    $elements = [];

    foreach ($this->getEntitiesToView($items,$langcode) as $delta => $item) {// creando el item en una entidad (objeto con todos los atributos)
      $elements[$delta] = ['#markup' => $this->viewValue($item)];//el markup agarra lo que se le pasó y lo pone en el html
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
  protected function viewValue(EntityInterface $entity) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    //obtener url base
      global $base_url;

      //crear variable label para obtener los atributos del objeto
      $label=$entity->label();// es el nombre del actor o director
      $id=$entity->id();

      //creamos la url
      $urlView=$base_url.'/'.$this->getSetting('path_view').'/'.$id;
      $url= Url::fromUri($urlView);
      $link=Link::fromTextAndUrl($this->t('@NombreActor',['@NombreActor'=>$label]),$url);//link hace la etiqueta <a> con el href="$url"

      return $link->toString();
  }

}
