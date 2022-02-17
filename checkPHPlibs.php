<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
ini_set('log_errors', 0);
ini_set('html_errors', 0);

if (php_sapi_name() != 'cli') {
    die('Must run from command line');
}

if (strnatcmp(phpversion(),'5.3.0') >= 0) 
{ 
    echo "\ncheck !!! PHP version is 5.3.0\n\n"; 
} 
else 
{ 
    die('PHP version must be 5.3.0');
}

include 'PEAR/Registry.php';

$reg = new PEAR_Registry;

echo "PEAR Packages are::\n";
foreach ($reg->listPackages() as $package) {
    print "$package\n";
}

if (in_array("net_gearman",$reg->listPackages())){
    echo "\ncheck !!! PEAR net_gearman is installed\n"; 
} else {
    echo "check !!! PEAR net_gearman is not installed\n"; 
}

if (in_array("pear",$reg->listPackages())){
    echo "check !!! PEAR is installed\n"; 
} else {
    echo "check !!! PEAR is not installed\n"; 
}

if (in_array("apc",get_loaded_extensions())){
    echo "check !!! PHP APC is installed\n"; 
} else {
    echo "check !!! PHP APC is not installed\n"; 
}

if (in_array("memcache",get_loaded_extensions())){
    echo "check !!! PHP memcache is installed\n"; 
} else {
    echo "check !!! PHP memcache is not installed\n"; 
}

/*
if (in_array("apc",get_loaded_extensions())){
    echo "check !!! PHP APC is installed\n"; 
} else {
    echo "check !!! PHP APC is not installed\n"; 
}
*/
// zlib & zip
if(function_exists('gzopen')){
    echo "check !!! PHP Zlib is installed\n"; 
} else {
    echo "check !!! PHP Zlib is not installed\n"; 
}

// zlib & zip
if(function_exists('zip_read')){
    echo "check !!! PHP zip is installed\n"; 
} else {
    echo "check !!! PHP zip is not installed\n"; 
}

// curl
if(function_exists('curl_init')){
    echo "check !!! PHP curl is installed\n"; 
} else {
    echo "check !!! PHP curl is not installed\n"; 
}

// gd with JPEG
if(function_exists('gd_info')){
    echo "check !!! PHP GD is installed\n"; 
}else {
    echo "check !!! PHP GD is not installed\n"; 
}

// gd with JPEG
if(function_exists('imagecreatefromjpeg')){
    echo "check !!! PHP GD jpeg is installed\n"; 
}else {
    echo "check !!! PHP GD jpeg is not installed\n"; 
}

// gd support for freetype
if (function_exists('imagettftext')) {
     echo "check !!! PHP GD free type support is installed\n";
} else {
     echo "check !!! PHP GD free type support is not installed\n"; 
}

// simple xml
if(function_exists('simplexml_load_file')){
    echo "check !!! PHP simplexml is installed\n";
}else{
    echo "check !!! PHP simplexml is not installed\n";
}

// mysqli & pdo_mysql
if(function_exists('mysqli_close')){
    echo "check !!! PHP mysqli is installed\n"; 
}else {
    echo "check !!! PHP mysqli is not installed\n"; 
}

// mysqli & pdo_mysql
if(function_exists('pdo_mysql')){
    echo "check !!! PHP pdo_mysql is installed\n"; 
}else {
    echo "check !!! PHP pdo_mysql is not installed\n"; 
}

$listphpiniextns    = array_map(function($e) { return sprintf("%s (%s)", $e, phpversion($e)); }, get_loaded_extensions());
echo "\nPHP extensions are::\n\n";
foreach ($listphpiniextns as $listphpiniextnsarr) {
    print "$listphpiniextnsarr\n";
}
