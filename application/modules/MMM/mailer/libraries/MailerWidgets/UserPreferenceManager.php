<?php

class UserPreferenceManager
{
    private $userInfoModel;
    
    function __construct(UserInfoModel $userInfoModel)
    {
        $this->userInfoModel = $userInfoModel;
    }
    
    /**
	 * Get desired course, category and subcategory data for users
	 * If user made a response, get these from course on which last response was made
	 * Else if user is a lead, get these from profile
	 * Else if user asked a question, get these (only cat/subcat in this case) from last question asked
	 */ 
	public function getDesiredCourseAndCategoryPref($users)
	{
		$prefForResponseUsers = array();
		$prefForLeadUsers = array();
		$prefForQnAUsers = array();
		
		$prefForResponseUsers = $this->userInfoModel->getDesiredCourseFromLastResponse($users);
		
		$responseUsers = array_keys($prefForResponseUsers);
		$remainingUsers = array_diff($users,$responseUsers);
		if(count($remainingUsers) > 0) { 
			$prefForLeadUsers = $this->userInfoModel->getDesiredCourseFromProfile($remainingUsers);
			$leadUsers = array_keys($prefForLeadUsers);
			$remainingUsers = array_diff($users,$responseUsers,$leadUsers);
		}
		
		if(count($remainingUsers) > 0) {
			$prefForQnAUsers = $this->userInfoModel->getCategoryFromLastQuestionAsked($remainingUsers);	
		}
		
		return $prefForResponseUsers+$prefForLeadUsers+$prefForQnAUsers;
	}
	
	/**
	 * Get desired country data for users
	 * If user made a response, get this from course on which last response was made
	 * Else if user is a lead, get this from profile
	 * Else if user asked a question, get this from last question asked
	 */ 
	public function getDesiredCountryPref($users)
	{
		$prefForResponseUsers = array();
		$prefForLeadUsers = array();
		$prefForQnAUsers = array();
		
		$prefForResponseUsers = $this->userInfoModel->getDesiredCountriesFromLastResponse($users);
		
		$responseUsers = array_keys($prefForResponseUsers);
		$remainingUsers = array_diff($users,$responseUsers);
		if(count($remainingUsers) > 0) {
			$prefForLeadUsers = $this->userInfoModel->getDesiredCountriesFromProfile($remainingUsers);
			$leadUsers = array_keys($prefForLeadUsers);
			$remainingUsers = array_diff($users,$responseUsers,$leadUsers);
		}
		
		if(count($remainingUsers) > 0) {
			$prefForQnAUsers = $this->userInfoModel->getCountriesFromLastQuestionAsked($remainingUsers);	
		}
		
		return $prefForResponseUsers+$prefForLeadUsers+$prefForQnAUsers;
	}
}