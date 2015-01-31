<?php

class Wurfl_Onsite
{

    /**
     * $config:array
     */
    protected $config;

    /**
     * $wurfl_config:object WURFL_Configuration_InMemoryConfig
     */
    protected $wurfl_config;

    /**
     * $wurfl_manager:object WURFL_WURFLManager
     */
    protected $wurfl_manager;

    /**
     * $wurfl_device:object WURFL_Device
     */
    protected $wurfl_device;

    /**
     * $fallback_view
     */
    protected $fallback;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = CONFIGURATION_DIR . 'wurfl_onsite/';
        $this->config['MOD_NAME'] = 'wurfl_onsite/';
        $this->config['WURFL_API_DIR'] = LIBRARIES_DIR . 'WURFL/';
        $this->config['WURFL_RESOURCES_DIR'] = $this->config['MOD_DIR'] . 'resources/';
        $this->config['WURFL_RESOURCE_FILE'] = $this->config['WURFL_RESOURCES_DIR'] . 'wurfl.zip';
        $this->config['WURFL_STORAGE_DIR'] = $this->config['WURFL_RESOURCES_DIR'] . 'storage/';
        $this->config['WURFL_CACHE_DIR'] = $this->config['WURFL_STORAGE_DIR'] . 'cache/';
        $this->config['WURFL_PERSISTENCE_DIR'] = $this->config['WURFL_STORAGE_DIR'] . 'persistence/';

        require BASE_PATH . "/configuration.php";
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
        require_once($this->config['WURFL_API_DIR'] . 'Application.php'); //load WURFL API
        $this->wurfl_config = new WURFL_Configuration_InMemoryConfig();
        $this->wurfl_config->wurflFile($this->config['WURFL_RESOURCE_FILE']);
        $this->wurfl_config->persistence('file', array('dir' => $this->config['WURFL_PERSISTENCE_DIR']));
        $this->wurfl_config->cache('file', array('dir' => $this->config['WURFL_CACHE_DIR'], 'expiration' => 36000));
        $this->wurfl_config->matchMode('performance'); // Set the match mode for the API ('performance' or 'accuracy')
        $this->wurfl_config->allowReload(true);
        $wurflManagerFactory = new WURFL_WURFLManagerFactory($this->wurfl_config);

        $this->wurfl_manager = $wurflManagerFactory->create();
        $this->wurfl_device = $this->wurfl_manager->getDeviceForHttpRequest($_SERVER);
    }

    /*
     * return Device Class Name
     */
    function getDeviceClass()
    {

        $width = 1440;

        if ($this->wurfl_device->getCapability('max_image_width')) {
            $width = $this->wurfl_device->getCapability('max_image_width');

            if ($this->wurfl_device->getCapability('ux_full_desktop') == 'true') {
                $width = 1440;
            }
        }

        // Note that in the PHP API, all capability values are strings, so you will need to compare them to strings
        if ($this->wurfl_device->getCapability('is_tablet') == 'true') {
            // Dispatch HTTP request to tablet view
            return array('device' => 'tablet', 'screen_width' => $width);

        } else {
            if ($this->wurfl_device->getCapability('is_wireless_device') == 'true') {
                // time to handle mobile devices
                return array('device' => 'mobile', 'screen_width' => $width);

            } else {
                if ($this->wurfl_device->getCapability('ux_full_desktop') == 'true') {
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

    function getDeviceCapability($name)
    {
        return $this->wurfl_device->getCapability($name);
    }

    function getDeviceCapabilitiesList()
    {
        $this->wurfl_device = $this->wurfl_manager->getDeviceForHttpRequest($_SERVER);
    }
}
