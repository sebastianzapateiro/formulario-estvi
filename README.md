# Cumplimiento de actividades

Tomando en cuenta los puntos asignados para realizar el formulario en Drupal 8 o 9, procedo a indicar el grado de cumpliminto que he podido alcanzar.

El modulo creado se llama Formulario custom y es el que se asocia en el presente repositorio.

## 1. El módulo debe declarar dos rutas en el sistema /example-module/form y /example-module/data.

Para este punto se declara en el archivo _form_module.routing.yml_ las dos rutas indicadas. Para _/example-module/form_ se implementa el formulario y para _/example-module/data_ un controlador que hace posible la consulta a la base de datos para presentar los campos en el front.
```sh
form_module.example_module_form:
  path: '/example-module/form'
  defaults:
    _form: '\Drupal\form_module\Form\CustomForm'
  requirements:
    _permission: 'access content'  

form_module.example_module_data:
  path: '/example-module/data'
  defaults:
    _controller: 'Drupal\form_module\Controller\CustomFormShow::getValues'
  requirements:
    _permission: 'access content'
```
## 2. En la ruta /example-module/form se debe mostrar un formulario que tenga los siguientes campos.

A partir de la clase abstracta FormBase se crean los siguientes campos:

| Campo | Caracteristica |
| ------ | ------ |
| Nombre | Campo de texto. Este campo es requerido y solo acepta caracteres alfanuméricos.|
| Identificación | Campo de texto. Este campo es requerido y solo acepta números. • Fecha de nacimiento: Campo de fecha. Puede usar el formato que mejor considere pero debe dar un texto de ayuda al usuario |
| Cargo |  Campo de selección. Las opciones son Administrador, Webmaster,
Desarrollador. |

A través del array $form se construyen los inputs.

_Input nombre:_
```sh
        $form['nombre'] = [
            '#type' => 'textfield',
            '#title' => 'Nombre',
            '#placeholder' => "Ingrese su nombre",
            '#required' => true,
            '#pattern' => '^[a-zA-Z0-9_. ]+$',

        ];
```

A nivel visual se implementa Bootstrap y no se hace la adición de CSS extra.

![image](https://user-images.githubusercontent.com/87027597/206190283-876042ca-ae40-4c8a-bf0e-5646b65c46f5.png)

## 3. Al momento de la instalación el módulo debe crear una tabla que se llame “example_users” y que contenga los campos que se mencionaron en el punto anterior más un campo llamado “Estado”.

Se define el schema de la tabla en el archivo _form_module.install_ del modulo, para que una vez este sea instalado se ejecute el hook _hook_schema_ y la tabla sea creada.
Destaco que fue necesario añadir el mysql_type => date ya que he usado MariaDB como motor de base datos.


## 4. Validar obligatoriedad, formatos de los campos utilizando las funciones apropiadas.

A través de la función _validateForm_ se efectuan validaciones para determinar si los inputs no cuentan con información, evitando el envio del formulario.

```sh
    public function validateForm(array &$form, FormStateInterface $form_state)
    {

        /*Se valida la cantidad de caracteres, la cual desbe ser mayor a 8 para poder pasar la validación */

        if (strlen($form_state->getValue('identificacion')) < 8 || !$form_state->hasValue('identificacion')) {
            $form_state->setErrorByName('identificacion', t('El número' . $form_state->getValue('identificacion') . ' de identificación es demasiado corto'));
        }
        if (!$form_state->hasValue('nombre')) {
            $form_state->setErrorByName('nombre', t('El campo es obligatorio'));
        }
        if (!$form_state->hasValue('cargo')) {
            $form_state->setErrorByName('cargo', t('El campo es obligatorio'));
        }
        if (!$form_state->hasValue('fecha_nacimiento')) {
            $form_state->setErrorByName('fecha_nacimiento', t('El campo es obligatorio'));
        }

    }
```

## 5. Al realizar el envío del formulario, llenar los datos en la tabla mencionada en el paso anterior. Colocar el campo “Estado” en 1 si el cargo es “Administrador” o 0 para el resto de valores. Adicionalmente mostrar un mensaje en pantalla al finalizar el envío del formulario.

Al diligenciar el formulario con todas las validaciones definidas se almacena en la tabla _example_users_ la información. Para la columna Estado se opta por un if else que asigna el valor de 0 o 1 de acuerdo al cargo del usuario a registrar.

```sh
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

            \Drupal::messenger()->addMessage(t('Información enviada con exito '));

        } catch (Exception $ex) {
            dpm($ex->getMessage());
        }
    }
```
Al enviar el formulario se muestra un msj de envio satisfactorio.

![image](https://user-images.githubusercontent.com/87027597/206193391-0214c3a9-e236-4621-b224-ee5781d07c73.png)


_Evidencia de datos almacenados_

![image](https://user-images.githubusercontent.com/87027597/206194207-80b3b70d-050a-4197-933a-316a59b6fd79.png)

## 6. Como punto adicional (No Obligatorio) se valora el desarrollo del formulario de envió con Ajax (Usando el API de Ajax de Drupal).

Este punto lo logre hacer, sin embargo, presente problemas al validar los inputs, por lo que no opte por dejar la configuración de #ajax.



Para validar el funcionamiento del modulo puede entrar a la dirección https://estrenar-vivienda.sebastianzapateiro.tech

