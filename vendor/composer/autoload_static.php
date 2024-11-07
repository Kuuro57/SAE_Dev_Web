<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc50cf7de30c03260a957bc6f7707bd57
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\sae_dev_web\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\sae_dev_web\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc50cf7de30c03260a957bc6f7707bd57::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc50cf7de30c03260a957bc6f7707bd57::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc50cf7de30c03260a957bc6f7707bd57::$classMap;

        }, null, ClassLoader::class);
    }
}