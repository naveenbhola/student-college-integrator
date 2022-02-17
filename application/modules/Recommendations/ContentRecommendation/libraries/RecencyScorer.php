<?php

class RecencyScorer
{
    private $CI;
    public $halfLife;
    
    public function __construct($params)
    {
        $this->CI = & get_instance();
        $this->halfLife = is_numeric($params)?$params:86400*30;
    }

    function score($lastActivityTime)
    {
        $currentTime = strtotime('NOW');
        $timeElapsed = $currentTime - strtotime($lastActivityTime);
        
		if($timeElapsed <= 0) {
			return 0;
		}
        
		$exponentFactor = log(2) * ($timeElapsed / $this->halfLife);
		$score = (1 / exp($exponentFactor));
		return $score;
    }
}