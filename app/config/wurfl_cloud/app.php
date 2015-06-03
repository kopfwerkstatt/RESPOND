<?php

class Wurfl_Cloud
{

    /**
     * $api_key
     */
    protected $api_key;

    /**
     * $config:array
     */
    protected $config;

    /**
     * $wurfl_config:object WURFL_Configuration_InMemoryConfig
     */
    protected $wurfl_config;

    /**
     * $wurfl_device:object WURFL_Client
     */
    protected $wurfl_client;

    /**
     * $fallback_view
     */
    protected $fallback;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = CONFIGURATION_DIR . 'wurfl_cloud/';
        $this->config['MOD_NAME'] = 'wurfl_cloud';
        $this->config['WURFL_CLOUD_LIB_DIR'] = LIBRARIES_DIR . 'WurflCloud/';
        $this->config['WURFL_CLOUD_CLIENT_DIR'] = $this->config['WURFL_CLOUD_LIB_DIR'] . 'Client/';
        $this->config['WURFL_CLOUD_CLIENT_FILE'] = $this->config['WURFL_CLOUD_CLIENT_DIR'] . 'Client.php';

        require CONFIGURATION_DIR . "/configuration.php";
        //echo $wurfl_cloud_api_key;
        $this->api_key = $wurfl_cloud_api_key;
        $this->fallback = $fallback_view;
    }

    function __construct()
    {
        $this->fillConfigArray();
        $this->createWurflObject();
    }

    /*
     * create Wurfl Object
     */
    function createWurflObject()
    {
        require_once $this->config['WURFL_CLOUD_CLIENT_FILE'];
        // Additional configuration options can be used here
        $this->wurfl_config = new WurflCloud_Client_Config();
        $this->wurfl_config->api_key = $this->api_key;
    }

    /*
     * return Device Class Name
     */
    function getDeviceClass()
    {
        // These two lines setup the WurflCloud_Client and do the device detection
        $this->wurfl_client = new WurflCloud_Client_Client($this->wurfl_config);
        $this->wurfl_client->detectDevice();

        $width = 1440;

        if($this->wurfl_client->getDeviceCapability('max_image_width')) {
            $width = $this->wurfl_client->getDeviceCapability('max_image_width');

            if ($this->wurfl_client->getDeviceCapability('ux_full_desktop')) {
                $width = 1440;
            }
        }

        // Use the capabilities
        if ($this->wurfl_client->getDeviceCapability('is_tablet')) {
            // Dispatch HTTP request to tablet view
            return array('device' => 'tablet', 'screen_width' => $width);

        } else {
            if ($this->wurfl_client->getDeviceCapability('is_wireless_device')) {
                // Dispatch HTTP request to mobile view
                return array('device' => 'mobile', 'screen_width' => $width);

            } else {
                if ($this->wurfl_client->getDeviceCapability('ux_full_desktop')) {
                    // time to handle desktop devices
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
