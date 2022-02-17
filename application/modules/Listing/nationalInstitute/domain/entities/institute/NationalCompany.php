<?php

class NationalCompany{

	private $company_id;
	private $company_name;
	private $company_logo;
	private $order;

	function __set($property,$value)
	{
		$this->$property = $value;
	}
}
?>