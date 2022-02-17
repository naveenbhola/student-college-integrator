<?php
class articleSecurity
{
	//takes array as an input and shows 404 if not an integer
	function checkIntegerParameters($params){
		$flag = 0;
		foreach ($params as $key => $value) {
			if(!preg_match('/^\d+$/',$value)){
				$flag = 1;
				error_log($value." is not an integer");
			}
		}
		if($flag == 1){
			show_404();
		}
		return;
	}
}
?>