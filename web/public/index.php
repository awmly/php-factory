<?php

// Set paths
define('PATH_BASE',         __DIR__ . '/../');
define('PATH_LIB',          __DIR__ . '/../lib/');

// Init autoloaders
include(PATH_LIB . 'vendor/autoload.php');
include(PATH_LIB . 'STAN/Autoload.php');
\STAN\Autoload::Init();

// Dummy config
$config = [
    'services' => [
        'email'     => 'Services\Email\PHPMailer',
        'storage'   => 'Services\Storage\Local'
    ]
];

// Init factory
$factory = new \STAN\Factory\Factory();

// Init email service via factory
$email = $factory->build($config['services']['email']);

// Init storage service via factory
$storage = $factory->build($config['services']['storage']);