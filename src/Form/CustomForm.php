<?php

namespace Drupal\form_module\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomForm extends FormBase
{
    public function getFormId()
    {
        return 'custom_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        //Se definen los campos del formulario

        /*
        Nombre: Campo de texto. Este campo es requerido y solo acepta caracteres
        alfanuméricos
         */
        $form['nombre'] = [
            '#type' => 'textfield',
            '#title' => 'Nombre',
            '#placeholder' => "Ingrese su nombre",
            '#required' => true,
            '#pattern' => '^[a-zA-Z0-9_. ]+$',

        ];

        /*
        Identificación: Campo de texto. Este campo es requerido y solo acepta números
         */
        $form['identificacion'] = [
            '#type' => 'textfield',
            '#title' => 'Identificación',
            '#placeholder' => "Ingrese su número de identificación",
            '#required' => true,
            '#pattern' => '^[0-9]*$',

        ];

        /*
        Fecha de nacimiento: Campo de fecha. Puede usar el formato que mejor considere
        pero debe dar un texto de ayuda al usuario
         */

        $form['fecha_nacimiento'] = [
            '#type' => 'date',
            '#title' => t('Fecha de nacimiento'),
            '#date_year_range' => '2010:+3',
            '#required' => true,
        ];

        /*Cargo: Campo de selección. Las opciones son Administrador, Webmaster,
        Desarrollador.*/

        $form['cargo'] = [
            '#type' => 'select',
            '#title' => t('Cargo'),
            '#options' => [
                '1' => t('Administrador'),
                '2' => t('Webmaster'),
                '3' => t('Desarrollador'),
            ],
            '#required' => true,
        ];

        $form['actions']['#type'] = 'actions';

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#attributes' => [
                'class' => ['col-md-6'],
            ],
            '#default_value' => t('Enviar'),
        ];

        $form['#theme'] = 'formulario_personalizado';

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {

        /*Se valida la cantidad de caracteres, la cual desbe ser mayor a 8 para poder pasar la validación */

        if (strlen($form_state->getValue('identificacion')) < 8) {
            $form_state->setErrorByName('identificacion', t('El núemro' . $form_state->getValue('identificacion') . ' de identificación es demasiado corto'));
        }
        

    }
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        //Se realiza el envio de los datos a la Base de Datos
        try {

            $cn = Database::getConnection();

            $value = $form_state->getValues();

            $values['nombre'] = $value['nombre'];
            $values['identificacion'] = $value['identificacion'];
            $values['fecha_nacimiento'] = $value['fecha_nacimiento'];
            $values['cargo_usuario'] = $value['cargo'];

            // Cambiar el valor de Estado segun el cargo seleccionado

            if ($value['cargo'] == 1) {
                $values['Estado'] = 1;
            } else {
                $values['Estado'] = 0;
            }

            $cn->insert('example_users')->fields($values)->execute();
            
            // Se muestra mensaje de envio de los datos

            \Drupal::messenger()->addMessage(t('Información envia con exito '));

        } catch (Exception $ex) {
            dpm($ex->getMessage());
        }
    }
}
