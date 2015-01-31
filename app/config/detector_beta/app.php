<?php

class Detector_Beta
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
    protected $detector_ua;

    /**
     * $fallback_view
     */
    protected $fallback;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = CONFIGURATION_DIR . 'detector/';
        $this->config['MOD_NAME'] = 'detector/';
        $this->config['DETECTOR_API_DIR'] = LIBRARIES_DIR . 'detector/';

        require BASE_PATH . "/configuration.php";
        $this->fallback = $fallback_view;
    }

    function __construct()
    {
        $this->fillConfigArray();
        $this->createDetector();
    }

    /*
     * create Detector Object
     */
    function createDetector()
    {
        require_once($this->config['DETECTOR_API_DIR'] . 'Detector.php'); //load Detector API
        $this->detector_ua =& $ua;
    }

    /*
     * return Device Class Name
     */
    function getDeviceClass()
    {

        $width = 1440;

        if($this->detector_ua->screenattributes->windowWidth) {
            $width = $this->detector_ua->screenattributes->windowWidth;
        }

        if ($this->detector_ua->isTablet) {
            // Dispatch HTTP request to tablet view
            return array('device' => 'tablet', 'screen_width' => $width);

        } else {
            if ($this->detector_ua->isMobile) {
                // time to handle mobile devices
                return array('device' => 'mobile', 'screen_width' => $width);

            } else {
                if ($this->detector_ua->isComputer) {
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

    function getScreenWidth()
    {
        return $this->detector_ua->screenattributes->windowWidth;
    }

    function getDeviceCapability($name)
    {
        if (isset($this->detector_ua->$name)) {
            return $this->detector_ua->$name;
        } else {
            return null;
        }
    }
}
