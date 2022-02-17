<?php

interface FinderInterface {
	
	public function getData($id = null);
	
	public function preprocessRawData($data = array());
	
}

