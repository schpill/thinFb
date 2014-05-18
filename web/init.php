<?php
    if (version_compare(PHP_VERSION, '5.4.0', "<")) {
        throw new Exception('You need at least PHP 5.4.0 version to use this framework.');
    }

    date_default_timezone_set('Europe/Paris');
    $siteName = 'project';

    // Define path to application directory
    defined('SITE_NAME')        || define('SITE_NAME',          $siteName);
    defined('APPLICATION_PATH') || define('APPLICATION_PATH',   realpath(dirname(__FILE__) . '/../application'));
    defined('CONFIG_PATH')      || define('CONFIG_PATH',        realpath(dirname(__FILE__) . '/../application/config'));
    defined('CACHE_PATH')       || define('CACHE_PATH',         realpath(dirname(__FILE__) . '/../storage/cache'));
    defined('LOGS_PATH')        || define('LOGS_PATH',          realpath(dirname(__FILE__) . '/../storage/logs'));
    defined('TMP_PATH')         || define('TMP_PATH',           realpath(dirname(__FILE__) . '/../storage/tmp'));
    defined('STORAGE_DIR')      || define('STORAGE_DIR',        realpath(dirname(__FILE__) . '/../storage'));

    // Define path to libs directory
    defined('LIBRARIES_PATH')   || define('LIBRARIES_PATH', realpath(dirname(__FILE__) . '/../src'));

    // Define application environment
    defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    define('DS', DIRECTORY_SEPARATOR);
    define('PS', PATH_SEPARATOR);

    define('STORAGE_PATH', STORAGE_DIR . DS . SITE_NAME);

    // Ensure library/ is on include_path
    set_include_path(implode(PS, array(
        LIBRARIES_PATH,
        get_include_path()
    )));

    $debug = 'production' != APPLICATION_ENV;

    require_once 'Thin/Loader.php';

    if (!is_dir(STORAGE_DIR . DS . SITE_NAME)) {
        $createDir = mkdir(STORAGE_DIR . DS . SITE_NAME, 0755);
        if (!$createDir) {
            throw new Exception("You must give 755 rights to " . STORAGE_DIR);
        }
        $file = STORAGE_DIR . DS . SITE_NAME . DS . time();
        $createFile = touch($file);
        if (!$createFile) {
            throw new Exception("You must give 755 rights to " . STORAGE_DIR . DS . SITE_NAME);
        }
    }
