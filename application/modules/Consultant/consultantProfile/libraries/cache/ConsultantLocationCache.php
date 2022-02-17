<?php

class ConsultantLocationCache extends Cache
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * function to get consultant Object from cache
	 */ 
	public function getConsultantLocation($consultantId)
	{
		$consultantLocationObject = $this->get('AbroadConsultantLocation',$consultantId);
		return $consultantLocationObject;
	}
	public function getMultipleConsultantsLocation($consultantIds){
		$consultantLocationObjects =  $this->multiGet('AbroadConsultantLocation',$consultantIds);
		return $consultantLocationObjects;
	}
	
	/*
	 * function to store consultant Object to cache
	 */
	public function storeConsultantLocation($consultantId,$consultantLocationObject)
	{
		$this->store('AbroadConsultantLocation',$consultantId, $consultantLocationObject,-1, NULL, 1);
	}
	
	/*
	 * delete consultant Object  object from cache (when consultant gets deleted)
	 */
	public function deleteConsultantLocation($consultantId)
	{
		$this->delete('AbroadConsultantLocation',$consultantId);
	}
	
}
