<?php
/*
 * Mustache is a simple, logic-less template engine
 */

// include Framework autoload and mustache configuration
include 'config/autoload.php';
include 'config/mustache_config.php';

// render index.html template
echo $mustache->render('index', $data);
