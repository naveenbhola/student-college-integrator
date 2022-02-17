<?php

class ThreadQualityScorer
{
    private $CI;
    
	private $median = 2.66;
	private $slope0 = 1;
	private $slopeMax = 1;
    
    public function __construct()
    {
        $this->CI = & get_instance();
    }
    
	public function setThreadQualityList($threadQualityList = array())
	{
		$this->computeNormalizationParameters($threadQualityList);
	}
	
	private function computeNormalizationParameters($threadQualityList)
	{
		$this->median = $this->computeMedian($threadQualityList);
		
		$maxValue = max($threadQualityList);
		
		if($this->median != 0) {
            $this->slope0 = 2.94/$this->median; //0.05 at 0
		}
		
		if($maxValue - $this->median != 0) {
			$this->slopeMax = 2.94/($maxValue - $this->median); //0.95 at max
		}
	}
	
	private function computeMedian($items)
	{
		sort($items);
		$length = count($items);
		if($length % 2 == 0) {
			return ($items[$length/2 - 1] + $items[$length/2])/2;
		}
		else {
			return $items[intval($length/2)];
		}
	}
	
    function score($threadQuality)
    {
		$score = 0;
		
        if($threadQuality < $this->median) {
			$score = 1/(1+exp(-1*$this->slope0*($threadQuality - $this->median)));
		}
		else{
			$score = 1/(1+exp(-1*$this->slopeMax*($threadQuality - $this->median)));
		}
		
		return $score;
    }
}