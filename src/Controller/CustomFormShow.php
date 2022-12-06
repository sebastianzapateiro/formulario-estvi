<?php

namespace Drupal\form_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomFormShow extends ControllerBase
{
    /**
     * @var Connection
     */
    private $cn;

    public function __construct(Connection $database)
    {
        $this->cn = $database;
    }

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('database')
        );
    }

    public function getValues()

    {
        $query = $this->cn->select('example_users', 'users');
        $query->fields('users', ['id', 'nombre', 'identificacion', 'fecha_nacimiento', 'cargo_usuario', 'Estado']);
        $valores = $query->execute();
        
        return[
            '#theme' =>'resultado_datos',
            '#datos' => $valores->fetchAll(),
        ];

    }

}
