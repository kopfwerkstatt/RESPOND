<?php
$config = json_decode(file_get_contents(BASE_PATH.'/config.json'), true);

/*
 * Development Mode ON / OFF
 * Display Errors in Browser:
 */
$dev_mode = $use_bandwidth_detection = $config['configuration']['dev_mode'] == 'true' ? true : false;

/*
 * Responsive Image Resolution Configuration:
 * Number of Image-folders (Resolutions) - should be identical with CSS Breakpoints:
 */
$image_resolutions = [];

foreach ($config['configuration']['image_resolutions'] as $key => $value) {
    array_push($image_resolutions, $value);
}

/*
 * Device Detection Libraries Configuration:
 */

/*
 * Choose Device Detection Library:
 *  # 1: Wurfl OnSite
 *  # 2: Wurfl Cloud
 *  # 3: Device Atlas Cloud
 *  # 4: 51Degrees
 *  # 5: Detector Beta
 */
$device_detection_library = trim($config['configuration']['device_detection_library'],"''");

/*
 * If no matching device is detected - show view:
 *  # 1: desktop
 *  # 2: tablet
 *  # 3: mobile
 */
$fallback_view = trim($config['configuration']['fallback_view'],"''");

/*
 * Use Modernizr Feature Detection
 */
$use_modernizr = $config['configuration']['use_modernizr'] == 'true' ? true : false;

/*
 * Use Bandwidth Detection
 */
$use_bandwidth_detection = $config['configuration']['use_bandwidth_detection'] == 'true' ? true : false;

/*
 * WURFL CLOUD Configuration
 *
 * Required Cloud Capabilities - must be set in WebConfig:
 *  # is_tablet
 *  # is_wireless_device
 *  # ux_full_desktop
 *  # max_image_width
 *
 * Your Cloud Client - API Key:
 */
$wurfl_cloud_api_key = trim($config['configuration']['wurfl_cloud_api_key'],"''");

/*
 * Device Atlas Configuration
 *
 * Cloud Client - API Key:
 * Set in 'app/libs/DeviceAtlasCloud/Client.php' Line: 96
 * const LICENCE_KEY = '...';
 */

/*
 * WURFL.js Configuration
 *
 * Path to WURFL.js
 * Alternate: '/respond/app/libs/JS/wurfl.js'
 */
$path_to_wurfl_js = trim($config['configuration']['path_to_wurfl_js'],"''");