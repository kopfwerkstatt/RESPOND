<?php
/*
 * Framework Autoloader
 */

/*
 * specify APP Directories
 */
define ("APPLICATION_DIR", str_replace('/config', '', __DIR__) . DIRECTORY_SEPARATOR);
define("BASE_PATH", str_replace('/app/', '', APPLICATION_DIR));
define ("CONFIGURATION_DIR", __DIR__ . DIRECTORY_SEPARATOR);
define ("LIBRARIES_DIR", APPLICATION_DIR . 'libs' . DIRECTORY_SEPARATOR);
define ("PUBLIC_DIR", BASE_PATH . '/public'. '/');
define ("ASSETS_DIR", BASE_PATH . '/assets' . '/');

/*
 * include Configuration
 */
include BASE_PATH . "/configuration.php";

/*
 * Development Mode
 */
if ($dev_mode) {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
}

/*
 * Access DeviceDetection Object
 */
function getDDLobject($library)
{
    switch ($library) {
        case ("wurfl_onsite"):
            require_once CONFIGURATION_DIR . $library . '/app.php';
            $DDLobject = new Wurfl_Onsite();
            return $DDLobject;

        case ("detector_beta"):
            require_once CONFIGURATION_DIR . $library . '/app.php';
            $DDLobject = new Detector_Beta();
            return $DDLobject;

        case ("wurfl_cloud"):
            require_once CONFIGURATION_DIR . $library . '/app.php';
            $DDLobject = new Wurfl_Cloud();
            return $DDLobject;

        case ("device_atlas_cloud"):
            require_once CONFIGURATION_DIR . $library . '/app.php';
            $DDLobject = new Device_Atlas_Cloud();
            return $DDLobject;

        case ("fifty_one_degrees"):
            require_once CONFIGURATION_DIR . $library . '/app.php';
            $DDLobject = new Fifty_One_Degrees();
            return $DDLobject;
    }
}

// Initialize Cookie Storage
require_once 'cookie/cookie.php';
function addCookieJS() {
    return getCookieJS($GLOBALS['path_to_wurfl_js'], $GLOBALS['use_modernizr']);
}

// Initialize Image Resize
require_once CONFIGURATION_DIR . 'images/app.php';
$IMGObject = new Images();
$IMGObject->setResolutions($image_resolutions);
$IMGObject->setScreenWidth($comparedDeviceInformation['Screen Width']);

/*
 * Wrapper Methods for assets:
 * Return path to relevant Image
 */
function img($filename)
{
    global $IMGObject;
    return $IMGObject->getImage($filename);
}
// Return path to CSS file
function css($filename)
{
    return '..' .str_replace(BASE_PATH, "", ASSETS_DIR . 'css/'.$filename);
}
// Return path to JS file
function js($filename)
{
    return '..' .str_replace(BASE_PATH, "", ASSETS_DIR . 'js/'.$filename);
}
