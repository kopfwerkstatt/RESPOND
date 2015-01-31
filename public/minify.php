<?php
/*
 * Minify
 * CSS and JavaScript minifier
 * Examples how to minify your
 * CSS and JS Files
 */

// include Minify configuration
include 'config/minifier_config.php';

echo '================ Minifying Files ================<br>';

/*

    // JavaScript Example
    minifyJS('animation.js');

    // CSS Example
    minifyCSS('unresponsive.css');

    // All JavaScript files Example
    minifyAllJS();

    // All CSS files Example
    minifyAllCSS();

*/


// All CSS & JS files Example
minifyAll();


echo '<br><a href="../public">Bring Me Back</a>';