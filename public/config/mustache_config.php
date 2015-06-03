<?php
/*
 * Mustache template engine configuration file:
 * the code below represents the Mustache engine
 * configuration and connects the Framework with
 * the Mustache template engine functionality
 */

Mustache_Autoloader::register();

// use .html instead of .mustache for default template extension
$options = array('extension' => '.html');

// template and partial - filesystem loader
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(PUBLIC_DIR. '/views', $options),
    'partials_loader' => new Mustache_Loader_FilesystemLoader(PUBLIC_DIR. '/views/partials/' . $GLOBALS['comparedDeviceInformation']['Device Class'], $options),
));

/*
 * Global Data that is required in templates and partials:
 * contains links to switch the view (desktop, tablet, mobile)
 * contains function that returns images in right resolution
 */
$data = array(
    'viewDesktop'      => 'javascript:switchView("desktop")',
    'viewTablet'       => 'javascript:switchView("tablet")',
    'viewMobile'       => 'javascript:switchView("mobile")',
    'viewDetected'     => 'javascript:switchView("detected")',
    'cookieAvailable'  =>  function () {
                                return getDeviceInformationCookie();
                            },
    'DeviceClass'  =>  function () {
                                return getDeviceInformationDeviceClass();
                            },
    'ScreenWidth'  =>  function () {
                                return getDeviceInformationScreenWidth();
                            },
    'Bandwidth'    =>  function () {
                                return getDeviceInformationBandwidth();
                            },
    'img'              =>  function ($filename) {
                                return img($filename);
                            },
    'css'              =>  function ($filename) {
                                return '<link rel="stylesheet" type="text/css" href="'.css($filename).'">';
                            },
    'js'              =>  function ($filename) {
                                return '<script type="text/javascript" src="'.js($filename).'"></script>';
                            },
    'addFrameworkJS'     =>   function () {
                                return addCookieJS();
                            },
);