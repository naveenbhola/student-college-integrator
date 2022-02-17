<?php

class ConsultantStudentProfileCache extends Cache
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * function to get consultant student profile Object from cache
	 */ 
	public function getConsultantStudentProfile($consultantId)
	{
		$consultantObject = $this->get('AbroadConsultantStudentProfile',$consultantId);
		return $consultantObject;
	}
	public function getMultipleConsultantsStudentProfile($consultantIds){
		$consultantObjects =  $this->multiGet('AbroadConsultantStudentProfile',$consultantIds);
		return $consultantObjects;
	}
	
	/*
	 * function to store consultant student profile  Object to cache
	 */
	public function storeConsultantStudentProfile($consultantId,$studentProfileObject)
	{
		$this->store('AbroadConsultantStudentProfile',$consultantId, $studentProfileObject,-1, NULL, 1);
	}
	
	/*
	 * delete consultant student profile  Object from cache (when consultant student profile  gets deleted)
	 */
	public function deleteConsultantStudentProfile($consultantId)
	{
		$this->delete('AbroadConsultantStudentProfile',$consultantId);
	}
	
}
