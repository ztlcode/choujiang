<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite9c7820a0c1a2b9d0408cc0f04831bac
{
    public static $files = array (
        '685ba3d03c0a49d7bfd5531cf21197d4' => __DIR__ . '/..' . '/hetao29/slightphp/SlightPHP.php',
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/..' . '/hetao29/slightphp/plugins',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->fallbackDirsPsr4 = ComposerStaticInite9c7820a0c1a2b9d0408cc0f04831bac::$fallbackDirsPsr4;

        }, null, ClassLoader::class);
    }
}
