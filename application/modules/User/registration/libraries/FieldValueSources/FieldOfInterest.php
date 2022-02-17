<?php
/**
 * File for Value source for field of interest field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for field of interest field
 */ 
class FieldOfInterest extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        if($params['twoStep'] && $params['registerationDomain']=='studyAbroad') {
            $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
            $abroadCategory = $abroadCommonLib->getAbroadCategories();
            return $abroadCategory;
        }
        
        if($mmpFormId = $params['mmpFormId']) {
			return $this->_getCategoriesInMMPForm($mmpFormId);
		}
        
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder = new \CategoryBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        $categories =  $categoryRepository->getSubCategories(1);
        
        usort($categories, function($category1,$category2) {
            return strcmp($category1->getName(),$category2->getName());    
        });
        
		$form = $params['form'];
		$groupType = $form->getGroupType();
		
        $values = array();
        foreach($categories as $index => $category) {
			/**
			 * For Study Abroad form, do not include Test Preparation
			 */ 
			if(!($groupType == 'studyAbroad' && ($category->isTestPrep() || $category->getId() == 11))) {
				$values[$category->getId()] = $category->getName();
			}
        }
    
        return $values;
    }
    
    /**
     * Function to get the categories in MMP form
     *
     * @param interest $mmpFormId
     *
     */
    private function _getCategoriesInMMPForm($mmpFormId)
    {
        $this->CI->load->model('customizedmmp/customizemmp_model');
		
		$values = array();
		
		$categories = $this->CI->customizemmp_model->getAbroadCategories($mmpFormId);
        foreach($categories as $category) {
            $values[$category['id']] = $category['name'];
        }
		
		return $values;
    }
}