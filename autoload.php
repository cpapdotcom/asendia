<?php
/**
 * A package-specific autoloader; modify `$prefixes` to provide a list of
 * namespaces and sources.
 *
 * @license MIT
 *
 * @author Paul M. Jones <pmjones88@gmail.com>
 *
 * @copyright 2015 Paul M. Jones
 *
 * @link http://auraphp.com/
 */
spl_autoload_register(function ($class) {

    // what prefixes should be recognized?
    $prefixes = array(
        'Cpapdotcom\\Asendia\\' => array(
            __DIR__ . '/src/Cpapdotcom/Asendia',
        ),
    );

    // go through the prefixes
    foreach ($prefixes as $prefix => $dirs) {

        // does the requested class match the namespace prefix?
        $prefix_len = strlen($prefix);
        if (substr($class, 0, $prefix_len) !== $prefix) {
            continue;
        }

        // strip the prefix off the class
        $class = substr($class, $prefix_len);

        // a partial filename
        $part = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        // go through the directories to find classes
        foreach ($dirs as $dir) {
            $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
            $file = $dir . DIRECTORY_SEPARATOR . $part;
            if (is_readable($file)) {
                require $file;
                return;
            }
        }
    }
});

