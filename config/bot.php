<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Включить/Отключить бот
    |--------------------------------------------------------------------------
    |
    | Опция показивает запущен бот или нет
    | Supported: "true", "false"
    |
    */

    'trio' => [

        // Включить/Отключить бот
        'go' => false,

        // Интервал по умолчанию между обновленями статистики
        'timeout' => 60, // minute

        // Коммисия по умолчанию
        'fee' => 0.002, // 0.2 %

        // Минимальний процент при коотором торгуем
        'min_profit' => 1, // %

    ],

    'inter' => [

        // Включить/Отключить бот
        'go' => true,

        // Интервал по умолчанию между обновленями статистики
        'timeout' => 60, // minute


    ]








];
