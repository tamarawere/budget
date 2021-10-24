<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

$injector->share($request);
$injector->alias('Psr\Http\Message\ResponseInterface', 'GuzzleHttp\Psr7\Response');
$injector->alias('Budget\Core\Renderer', 'Budget\Core\TwigRenderer');

/**
 * the GuzzleHttp\Psr7\UploadedFile class requires 
 * initial values. The define method here is used to setup
 * these initial values.
 */
$injector->define('GuzzleHttp\Psr7\UploadedFile', [':streamOrFile' => 'default', ':size' => 0, ':errorStatus' => 0]);
$injector->alias('Psr\Http\Message\UploadedFileInterface', 'GuzzleHttp\Psr7\UploadedFile');

/**
 * this injector delegates the creation of the templating engine to 
 * the anonymous function to reduce complexity
 */
$injector->delegate('Twig\Environment', function () use ($injector) {

    $modules = $GLOBALS['config']['modules'];

    /**
     * for each module installed get the 
     * path for the view folder and 
     * add it as a template location
     */
    $templates = [];

    foreach ($modules as $module) {
        $dir = dirname(__DIR__) . '/' . $module . '/views';
        array_push($templates, $dir);
    }

    try {
        $loader = new FilesystemLoader($templates);
        $twig = new Environment($loader, ['debug' => true, 'charset' => 'utf-8', 'strict_variables' => true]);

        foreach ($modules as $module) {
            $twig->addGlobal($module, $GLOBALS['config']['base_path'] . $module . '/');
        }

        $base = $GLOBALS['config']['base_path'];

        $twig->addGlobal('base_path', $base);

        $twig->addGlobal('catPath', $base.'categories/');
        $twig->addGlobal('dashPath', $base.'dash/');
        
        $twig->addGlobal('template', $GLOBALS['config']['twig']['template']);
        $twig->addGlobal('css', $GLOBALS['config']['twig']['css']);
        $twig->addGlobal('js', $GLOBALS['config']['twig']['js']);
        $twig->addGlobal('img', $GLOBALS['config']['twig']['img']);
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new DebugExtension);
        return $twig;
    } catch (Exception $e) {
        $err = 'Twig Exception - ';
        throw new Exception($err . $e->getMessage());
    }
});
