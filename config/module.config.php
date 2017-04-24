<?php
namespace ZFMigrate;

return [
    'console' => array(
        'router' => array(
            'routes' => array(
                'migrate-database' => array(
                    'options' => array(
                        'route'    => 'migrate database',
                        'defaults' => array(
                            'controller' => Controller\DatabaseController::class,
                            'action'     => 'migrate'
                        )
                    )
                )
            )
        )
    ),
];