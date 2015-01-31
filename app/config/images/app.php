<?php

class Images
{

    /**
     * $config:array
     */
    protected $config;

    /**
     * $resolutions:array of resolutions stops
     */
    protected $resolutions = null;

    /**
     * $current_resolution:cache for current resolution value
     */
    protected $current_resolution = null;

    /**
     * $screen_width:number device screen width
     */
    protected $screen_width = null;

    /**
     * $source_dir:source directory for images
     */
    protected $source_dir = null;

    /*
     * fill the Configuration Array with the right paths
     */
    function fillConfigArray()
    {
        $this->config['MOD_DIR'] = APPLICATION_DIR . 'images/';
        $this->config['MOD_NAME'] = 'images/';
        $this->config['IMG_DIR'] = ASSETS_DIR . 'images/';
    }

    function __construct()
    {
        $this->fillConfigArray();

        if (array_key_exists('RESOLUTIONS', $this->config) && is_array($this->config['RESOLUTIONS']) && !empty($this->config['RESOLUTIONS'])) {
            $this->resolutions = $this->config['RESOLUTIONS'];
        }
        if (array_key_exists('IMG_DIR', $this->config) && !empty($this->config['IMG_DIR'])) {
            $this->source_dir = $this->config['IMG_DIR'];
        }
    }

    /*
     * set Image Directory for source images
     */
    function setSourceDir($dir_name)
    {
        $this->source_dir = $dir_name;
    }

    function getSourceDir()
    {
        return $this->source_dir;
    }

    /*
     * set required Resolutions / Folders
     */
    function setResolutions($resolutions)
    {
        $this->resolutions = $resolutions;
    }

    function setScreenWidth($scr_width)
    {
        $this->screen_width = $scr_width;
    }

    /*
     * return matching Resolutions from Configuration
     */
    function getCurrentResolution()
    {
        if (!is_null($this->current_resolution)) {
            return $this->current_resolution; // we already have it
        }
        if (is_null($this->screen_width)) {
            throw new Exception('first screen width has to be set with $this->setScreenWidth.');
        }
        if (is_null($this->resolutions)) {
            throw new Exception('first resolutions have to be set with $this->setResolutions or via config ["RESOLUTIONS"] .');
        }
        $resolutions = $this->resolutions;

        // Delete Resolutions lower then screen width
        foreach ($resolutions as $k => $val) {
            if ($val < $this->screen_width) {
                unset($resolutions[$k]);
            }
        }
        // if there is no higher resolution in $resolutions, it indicates that we should use the original image files
        if (empty($resolutions)) {
            return 'source';
        } else {
            return min($resolutions);
        }
    }

    /*
     * return Path to image in accurate Resolution
     * if relevant image doesn't exist -> create the resized version
     */
    function getImage($filename)
    {
        if (is_null($this->source_dir)) {
            throw new Exception('first image_dir has to be set with $this->setSourceDir($dir_name) or via config ["IMG_DIR"] .');
        }

        // if no Source-Directory exists -> dirExists() creates it
        if (!$this->dirExists($this->source_dir)) {
            return false;
        }

        $image_source = $this->source_dir . 'source/' . $filename;
        $img_file_exists = file_exists($image_source);
        // source file doesn't exist
        if (!$img_file_exists) {
            throw new Exception('source file does not exists: source/' . $filename);
        }

        // source file exists
        $current_resolution = $this->getCurrentResolution(); // get current Resolution
        // build a file path for the file
        $dir_resolution_base = $this->source_dir . $current_resolution . "/";
        $target_img_file = $dir_resolution_base . $filename;

        // if file exists - create path relative to site root
        if (file_exists($target_img_file)) {
            return '..' . str_replace(BASE_PATH, "", $target_img_file);
        }

        // if source file resolution is smaller than current screen size
        $image_source_size = getimagesize($image_source);
        $target_img_width = $image_source_size[0];

        // we return path to source file
        if (is_numeric($current_resolution) && ((int)$target_img_width <= $current_resolution)) {
            return '..' . str_replace(BASE_PATH, "", $image_source);
        }

        // if file doesn't exist and source image width is larger than current screen size -> try to resize it
        $img_pathinfo = pathinfo($filename); // analyze if $filename contains directories
        if (!empty($img_pathinfo['dirname']) && $img_file_exists) //target directory
        {
            // if $filename contains directories -> we create directory structure in relevant directory
            if (!$this->iteratorDirExists($img_pathinfo['dirname'])) {
                return false; // if there is no way to create dir
            }
        }

        // include library with resize code "SmartResize"
        require_once(LIBRARIES_DIR . "/SmartResize/smart_resize_image.function.php");
        if (is_numeric($current_resolution)) {
            $res = smart_resize_image($image_source, $target_img_file, $current_resolution);
            return '..' . str_replace(BASE_PATH, "", $target_img_file);
        }
        return false;
    }

    /*
     * check if directory already exists - otherwise create it
     */
    function dirExists($dir)
    {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                if (!is_dir($dir)) {
                    throw new Exception('directory: "' . $dir . '" does not exist and could not be created');
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
        return false;
    }

    /*
     * create directory structure in directory
     */
    function iteratorDirExists($dir)
    {
        $current_resolution = $this->getCurrentResolution();
        $dir_resolution_base = $this->source_dir . $current_resolution . "/";

        $dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $dir);
        $dirParts = explode(DIRECTORY_SEPARATOR, $dir);

        $dir_current = $dir_resolution_base;
        for ($i = 0; $i < count($dirParts); $i++) {
            $dir_current .= $dirParts[$i] . "/";
            $this->dirExists($dir_current);
        }

        if (!$this->dirExists($dir_resolution_base)) {
            return false;
        }
        return true;
    }
}
