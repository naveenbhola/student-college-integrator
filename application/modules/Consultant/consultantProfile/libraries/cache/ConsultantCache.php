<?php

class ConsultantCache extends Cache
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * function to get consultant Object from cache
	 */ 
	public function getConsultant($consultantId)
	{
		$consultantObject = $this->get('AbroadConsultant',$consultantId);
		return $consultantObject;
	}
	public function getMultipleConsultants($consultantIds){
		$consultantObjects =  $this->multiGet('AbroadConsultant',$consultantIds);
		return $consultantObjects;
	}
	
	/*
	 * function to store consultant Object to cache
	 */
	public function storeConsultant($consultantId,$consultantObject)
	{
		$this->store('AbroadConsultant',$consultantId, $consultantObject,-1, NULL, 1);
	}
	
	/*
	 * delete consultant Object  object from cache (when consultant gets deleted)
	 */
	public function deleteConsultant($consultantId)
	{
		$this->delete('AbroadConsultant',$consultantId);
	}
	
}
