<?php

class ProfileBasedPipeline
{
    private $processors = array();
    private $profiles = array();
    private $numResults = 10;
    private $exclusionList = array();
    
    function __construct($profiles, $numResults, $exclustionList)
    {
        $this->profiles = $profiles;
        $this->numResults = $numResults;
        $this->exclusionList = $exclustionList;
    }
    
    public function addProcessor(AbstractProfileBased $processor)
    {
        $this->processors[] = $processor;
    }
    
    public function execute()
    {
        $finalResults = array();
		foreach($this->processors as $processor) {
            /**
             * Modify params based on results computed so far
             */
            $numResults = $this->numResults - count($finalResults);
            $exclusionList = $this->exclusionList;
            foreach ($finalResults as $result) {
                $exclusionList[] = $result['instituteId'];
            }
            /**
             * Process on current processor
             */
			$results = $processor->process($this->profiles, $numResults, $exclusionList);

            /**
             * Merge results with final results
             */
            $finalResults = array_merge($finalResults, $results);
            if(count($finalResults) >= $this->numResults) {
                break;
            }
		}
        
        return array_slice($finalResults, 0, $this->numResults);
    }
}