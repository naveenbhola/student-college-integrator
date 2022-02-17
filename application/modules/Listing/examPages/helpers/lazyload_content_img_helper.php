<?php 
if(!function_exists('lazyload_content_image')){
	function lazyload_content_image($html, $isUtf8 = true){
	    if($isUtf8){
	    	$utf8 = '<?xml encoding="UTF-8">';
	    }
	    else{
	    	$utf8 = '';
	    }
		$doc = DOMDocument::loadHTML($utf8.html_entity_decode($html));
	    $images = $doc->getElementsByTagName('img');
	    foreach ($images as $image) {
	        $image->setAttribute('data-original', $image->getAttribute('src'));
	        if($image->hasAttribute('class')){
	            $image->setAttribute('class', $image->getAttribute('class').' lazy');
	        }else{
	            $image->setAttribute('class', 'lazy');
	        }
	        $image->removeAttribute('src');
	    }
	    return $doc->saveHTML();
	}
}