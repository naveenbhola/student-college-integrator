<?php

class LogLikelihoodSimilarity
{
    /**
     * @var $set1 array of ids
     * @var $set2 array of ids
     * @var $num total no. of items
     */ 
    public function similarity($set1, $set2, $num)
    {
        $set1Size = count($set1);
        $set2Size = count($set2);
        
        $intersectionSize = count(array_intersect($set1, $set2));
        if($intersectionSize == 0) {
            return 0;
        }
        
        /**
         * Both A (set1) & B (set2) occurs
         */ 
        $k11 = $intersectionSize;
        
        /**
         * B occurs without A
         */ 
        $k12 = $set2Size - $intersectionSize;
        
        /**
         * A occurs without B
         */ 
        $k21 = $set1Size - $intersectionSize;
        
        /**
         * Neither A nor B occurs
         */ 
        $k22 = $num - $set1Size - $set2Size + $intersectionSize;
        
        $logLikelihood = $this->logLikelihoodRatio($k11, $k12, $k21, $k22);
        
        return 1.0 - 1.0 / (1.0 + $logLikelihood);
    }

    private function logLikelihoodRatio($k11, $k12, $k21, $k22)
    {        
        $rowEntropy = $this->entropy(array($k11+$k12, $k21+$k22));
        $columnEntropy = $this->entropy(array($k11+$k21, $k12+$k22));
        $matrixEntropy = $this->entropy(array($k11, $k12, $k21, $k22));
        
        if($rowEntropy + $columnEntropy < $matrixEntropy) {
            return 0;
        }
        
        return 2 * ($rowEntropy + $columnEntropy - $matrixEntropy);
    }
    
    private function entropy($elements)
    {
        $sum = 0;
        $result = 0;
        
        foreach($elements as $element) {
            $result += $this->xLogx($element);
            $sum += $element;
        }
        
        return $this->xLogx($sum) - $result;
    }
    
    private function xLogx($x)
    {
        return $x == 0 ? 0 : $x * log($x);
    }
}
