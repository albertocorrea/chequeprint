<?php
namespace Drupal\cheque_creator\Validate;

use Drupal\Core\Field\FieldException;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form API callback. Validate element value.
 */
class AmountConstraint {

  static $_limit = 100000000000000;
  /**
   * Validates given element.
   *
   * @param array              $element      The form element to process.
   * @param FormStateInterface $formState    The form state.
   * @param array              $form The complete form structure.
   */
  public static function validate(array &$element, FormStateInterface $formState, array &$form) {
    $webformKey = $element['#webform_key'];
    $value = $formState->getValue($webformKey);

    if ($value === '' || is_array($value)) {
      return;
    }
    $value = str_replace(array(' ','$'), '' , trim($value));
    if (!preg_match('/^[0-9]{1,3}(,[0-9]{3})*\.[0-9]+$/', $value)) {
      $formState->setError(
        $element,
        t('Please insert a currency value in the format 1,423.45')
      );
    }

    $value = str_replace(array(','), '' , trim($value));
    $amount = (float) $value;
    if ($amount > self::$_limit) {
      $formState->setError(
        $element,
        t('Amount must be less than 100 trillion')
      );
    }
  }
}
