<?php
class SingleBreadcrumb {
    private $url;
    private $text;
    public function __construct($text = "", $url = "") {
        $this->url = $url;
        $this->text = $text;
    }

    public function getUrl() {
    	return $this->url;
    }

    public function getText() {
    	return $this->text;
    }
}