<?php

class FileReplaceScript extends MX_Controller {
    function changeNames() {
        $mediaDataPath = '/var/www/html/shiksha/mediadata/videos/';
        $publicPath = '/var/www/html/shiksha/public/RnrCategoryBannerReplace/';
        $extension = '.swf';
        
        $fileNamelist = array(
        '1384433111phpmCpSPR',
        '1384438363phpsrbB65',
        '1396414702phpAWHnL9',
        '1397457359phpIN9gtf',
        '1396874041phpFmvq4p',
        '1397803525phpy65e0l',
        '1397540968phpICZ33W',
        '1398753077phpsVqEzh',
        '1398753062phpSKciW0',
        '1399023350phpCWjHqU',
        '1399359067phpj9zA8x',
        '1399359120phpANfiST',
        '1399359994phpwkDcLJ',
        '1399360058phpLRtZrg',
        '1399360815phphdbozd',
        '1399360864phpwf8yBt',
        '1399360634php0OG5wr',
        '1399360624phpavq2pO',
        '1399529678php2WepJC',
        '1399547460phpHImNWQ',
        '1403248437phplnAnqe',
        '1404113307phphdl63w',
        '1405686509phpk1AfPm',
        '1405686612phpAqABTF',
        '1407241661phpXgjuiK',
        '1407241815phpLzt705',
        '1407241848phpPNlgu5',
        '1407241872php6Tz9Xt',
        '1407241897phpqsnciP',
        '1407241933phpYWTOsg',
        '1407299980phpKY8PlS',
        '1409228129phpEBhQb5'
        );
        
        foreach($fileNamelist as $oldName) {
            rename($mediaDataPath.$oldName.$extension, $mediaDataPath.$oldName."_backup".$extension);
        }
        
        foreach($fileNamelist as $key=>$oldName) {
            copy($publicPath.$oldName.$extension, $mediaDataPath.$oldName.$extension);
        }
    }
}
?>