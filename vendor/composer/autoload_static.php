<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9e54c9856df1592af0790cdd23566921
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MoslyApp\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MoslyApp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'MoslyApp\\Controller\\UserController' => __DIR__ . '/../..' . '/src/Controller/User.php',
        'MoslyApp\\Model\\Connection' => __DIR__ . '/../..' . '/src/Model/Connection.php',
        'MoslyApp\\Model\\User' => __DIR__ . '/../..' . '/src/Model/User.php',
        'MoslyApp\\Model\\Validations' => __DIR__ . '/../..' . '/src/Model/Validation.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9e54c9856df1592af0790cdd23566921::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9e54c9856df1592af0790cdd23566921::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9e54c9856df1592af0790cdd23566921::$classMap;

        }, null, ClassLoader::class);
    }
}
