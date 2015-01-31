<?php

/*
 * Development Mode ON / OFF
 * Display Errors in Browser:
 */
$dev_mode = true;

/*
 * Responsive Image Resolution Configuration:
 * Number of Image-folders (Resolutions) - should be identical with CSS Breakpoints:
 */
$image_resolutions = [1200, 800, 480, 320];

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
$device_detection_library = 1;

/*
 * If no matching device is detected - show view:
 *  # 1: desktop
 *  # 2: tablet
 *  # 3: mobile
 */
$fallback_view = 1;

/*
 * Use Modernizr Feature Detection
 */
$use_modernizr = false;

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
$wurfl_cloud_api_key = '890829:sN1a7T6Ok8SYo9MBtGyhXv2fibFA3duZ';

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
$path_to_wurfl_js = '//wurfl.io/wurfl.js';

