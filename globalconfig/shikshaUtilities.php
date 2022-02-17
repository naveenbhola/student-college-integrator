<?php
function getJsToInclude($jsArray) {
    $returnArr = array();
    foreach ($jsArray as $js) {
        $temp = getJSWithVersion($js);
        if(!empty($temp)){
            $returnArr[] = '//'.JSURL  .'/public/js/'.$temp;
        }
    }
    return $returnArr;
    global $js_revisions;

    $jsToInclude= array();
    $jsSize = 0;
    $jsNameArray = array();
    $jsNameRelatedArray = array();
    $jsAtClient = json_decode($_COOKIE['JSJar'], true);
    global $jsToBeExcluded, $jsRepos,$jsDeleted;
    //print_r($jsToBeExcluded);
    foreach($jsArray as $js) {
        
        $jsOriginal = $js;
        
        if(!in_array($js,$jsToBeExcluded)){
            $js = $js. JSVERSION;
        }
        if(in_array($js,$jsDeleted)){
            continue;
        }
        
        if(isset($js_revisions[$jsOriginal])) {
            $js .= $js_revisions[$jsOriginal];
        }
        
        // Put 1 check for JS Served at client
        if(array_key_exists($js, $jsRepos)) { //If exists in cache
            if(array_key_exists($js,$jsAtClient)) {
                $jsToInclude[] = $jsAtClient[$js];
                continue;
            }
            if($size < SERVE_SIZE) {
                $jsNameArray[] = $js;
                $size += $jsRepos[$js]['size'];
            } else {
                $size = $jsRepos[$js]['size'];
                $jsName = '//'.JSURL  .'/common/jsRepos/'. implode('--',$jsNameArray) .'.js';
                foreach($jsNameArray as $jsEntity) {
                    $jsAtClient[$jsEntity] = $jsName;
                }
                $jsToInclude[] = $jsName;
                $jsNameArray = array();
                $jsNameArray[] = $js;
            }
        } else {
            $jsToInclude[] = '//'.JSURL  .'/public/js/'. $js .'.js';
        }
    }

    if(!empty($jsNameArray)) {
        $jsName = '//'.JSURL  .'/common/jsRepos/'. implode('--',$jsNameArray) .'.js';
        foreach($jsNameArray as $jsEntity) {
            $jsAtClient[$jsEntity] = $jsName;
        }
        $jsToInclude[] = $jsName;
    }
    //error_log('ASHISH:SERVE:'. print_r($jsToInclude, true));

    setcookie('JSJar',json_encode($jsAtClient),time() + 2592000,'/',COOKIEDOMAIN);
    return array_unique($jsToInclude);
}

    function includeJSFiles($page='jquery',$path='shikshaDesktop',$additionalAttributes=array(),$preload=false){
        
        ensureFilledVersionArray($path,'js');
        global $jsFilesVersionArray;

        $shikshaGruntConfig  = json_decode(file_get_contents(FCPATH.'/public/gruntConfig.json'),true);
        $minifiedJS          = $shikshaGruntConfig['mappings'][$page]['minifiedName'];
        $returnJSFILES       = array(); 
        $tag                 ='';
	    $additionalAttributeString = (count($additionalAttributes)>0?implode(' ', $additionalAttributes):'');
        if(!DEBUG_ON){
            foreach ($jsFilesVersionArray[$path] as $key => $value) {
                if($value['originalPath'] == $minifiedJS){
                    $returnJSFILES = $value['versionedPath'];
                }
            }
            if(is_bool($preload) && $preload == true){
                $tag = "<link rel='preload' as='script' href='https://".JSURL."/public/".$shikshaGruntConfig['mappings'][$page]['cwd']."/build/". $returnJSFILES."'>";                
            }else if($returnJSFILES){
                $tag="<script type='text/javascript' ".$additionalAttributeString." src='https://".JSURL."/public/".$shikshaGruntConfig['mappings'][$page]['cwd']."/build/". $returnJSFILES."'></script>";    
            }
        }else{
            foreach ($shikshaGruntConfig['mappings'][$page]['files'] as $key => $jsFiles) {
                if(is_array($shikshaGruntConfig['mappings'][$page]['responsiveFiles']) && in_array($jsFiles, $shikshaGruntConfig['mappings'][$page]['responsiveFiles'])){
                        $tag.="<script type='text/javascript' ".$additionalAttributeString."  src='https://".JSURL."/public/responsiveAssets/js".'/'. $jsFiles."'></script>";
                }else{
                    $tag.="<script type='text/javascript' ".$additionalAttributeString." src='https://".JSURL."/public/".$shikshaGruntConfig['mappings'][$page]['cwd'].'/'. $jsFiles."'></script>";
                }
            }
        }

        return $tag;
    }

    function includeCSSFiles($page='desktopCommon',$path='shikshaDesktop',$preload=false){
        $cssUrl = getCssUrl();
        ensureFilledVersionArray($path,'css');
        global $cssFilesVersionArray;

        $shikshaGruntConfig  = json_decode(file_get_contents(FCPATH.'/public/gruntCssConfig.json'),true);
        $minifiedCSS          = $shikshaGruntConfig['mappings'][$page]['minifiedName'];
        $returnCSSFILES       = array(); 
        $tag                 ='';
        if(!empty($minifiedCSS)){
            if(!DEBUG_ON){
                foreach ($cssFilesVersionArray[$path] as $key => $value) {
                    if($value['originalPath'] == $minifiedCSS){
                        $returnCSSFILES = $value['versionedPath'];
                    }
                }
                if(is_bool($preload) && $preload == true){
                    $tag="<link rel='preload' href='https://".$cssUrl."/public/".$shikshaGruntConfig['mappings'][$page]['cwd']."/build/". $returnCSSFILES."' as='style'>";
                }else if(!empty($returnCSSFILES)){
                    $tag="<link type='text/css' rel='stylesheet' href='https://".$cssUrl."/public/".$shikshaGruntConfig['mappings'][$page]['cwd']."/build/". $returnCSSFILES."'></link>";    
                }
            }else{
                foreach ($shikshaGruntConfig['mappings'][$page]['files'] as $key => $cssFiles) {
                    if(is_array($shikshaGruntConfig['mappings'][$page]['responsiveFiles']) && in_array($cssFiles, $shikshaGruntConfig['mappings'][$page]['responsiveFiles'])){
                        $tag.="<link type='text/css' rel='stylesheet' href='https://".$cssUrl."/public/responsiveAssets/css".'/'. $cssFiles."'></link>";
                    }else{
                        $tag.="<link type='text/css' rel='stylesheet' href='https://".$cssUrl."/public/".$shikshaGruntConfig['mappings'][$page]['cwd'].'/'. $cssFiles."'></link>";                        
                    }
                }
            }
        }

        return $tag;
    }

    function getCssUrl() {
        $cssUrl = CSSURL;
        if($_REQUEST['loadFromStatic']) {
            $cssUrl = JSURL;
        }
        return $cssUrl;
    }
?>