<?php
/*
 * The code below will read
 * device information from
 * a cookie and compares it with
 * information detected on the
 * server
 */


$cookie = [];
$clientDeviceInformation = ['screen_width' => '', 'device' => ''];
$serverDeviceInformation = ['screen_width' => '', 'device' => ''];
$comparedDeviceInformation = ['Device Detection Library' => $device_detection_library];

// Look for DeviceInformation Cookie stored on PageLoad (Client Side)
if (isset($_COOKIE['DeviceInformation'])) {
    $cookie = $_COOKIE['DeviceInformation'];
    $GLOBALS['comparedDeviceInformation']['Client Cookie'] = 'available';

    // If Cookie exists - explode Information
    if ($cookie) {
        $values = explode('|', $cookie);
        foreach ($values as $value) {
            $capability = explode('.', $value);
            $clientDeviceInformation[$capability[0]] = $capability[1];
            $clientDeviceInformation[$capability[2]] = $capability[3];
        }
    }
    getDeviceInformation($serverDeviceInformation, $clientDeviceInformation, $device_detection_library);

} else {
    // Select Device Detection Library - Perform Server Side Device Detection
    $serverDeviceInformation = selectDeviceDetectionLibrary($device_detection_library);
    $GLOBALS['comparedDeviceInformation']['Client Cookie'] = 'not available';
    getDeviceInformation($serverDeviceInformation, $clientDeviceInformation, $device_detection_library);
}

// Choose Device Detection Library
function selectDeviceDetectionLibrary($device_detection_library)
{
    switch ($device_detection_library) {
        case (1):
            $DDLobject = getDDLobject('wurfl_onsite');
            break;
        case (2):
            $DDLobject = getDDLobject('wurfl_cloud');
            break;
        case (3):
            $DDLobject = getDDLobject('device_atlas_cloud');
            break;
        case (4):
            $DDLobject = getDDLobject('fifty_one_degrees');
            break;
        default:
            $DDLobject = getDDLobject('detector_beta');
            break;
    }
    $serverDeviceInformation = $DDLobject->getDeviceClass();
    return $serverDeviceInformation;
}

// check if deviceInformation in Cookie already exists, otherwise use Information from Device Detection Library
function getDeviceInformation($serverDeviceInformation, $clientDeviceInformation, $device_detection_library)
{
    if ($clientDeviceInformation["device"] == "unknown") {
        $serverDeviceInformation = selectDeviceDetectionLibrary($device_detection_library);
        $GLOBALS['comparedDeviceInformation']['Device Class'] = $serverDeviceInformation['device'];
    } else {
        $GLOBALS['comparedDeviceInformation']['Device Class'] = ($clientDeviceInformation["device"] ? $clientDeviceInformation["device"] : $serverDeviceInformation['device']);
    }
    $GLOBALS['comparedDeviceInformation']['Screen Width'] = ($clientDeviceInformation["screen_width"] ? $clientDeviceInformation["screen_width"] : $serverDeviceInformation['screen_width']);
}


// print the compared Device Information
function printDeviceInformation()
{
    print_r($GLOBALS['comparedDeviceInformation']);
}

// return Cookie information (available / not available)
function getDeviceInformationCookie()
{
    return $GLOBALS['comparedDeviceInformation']['Client Cookie'];
}

// return detected Device Class
function getDeviceInformationDeviceClass()
{
    return $GLOBALS['comparedDeviceInformation']['Device Class'];
}

// return detected Screen Width
function getDeviceInformationScreenWidth()
{
    return $GLOBALS['comparedDeviceInformation']['Screen Width'];
}

// Add Code from Cookie.js File
function getCookieJS($path_to_wurfl_js, $use_modernizr, $use_bandwidth_detection)
{
    // Add WURFL.js
    $code = '<script type="text/javascript" src="'.$path_to_wurfl_js.'"></script>' . "\n";
    // Add Cookie Code
    $code .= '<script type="text/javascript" src="'. '..' .str_replace(BASE_PATH, "",  CONFIGURATION_DIR . 'cookie/cookie.js').'"></script>' . "\n";
    // Add Bandwidth Detection
    if($use_bandwidth_detection) {
        $code .= '<script type="text/javascript" src="'. '..' .str_replace(BASE_PATH, "",  CONFIGURATION_DIR . 'cookie/bandwidth.js').'"></script>' . "\n";
    }
    // Add Modernizr
    if($use_modernizr) {
        $code .= '<script type="text/javascript" src="'. '..' .str_replace(BASE_PATH, "", LIBRARIES_DIR . 'JS/modernizr.min.js').'"></script>' . "\n";
    }
    return $code;
}

// Code for Bandwidth Detection
// Look for a Bandwidth Cookie stored on PageLoad (Client Side) and explode Information
if($use_bandwidth_detection) {
    $bandwidth = [];
    if (isset($_COOKIE['Bandwidth'])) {
        $bandwidth = $_COOKIE['Bandwidth'];
    }
}

// return detected Bandwidth
function getDeviceInformationBandwidth() {
    if(!$GLOBALS['use_bandwidth_detection']) {
        return 'not active';
    } else if($GLOBALS['use_bandwidth_detection'] && $GLOBALS['bandwidth'] == 'low' || $GLOBALS['use_bandwidth_detection'] && $GLOBALS['bandwidth'] == 'high') {
        return $GLOBALS['bandwidth'];
    } else {
        return 'not available';
    }
}