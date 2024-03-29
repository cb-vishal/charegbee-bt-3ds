<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbf98fa64baa4b2ff31f7499a6528cc5b
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Braintree\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Braintree\\' => 
        array (
            0 => __DIR__ . '/..' . '/braintree/braintree_php/lib/Braintree',
        ),
    );

    public static $prefixesPsr0 = array (
        'B' => 
        array (
            'Braintree' => 
            array (
                0 => __DIR__ . '/..' . '/braintree/braintree_php/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbf98fa64baa4b2ff31f7499a6528cc5b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbf98fa64baa4b2ff31f7499a6528cc5b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbf98fa64baa4b2ff31f7499a6528cc5b::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
