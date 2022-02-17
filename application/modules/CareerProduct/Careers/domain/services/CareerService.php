<?php
class CareerService
{
    private $_careerRepository;
    
    function __construct(CareerRepository $careerRepository)
    {
        $this->_careerRepository = $careerRepository;
        
    }
    
    /*public function getRecommendedCareers($expressInterestFirst,$expressInterestSecond,$stream,$careerId)
    { 
        $res = $this->_careerRepository->getRecommendedCareers($expressInterestFirst,$expressInterestSecond,$stream,$careerId);
	return $res;
    }*/
	
    public function getRecommendedCareers($careerId)
    { 
        $res = $this->_careerRepository->getRecommendedCareers($careerId);
	return $res;
    }

    public function getSuggestedCareers($expressInterestFirst,$expressInterestSecond,$stream)
    { 
        $res = $this->_careerRepository->getSuggestedCareers($expressInterestFirst,$expressInterestSecond,$stream);
	return $res;
    }

}
?>
