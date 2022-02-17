<?php

class BotParentLib{
	
	public function JsonSerialize()
	{
	    $vars = get_object_vars($this);
	    return $vars;
	}
}
	
?>