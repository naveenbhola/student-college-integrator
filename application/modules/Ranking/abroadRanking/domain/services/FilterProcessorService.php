<?php

class FilterProcessorService
{
    private $defaultSpecifications;
    private $specificationQuery;
    private $numOfAbroadCourses;
	private $snapshotSpecificationQuery;

    function __construct($specifications,$specificationQuery,$snapshotSpecificationQuery)
    {
        $this->defaultSpecifications = $specifications;
        $this->specificationQuery = $specificationQuery;
		$this->snapshotSpecificationQuery = $snapshotSpecificationQuery;
    }

    public function processFilters($institutes,$filtersToBeApplied)
    {
        $specification = $this->_getCompositeSpecification($filtersToBeApplied);
        foreach($institutes as $instituteId => $courses) {
            foreach($courses as $courseId => $course) {
                $course['filterValues'] = unserialize($course['filterValues']);
                if(!$specification->isSatisfiedBy($course)) {
                    unset($institutes[$instituteId][$courseId]);
                }
            }
            if(count($institutes[$instituteId]) == 0) {
                unset($institutes[$instituteId]);
            }
        }
        return $institutes;
    }
    
    private function _getCompositeSpecification($filtersToBeApplied)
    {
        $compositeSpecification = NULL;
        
        $ANDFilterKeys = array_map('trim',explode('AND',$this->specificationQuery));
    
        foreach($ANDFilterKeys as $ANDFilterKey) {
            
            $ORFilterKeys = array_map('trim',explode('OR',trim($ANDFilterKey,'() ')));
            $specification = NULL;
            
            foreach($ORFilterKeys as $filterKey) {
                if(isset($this->defaultSpecifications[$filterKey]) && isset($filtersToBeApplied[$filterKey]) && count($filtersToBeApplied[$filterKey]) > 0) {
                    $this->defaultSpecifications[$filterKey]->setFilterValues($filtersToBeApplied[$filterKey]);
                    $specification = $specification ? $specification->or_($this->defaultSpecifications[$filterKey]) : $this->defaultSpecifications[$filterKey];
                }
            }
            
            if($specification) {
                $compositeSpecification = $compositeSpecification ? $compositeSpecification->and_($specification) : $specification;
            }
        }
        
        return $compositeSpecification;
    }
	
    
    public function getNoOfAbroadCoursesLeftAfterFilterProcess()
    {
    	return $this->numOfAbroadCourses;
    }
    
    public function processFiltersForAbroadRanking($rankingPageObj,$filtersToBeApplied)
    {
	$rankingDataObj = $rankingPageObj->getRankingPageData();
        $specification = $this->_getCompositeSpecification($filtersToBeApplied);
        $courseCount = 0;
	foreach ( $rankingDataObj as $key=>$tupleobj ) {
		$university = $tupleobj['university'];
		$course = $tupleobj['course'];
		$filters = $tupleobj['filters'];
               
                $courseVal['filterValues'] = unserialize($filters);
		    // remove the course from the list if this course doesn't satisfies the filter value
                    if(!$specification->isSatisfiedBy($courseVal))
                    {
                        unset($rankingDataObj[$key]);
                    }
                    else {
                    	$courseCount++;
                    }

		}
       $this->numOfAbroadCourses = $courseCount;
       $rankingPageObj->setRankingPageData($rankingDataObj);
       return $rankingPageObj;
    }
}