<?php
setcookie("DeviceInformation", "", time() - 3600, "/respond/public/");
setcookie("Bandwidth", "", time() - 3600, "/respond/public/");
?>

<!DOCTYPE html>
<html>
<head>

    <title>RESPOND - Configuration File</title>

    <meta charset="utf-8">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="a Responsive Web Framework for Device Specific User Experience">
    <meta name="author" content="Christoph Pömer">

    <!--
         ___           ___           ___           ___           ___           ___           ___
        /\  \         /\  \         /\  \         /\  \         /\  \         /\__\         /\  \
       /::\  \       /::\  \       /::\  \       /::\  \       /::\  \       /::|  |       /::\  \
      /:/\:\  \     /:/\:\  \     /:/\ \  \     /:/\:\  \     /:/\:\  \     /:|:|  |      /:/\:\  \
     /::\~\:\  \   /::\~\:\  \   _\:\~\ \  \   /::\~\:\  \   /:/  \:\  \   /:/|:|  |__   /:/  \:\__\
    /:/\:\ \:\__\ /:/\:\ \:\__\ /\ \:\ \ \__\ /:/\:\ \:\__\ /:/__/ \:\__\ /:/ |:| /\__\ /:/__/ \:|__|
    \/_|::\/:/  / \:\~\:\ \/__/ \:\ \:\ \/__/ \/__\:\/:/  / \:\  \ /:/  / \/__|:|/:/  / \:\  \ /:/  /
       |:|::/  /   \:\ \:\__\    \:\ \:\__\        \::/  /   \:\  /:/  /      |:/:/  /   \:\  /:/  /
       |:|\/__/     \:\ \/__/     \:\/:/  /         \/__/     \:\/:/  /       |::/  /     \:\/:/  /
       |:|  |        \:\__\        \::/  /                     \::/  /        /:/  /       \::/__/
        \|__|         \/__/         \/__/                       \/__/         \/__/         ~~

    <3 Created with love in Austria - by Christoph Pömer

    -->

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

</head>
<body>

<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // save Configuration to config.json file
    $config['configuration']['dev_mode'] = sanitize($_POST["dev_mode"]);
    if(empty($_POST["image_resolutions"])) {
        $config['configuration']['image_resolutions'] = explode(', ', '1200, 800, 480, 320');
    } else {
        $config['configuration']['image_resolutions'] = explode(', ', $_POST["image_resolutions"]);
    }
    $config['configuration']['device_detection_library'] = sanitize($_POST["device_detection_library"]);
    $config['configuration']['fallback_view'] = sanitize($_POST["fallback_view"]);
    $config['configuration']['use_modernizr'] = sanitize($_POST["use_modernizr"]);
    $config['configuration']['use_bandwidth_detection'] = sanitize($_POST["use_bandwidth_detection"]);
    $config['configuration']['wurfl_cloud_api_key'] = sanitize($_POST["wurfl_cloud_api_key"]);
    if(empty($_POST["path_to_wurfl_js"])) {
        $config['configuration']['path_to_wurfl_js'] = '//wurfl.io/wurfl.js';
    } else {
        $config['configuration']['path_to_wurfl_js'] = sanitize($_POST["path_to_wurfl_js"]);
    }

    file_put_contents("config.json", json_encode($config, TRUE));

    $success = true;
}

// get Configuration from config.json file
$config = json_decode(file_get_contents("config.json"), TRUE);

$dev_mode = $config['configuration']['dev_mode'];
$image_resolutions = '';
foreach ($config['configuration']['image_resolutions'] as $key => $value) {
    $image_resolutions .= $value . ', ';
}
$image_resolutions = trim($image_resolutions, ", ");
$device_detection_library = $config['configuration']['device_detection_library'];
$fallback_view = $config['configuration']['fallback_view'];
$use_modernizr = $config['configuration']['use_modernizr'];
$use_bandwidth_detection = $config['configuration']['use_bandwidth_detection'];
$wurfl_cloud_api_key = $config['configuration']['wurfl_cloud_api_key'];
$path_to_wurfl_js = $config['configuration']['path_to_wurfl_js'];

