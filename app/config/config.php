<?php

return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'mvc',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ],
    'cookie' => [
        'cryptPassword' => 'IuJkLr34Dfb0196',
        'liveTime' => 120
    ],
    'photosFolder' => APP . '/_photos_',
    'user' => [
        'minLoginLength' => 4,
        'maxLoginLength' => 15,
        'minPasswordLength' => 5,
        'maxPasswordLength' => 20
    ],
    'captcha' => [
        'siteKey' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
        'secretKey' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'
    ],
    'smtp' => [
        'host' => "smtp.mail.ru",
        'username' => 'evgputweb_loftschool@mail.ru',
        'password' => 'loftschool_evgputweb',
        'secure' => 'ssl',
        'port' => 465,
        'mail_from' => 'evgputweb_loftschool@mail.ru'
    ]
];
