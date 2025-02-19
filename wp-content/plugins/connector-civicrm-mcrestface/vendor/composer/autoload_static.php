<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7fd6297525d6f796fbab979fd70c9fd4
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'CMRF' => 
            array (
                0 => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core',
            ),
        ),
    );

    public static $classMap = array (
        'CMRF\\Connection\\Curl' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Connection/Curl.php',
        'CMRF\\Connection\\CurlAuthX' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Connection/CurlAuthX.php',
        'CMRF\\Connection\\Local' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Connection/Local.php',
        'CMRF\\Core\\AbstractCall' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Core/AbstractCall.php',
        'CMRF\\Core\\Call' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Core/Call.php',
        'CMRF\\Core\\Connection' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Core/Connection.php',
        'CMRF\\Core\\Core' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/Core/Core.php',
        'CMRF\\PersistenceLayer\\CallFactory' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/PersistenceLayer/CallFactory.php',
        'CMRF\\PersistenceLayer\\SQLPersistingCallFactory' => __DIR__ . '/..' . '/civimrf/cmrf_abstract_core/CMRF/PersistenceLayer/SQLPersistingCallFactory.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit7fd6297525d6f796fbab979fd70c9fd4::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit7fd6297525d6f796fbab979fd70c9fd4::$classMap;

        }, null, ClassLoader::class);
    }
}