function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<div class="container">

    <h1><img style="width: 250px"
             src="https://raw.githubusercontent.com/kopfwerkstatt/RESPOND/master/assets/images/480/logo_lg.jpg"
             alt="RESPOND"> - Configuration File</h1>

    <hr>
    <form name="configuration" autocomplete="off" method="post"
          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <div class="row">
            <div class="col-md-2">
                <p>Development Mode:</p>
            </div>
            <div class="col-md-1">
                <input type="radio"
                       name="dev_mode" <?php if (isset($dev_mode) && $dev_mode == "true") echo "checked"; ?>
                       value="true">&nbsp;active
            </div>
            <div class="col-md-2">
                <input type="radio"
                       name="dev_mode" <?php if (isset($dev_mode) && $dev_mode == "false") echo "checked"; ?>
                       value="false">&nbsp;inactive
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Development Mode ON / OFF - Display Errors in Browser</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Bandwidth Detection:</p>
            </div>
            <div class="col-md-1">
                <input type="radio"
                       name="use_bandwidth_detection" <?php if (isset($use_bandwidth_detection) && $use_bandwidth_detection == "true") echo "checked"; ?>
                       value="true"> active
            </div>
            <div class="col-md-2">
                <input type="radio"
                       name="use_bandwidth_detection" <?php if (isset($use_bandwidth_detection) && $use_bandwidth_detection == "false") echo "checked"; ?>
                       value="false"> inactive
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Use Bandwidth Detection to deliver network optimized Images</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Include Modernizr CSS:</p>
            </div>
            <div class="col-md-1">
                <input type="radio"
                       name="use_modernizr" <?php if (isset($use_modernizr) && $use_modernizr == "true") echo "checked"; ?>
                       value="true"> yes
            </div>
            <div class="col-md-2">
                <input type="radio"
                       name="use_modernizr" <?php if (isset($use_modernizr) && $use_modernizr == "false") echo "checked"; ?>
                       value="false"> no
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Include Modernizr JavaScript File to use generated CSS Classes</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Device Detection Library:</p>
            </div>
            <div class="col-md-3">
                <select name="device_detection_library" class="form-control">
                    <option <?php if ($device_detection_library == 1) echo 'selected'; ?> value="1">1 - Wurfl OnSite
                    </option>
                    <option <?php if ($device_detection_library == 2) echo 'selected'; ?> value="2">2 - Wurfl Cloud
                    </option>
                    <option <?php if ($device_detection_library == 3) echo 'selected'; ?> value="3">3 - Device Atlas
                        Cloud
                    </option>
                    <option <?php if ($device_detection_library == 4) echo 'selected'; ?> value="4">4 - 51Degrees
                    </option>
                    <option <?php if ($device_detection_library == 5) echo 'selected'; ?> value="5">5 - Detector Beta
                    </option>
                </select>
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Choose Device Detection Library - See different Licenses on Websites<br>
                Caveat: Device Atlas Configuration - Cloud Client API Key must be set in 'app/libs/DeviceAtlasCloud/Client.php' Line: 96 -> const LICENCE_KEY = '...';</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Fallback View:</p>
            </div>
            <div class="col-md-3">
                <select name="fallback_view" class="form-control">
                    <option <?php if ($fallback_view == 1) echo 'selected'; ?> value="1">1 - Desktop</option>
                    <option <?php if ($fallback_view == 2) echo 'selected'; ?> value="2">2 - Tablet</option>
                    <option <?php if ($fallback_view == 3) echo 'selected'; ?> value="3">3 - Mobile</option>
                </select>
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Select Fallback View - if Device Detection fails, this View is delivered</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Image Resolution Config:</p>
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" name="image_resolutions" value="<?php echo $image_resolutions; ?>">
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Responsive Image Resolution Configuration - Number of Image-folders (Resolutions) -
                    should be
                    identical with CSS Breakpoints -
                    Use Syntax: 1200, 800, 480, 320</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Wurfl Cloud Config:</p>
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" name="wurfl_cloud_api_key" value="<?php echo $wurfl_cloud_api_key; ?>">
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Copy/Paste your Cloud Client API Key<br>Required Cloud Capabilities - must be set in
                    Wurfl
                    Webinterface:<br>
                    <b>is_tablet</b>; <b>is_wireless_device</b>; <b>ux_full_desktop</b>; <b>max_image_width</b></p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <p>Wurfl JS Config:</p>
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" name="path_to_wurfl_js" value="<?php echo $path_to_wurfl_js; ?>">
            </div>
            <div class="col-md-7">
                <p><b>Notice:</b> Path to WURFL.js - Alternate: '/respond/app/libs/JS/wurfl.js'</p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-2">
                <input type="submit" class="btn btn-block btn-success" name="submit" value="Save">
            </div>
        </div>

        <?php if ($success) { ?>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <p><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> <b>Well done!</b>
                            Configuration saved successfully - <a href="public">Go to
                                Website</a></p>
                    </div>
                </div>
            </div>
        <?php }; ?>
    </form>

</div>

</body>
</html>
