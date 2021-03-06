<?php

/**
 *  Autoloader.php - autoloader for classes in this project
 */

class Autoloader
{
    public static function loadClass($className)
    {
        $baseUrl = $_SERVER['DOCUMENT_ROOT'];

        // format full class name as directory structure
        $classDirStructure = str_replace('\\', '/', $className);

        $classFilename = $baseUrl.'/lib/'.$classDirStructure.'.php';
        if(file_exists($classFilename))
        {
            require $classFilename;
        }
        else
        {
            error_log('could not load class :: [ '.$classFilename.' ]');
        }
    }
}

// set autoload function in AutoLoad() class
spl_autoload_register(__NAMESPACE__.'\\AutoLoader::loadClass');