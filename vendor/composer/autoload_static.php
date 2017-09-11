<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit11d48f0f8c5b2b69474d5065e860447d
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '93e8f3302c89cc6683c1dbd7fd0dd806' => __DIR__ . '/../..' . '/lib/class.phperror.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'server\\' => 7,
        ),
        'r' => 
        array (
            'robot\\' => 6,
        ),
        'l' => 
        array (
            'lib\\' => 4,
        ),
        'c' => 
        array (
            'client\\' => 7,
        ),
        'Z' => 
        array (
            'Zend\\EventManager\\' => 18,
            'Zend\\Code\\' => 10,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Process\\' => 26,
            'Symfony\\Component\\Debug\\' => 24,
            'Symfony\\Component\\Console\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Protobuf\\Compiler\\' => 18,
            'Protobuf\\' => 9,
            'PhpAmqpLib\\' => 11,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Inflector\\' => 26,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'server\\' => 
        array (
            0 => __DIR__ . '/../..' . '/server',
        ),
        'robot\\' => 
        array (
            0 => __DIR__ . '/../..' . '/robot',
        ),
        'lib\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'client\\' => 
        array (
            0 => __DIR__ . '/../..' . '/client',
        ),
        'Zend\\EventManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-eventmanager/src',
        ),
        'Zend\\Code\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-code/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Symfony\\Component\\Debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/debug',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Protobuf\\Compiler\\' => 
        array (
            0 => __DIR__ . '/..' . '/protobuf-php/protobuf-plugin/src',
        ),
        'Protobuf\\' => 
        array (
            0 => __DIR__ . '/..' . '/protobuf-php/protobuf/src',
        ),
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Doctrine\\Common\\Inflector\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/inflector/lib/Doctrine/Common/Inflector',
        ),
    );

    public static $prefixesPsr0 = array (
        'g' => 
        array (
            'google\\protobuf' => 
            array (
                0 => __DIR__ . '/..' . '/protobuf-php/google-protobuf-proto/src',
            ),
        ),
    );

    public static $classMap = array (
        'Lib_Functions' => __DIR__ . '/../..' . '/lib/lib.functions.php',
        'Lib_Mysqli' => __DIR__ . '/../..' . '/lib/lib.mysqli.php',
        'client\\Client' => __DIR__ . '/../..' . '/client/Client.php',
        'lib\\Functions' => __DIR__ . '/../..' . '/lib/Functions.php',
        'lib\\Log' => __DIR__ . '/../..' . '/lib/Log.php',
        'lib\\httpClient' => __DIR__ . '/../..' . '/lib/httpClient.php',
        'lib\\httpManager' => __DIR__ . '/../..' . '/lib/httpManager.php',
        'mucache' => __DIR__ . '/../..' . '/lib/class.mucache.php',
        'mumongo' => __DIR__ . '/../..' . '/lib/class.mumongo.php',
        'muredis' => __DIR__ . '/../..' . '/lib/class.muredis.php',
        'robot\\Login' => __DIR__ . '/../..' . '/robot/Login.php',
        'robot\\Main' => __DIR__ . '/../..' . '/robot/Main.php',
        'robot\\WebSocket' => __DIR__ . '/../..' . '/robot/WebSocket.php',
        'server\\Server' => __DIR__ . '/../..' . '/server/Server.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit11d48f0f8c5b2b69474d5065e860447d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit11d48f0f8c5b2b69474d5065e860447d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit11d48f0f8c5b2b69474d5065e860447d::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit11d48f0f8c5b2b69474d5065e860447d::$classMap;

        }, null, ClassLoader::class);
    }
}
