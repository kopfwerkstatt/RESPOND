<?php

class Device_Atlas_Cloud
{

    /**
     * $config:array
     */
    protected $config;

    /**
     * $deviceAtlas:object DeviceAtlas_Client
     */
    protected $deviceAtlas_client;

    /**
     * $fallback_view
     */
    protected $fallback;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = CONFIGURATION_DIR . 'device_atlas_cloud/';
        $this->config['MOD_NAME'] = 'device_atlas_cloud';
        $this->config['DEVICE_ATLAS_CLOUD_LIB_DIR'] = LIBRARIES_DIR . 'DeviceAtlasCloud/';
        $this->config['DEVICE_ATLAS_CLOUD_CLIENT_FILE'] = $this->config['DEVICE_ATLAS_CLOUD_LIB_DIR'] . 'Client.php';

        require BASE_PATH . "/configuration.php";
        $this->fallback = $fallback_view;
    }

    function __construct()
    {
        $this->fillConfigArray();
    }

    /*
     * return Device Class Name
     */
    function getDeviceClass()
    {
        require_once $this->config['DEVICE_ATLAS_CLOUD_CLIENT_FILE'];
        //  setup the DeviceAtlasCloud_Client and do the device detection
        $this->deviceAtlas_client = new DeviceAtlasCloudClient;

        // Call static method and get back the device properties
        $results = $this->deviceAtlas_client->getDeviceData();
        $properties = $results[DeviceAtlasCloudClient::KEY_PROPERTIES];
        $width = 1440;

        if (isset($properties['usableDisplayWidth']) && $properties["usableDisplayWidth"]) {
            $width = $properties["usableDisplayWidth"];
        }

        if (isset($properties['isTablet']) && $properties["isTablet"]) {
            // Dispatch HTTP request to tablet view
            return array('device' => 'tablet', 'screen_width' => $width);

        } else {
            if (isset($properties['mobileDevice']) && $properties["mobileDevice"]) {
                // time to handle mobile devices
                return array('device' => 'mobile', 'screen_width' => $width);

            } else {
                if (isset($properties['isBrowser']) && $properties["isBrowser"]) {
                    // Dispatch HTTP request to desktop view
                    return array('device' => 'desktop', 'screen_width' => $width);

                } else {
                    if ($this->fallback == 3) {
                        return array('device' => 'mobile', 'screen_width' => $width);
                    } else if ($this->fallback == 2) {
                        return array('device' => 'tablet', 'screen_width' => $width);
                    } else {
                        return array('device' => 'desktop', 'screen_width' => $width);
                    }
                }
            }
        }
    }
}
