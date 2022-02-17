<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ABTesting extends MX_Controller{
	private $abProbability;
	private $abProbabilityRange = 100;
	private $abTestName;
	private $abCacheParamKeys;

	function __construct(){
		$this->abTestingCache 	= $this->load->library('common/cache/ABTestingCache');
	}	

	private function _validateInputParams($abProbability , $abTestName){
		if((empty($abProbability) && $abProbability != 0) ||   $abProbability < 0 || $abProbability > $this->abProbabilityRange || empty($abTestName)){
			return -1;
		}
		return 1;
	}

	private function _getVariationWithoutSecondaryRandomization(){
		if($this->abProbability == 0){
			return 0;
		}else if($this->abProbability == 100){
			return 1;
		}
		$currentVariation = 0;
		$ABSessionCount = $this->_incrementCacheValue($this->abCacheParamKeys['abSessionCount']);
		$modulus = $ABSessionCount % $this->abProbabilityRange;
		if($modulus < $this->abProbability){
			$currentVariation = 1;
		}else{
			$currentVariation = 0;
		}
		return $currentVariation;
	}

	public function executeABTesting($abProbability , $abTestName){
		if($_COOKIE[$abTestName] == NULL){
			$abProbability = (int)$abProbability;
			$abProbability =  (int)(($abProbability*$this->abProbabilityRange)/100);
			if($this->_validateInputParams($abProbability , $abTestName) == -1){
				return -1;
			}

			$this->abProbability 	= $abProbability;
			$this->abTestName 		= $abTestName;
			$this->abCacheParamKeys = array(
										'abSessionCount' => $this->abTestName.'_ABSessionCount',
										'abVariationCount' => $this->abTestName.'_ABVariationCount'
									);

			if($abTestName == ABROAD_SIGNLESIGNUPFORM_ABTESTNAME){
				$currentVariation = $this->_getVariationWithoutSecondaryRandomization();
			}else{
				$cacheParams = array();			
				$ABVariationCount = $this->_checkIfParamsExistsInCache($this->abCacheParamKeys['abVariationCount']);
				if($ABVariationCount === false){
					$ABVariationCount = 1;
					$this->_createParamsInCache($this->abCacheParamKeys['abVariationCount'], $ABVariationCount);
					$this->_createParamsInCache($this->abCacheParamKeys['abSessionCount'], $ABVariationCount);
				}

				$cacheParams['ABSessionCount'] = $this->_incrementCacheValue($this->abCacheParamKeys['abSessionCount']);
				$cacheParams['ABVariationCount'] = $ABVariationCount;
				$currentVariation = $this->_getVariation($cacheParams);
			}
			$this->_createCookie($currentVariation);
			return $currentVariation;
        }else{
            return $_COOKIE[$abTestName];
        }
	}

	private function _incrementCacheValue($key){
		return $this->abTestingCache->incrementCacheValue($key);
	}	

	private function _checkIfParamsExistsInCache($key){
		return $this->abTestingCache->getABParams($key);
	}

	private function _createParamsInCache($key, $param){
		$this->abTestingCache->storeABParams($key, $param);
	}

	private function _getVariation($cacheParams){
		$modulus = ($cacheParams['ABSessionCount']-1) % ($this->abProbabilityRange);
		if($cacheParams['ABVariationCount'] >= $this->abProbability+1){
			$currentVariation = 0;
		}else{
			$randomNumber = rand(0,1);
			if($randomNumber == 1){
				$currentVariation = 1;
				$this->_incrementCacheValue($this->abCacheParamKeys['abVariationCount']);
			}else{
				$maxControlCount = $this->abProbabilityRange - $this->abProbability;
				$currentIndex = ($modulus == 0) ? $this->abProbabilityRange : $modulus;
				$currentControlCount = $currentIndex - $cacheParams['ABVariationCount'];
				if($currentControlCount >= $maxControlCount){
					$currentVariation = 1;
					$this->_incrementCacheValue($this->abCacheParamKeys['abVariationCount']);
				}else{
					$currentVariation = 0;
				}
			}
		}

		if($modulus == 0){
			$this->_resetParamsInCache();
		}
		return $currentVariation;
	}

	private function _resetParamsInCache(){
		$this->abTestingCache->storeABParams($this->abCacheParamKeys['abSessionCount'], 1);
		$this->abTestingCache->storeABParams($this->abCacheParamKeys['abVariationCount'], 1);
	}

	private function _createCookie($currentVariation){
		//setcookie($this->abTestName,$currentVariation);
		setcookie($this->abTestName,$currentVariation, 0, '/', COOKIEDOMAIN);
	}
}
?>