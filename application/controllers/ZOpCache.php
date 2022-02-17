<?php

class ZOpCache extends MX_Controller
{
	function invalidate()
	{
		$file = '/var/www/html/shiksha/'.$_REQUEST['f'];
		if(file_exists($file)) {
			if(opcache_invalidate($file)) {
				echo "DONE";
			}
			else {
				echo "ERROR";
			}
		}
	}
}
