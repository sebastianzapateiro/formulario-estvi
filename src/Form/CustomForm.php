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
       '#pattern' => '^[a-zA-Z0-9_. ]+$'

    ];

    /*
    Identificación: Campo de texto. Este campo es requerido y solo acepta números
    */
    $form['identificacion'] = [
      '#type' => 'textfield',
      '#title' => 'Identificación',
      '#required' => TRUE,
      '#pattern' => '^[0-9]*$'

   ];

    /*
    Fecha de nacimiento: Campo de fecha. Puede usar el formato que mejor considere
pero debe dar un texto de ayuda al usuario
    */

    $form['fecha_nacimiento'] = [
      '#type' => 'date',
      '#title' => t('Fecha de nacimiento'),
      '#description' => t('Seleccione su fecha de nacimiento'),
      '#required' => TRUE,
   ];

    /*Cargo: Campo de selección. Las opciones son Administrador, Webmaster,
Desarrollador.*/

$form['cargo'] = [
  '#type' => 'select',
  '#title' => t('Cargo'),
  '#options' => [
    '1' => t('Administrador'),
    '2' => t('Webmaster'),
    '3' => t('Desarrollador')
  ],
  '#required' => TRUE,
];

$form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#default_value' => t('Enviar') ,
    ];


$form['#theme'] = 'formulario_personalizado';

        return $form;
      }
    

      public function validateForm(array &$form, FormStateInterface $form_state) {

        if (strlen($form_state->getValue('identificacion')) < 3) {
          $form_state->setErrorByName('identificacion', t('El núemro'.$form_state->getValue('identificacion').' de identificación es demasiado corto'));
        }

      }
    
      public function submitForm(array &$form, FormStateInterface $form_state) {
        $field = $form_state->getValues();

        \Drupal::messenger()->addMessage(t('Información de usuario = '.$field["nombre"]));

      }
}

