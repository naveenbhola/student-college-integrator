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

    public function processFiltersForAbroad($universitiesObj,$filtersToBeApplied)
    {
        $specification = $this->_getCompositeSpecification($filtersToBeApplied);
        $courseCount = 0;
        // get the universities
        $universities = $universitiesObj["universities_obj"];
        foreach($universities as $universityId => $university)
        {
            $courses = $university->getCourses();
			foreach($courses as $courseId => $course)
			{
				$courseId = $course->getId();
				$courseVal['filterValues'] = unserialize($universitiesObj['universities_array'][$universityId][$courseId]['filterValues']);
				// remove the course from the list if this course doesn't satisfies the filter value
				if(!$specification->isSatisfiedBy($courseVal))
				{
					$university->removeCourse($courseId);
				}
				else {
					$courseCount++;
				}
			}
            
            // remove the university from the list if no courses exist within it anymore
            if(!$university->getCourses()){
                unset($universitiesObj['universities_obj'][$universityId]);
                unset($universitiesObj['universities_array'][$universityId]);
            }

        }
        $this->numOfAbroadCourses = $courseCount; // This is now the number of all courses!
        return array('universities'=>$universitiesObj);
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
}