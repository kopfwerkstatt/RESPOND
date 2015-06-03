<?php

class Fifty_One_Degrees
{

    /**
     * $config:array
     */
    protected $config;

    /**
     * $fallback_view
     */
    protected $fallback;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = CONFIGURATION_DIR . 'fifty_one_degrees/';
        $this->config['MOD_NAME'] = 'fifty_one_degrees';
        $this->config['51_DEGREES_LIB_DIR'] = LIBRARIES_DIR . '51Degrees/';
        $this->config['51_DEGREES_CLIENT_FILE'] = $this->config['51_DEGREES_LIB_DIR'] . '51Degrees.php';
        $this->config['51_DEGREES_USAGE_FILE'] = $this->config['51_DEGREES_LIB_DIR'] . '51Degrees_usage.php';

        require CONFIGURATION_DIR . "/configuration.php";
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
        require_once $this->config['51_DEGREES_CLIENT_FILE'];
        require_once $this->config['51_DEGREES_USAGE_FILE'];

        $width = 1440;

        if($_51d['ScreenPixelsWidth']) {
            $width = $_51d['ScreenPixelsWidth'];
        }

        if ($_51d['IsMobile'] && $_51d['ScreenPixelsHeight'] >= 1920) {
            // Dispatch HTTP request to tablet view
            return array('device' => 'tablet', 'screen_width' => $width);

        } else {
            if ($_51d['IsMobile']) {
                // time to handle mobile devices
                return array('device' => 'mobile', 'screen_width' => $width);

            } else {
                if ($_51d['IsMobile'] == false && $_51d['PostMessage']) {
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
