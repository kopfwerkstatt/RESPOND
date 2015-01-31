<?php
/*
 * Minify
 * CSS and JavaScript minifier
 * the code below represents Wrapper-
 * Methods for the Minify Library
 */

// include Framework and Composer Autoloader
include 'autoload.php';

use MatthiasMullie\Minify;

// Minify JavaScript
function minifyJS($filename)
{
    $sourcePathJS = js($filename);
    $minifier = new Minify\JS($sourcePathJS);

    $minifiedPath = substr_replace($sourcePathJS, 'min.js', -2, 2);
    // save minified file to disk
    $minifier->minify($minifiedPath);

    echo '<br><b>JavaScript file "' . $filename . '" minified</b><br>Path: ' . $minifiedPath . '<br>';
}

// Minify CSS
function minifyCSS($filename)
{
    $sourcePathCSS = css($filename);
    $minifier = new Minify\CSS($sourcePathCSS);

    $minifiedPath = substr_replace($sourcePathCSS, 'min.css', -3, 3);
    // save minified file to disk
    $minifier->minify($minifiedPath);

    echo '<br><b>CSS file "' . $filename . '" minified</b><br>Path: ' . $minifiedPath . '<br>';
}

// Minify all JavaScript files in assets directory
function minifyAllJS()
{
    $contents = scandir('../assets/js/');
    $bad = array(".", "..", ".DS_Store", "_notes", "Thumbs.db");
    $files = array_diff($contents, $bad);

    foreach ($files as $file) {
        if (!strpos($file, '.min.') !== FALSE) {
            minifyJS($file);
        }
    }
}

// Minify all CSS files in assets directory
function minifyAllCSS()
{
    $contents = scandir('../assets/css/');
    $bad = array(".", "..", ".DS_Store", "_notes", "Thumbs.db");
    $files = array_diff($contents, $bad);

    foreach ($files as $file) {
        if (!strpos($file, '.min.') !== FALSE) {
            minifyCSS($file);
        }
    }
}

// Minify all JS and all CSS files in assets directory
function minifyAll()
{
    minifyAllJS();
    minifyAllCSS();
}