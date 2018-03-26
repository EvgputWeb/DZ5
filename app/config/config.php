<?php


function getAppConfig()
{
    return [
        'db' => [
            'host' => 'localhost',
            'dbname' => 'mvc',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8'
        ],
        'cookieCryptPassword' => 'IuJkLr34Dfb0196',
        'cookieLiveTime' => 120,
        'photosFolder' => APP . '/_photos_',
        'user' => [
            'minLoginLength' => 4,
            'maxLoginLength' => 15,
            'minPasswordLength' => 5,
            'maxPasswordLength' => 20
        ]
    ];
}