<?php

namespace Drupal\form_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;



class CustomForm extends FormBase {
    public function getFormId() {
        return 'custom_form';
      }

      public function buildForm(array $form, FormStateInterface $form_state) {

        /*
        Nombre: Campo de texto. Este campo es requerido y solo acepta caracteres
      alfanuméricos
        */
    $form['nombre'] = [
       '#type' => 'textfield',
       '#title' => 'Nombre',
       '#required' => TRUE,
       '#pattern' => '^[a-zA-Z0-9_]*$'

    ];

    /*
    Identificación: Campo de texto. Este campo es requerido y solo acepta números
    */
    $form['identificacion'] = [
      '#type' => 'textfield',
      '#title' => 'Identificación',
      '#required' => TRUE,
      '#pattern' => '^[0-9]$'

   ];

    /*
    Fecha de nacimiento: Campo de fecha. Puede usar el formato que mejor considere
pero debe dar un texto de ayuda al usuario
    */

    $form['fecha_nacimiento'] = [
      '#type' => 'date',
      '#title' => 'Fecha de nacimiento',
      '#required' => TRUE,
   ];

    /*Cargo: Campo de selección. Las opciones son Administrador, Webmaster,
Desarrollador.*/

$form['cargo'] = [
  '#type' => 'select',
  '#title' => t('Seleccione el cargo'),
  '#options' => [
    '1' => t('Administrador'),
    '2' => t('Webmaster'),
    '3' => t('Desarrollador')
  ],
];

        return $form;
      }
    

      public function validateForm(array &$form, FormStateInterface $form_state) {}
    
      public function submitForm(array &$form, FormStateInterface $form_state) {
    
      }
}

