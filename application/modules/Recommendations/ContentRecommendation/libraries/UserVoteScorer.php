<?php
/**
 * UserVoteScorer Library Class
 *
 *
 * @package     ContentRecommendation
 * @subpackage  Libraries
 * @description  Calculates a score between 0 and 1 for a given number of upvotes and down votes using 
 *    Lower bound of Wilson score confidence interval for a Bernoulli parameter. The formula applied is
 *  ($positiveFraction + $z*$z/(2*$n) - $z * sqrt(($positiveFraction*(1-$positiveFraction)+$z*$z/(4*$n))/$n))/(1+$z*$z/$n),
 *  where z is 95% confidence interval, (1-α/2) quantile of the standard normal distribution = 1.96
 *
 */
class UserVoteScorer
{
    private $CI;
    private $quantile;
    
    public function __construct($params)
    {
        $this->CI = & get_instance();
        $this->quantile = is_numeric($params)?$params:1.96; 
    }

    function score($item)
    {
        $n = ($item['helpfulFlagCount']+$item['notHelpfulFlagCount']);
        $positiveFraction = $item['helpfulFlagCount']/$n;

        $temp = $this->quantile*$this->quantile/(2*$n);

        $score = ($positiveFraction + $temp - $this->quantile * sqrt(($positiveFraction*(1-$positiveFraction)+$temp/2)/$n))/(1+2*$temp);
        return $score;
    }
}