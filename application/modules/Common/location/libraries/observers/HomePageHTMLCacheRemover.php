<?php

class HomePageHTMLCacheRemover
{
    public function update()
    {
        $this->removeHomepageHTMLCache(); 
    }
    
    public function removeHomepageHTMLCache()
    {
        $dir = FCPATH.'system/cache/';
        if($dh = opendir($dir)){
            while(($file = readdir($dh))!== false){
                if(file_exists($dir.$file)) @unlink($dir.$file);
            }
            closedir($dh);
        }
        
        $dir = FCPATH.'HomePageRedesignCache/';
        if($dh = opendir($dir)){
            while(($file = readdir($dh))!== false){
                if(file_exists($dir.$file)) @unlink($dir.$file);
            }
            closedir($dh);
        }   
    }
}