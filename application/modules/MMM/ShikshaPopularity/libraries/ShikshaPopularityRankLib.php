<?php 

class shikshaPopularityRankLib {

	function getValuesForInsertion($instituteId, $attributeType, $instituteData, $configData) {

		$insertValues = array();
		if(!empty($instituteData)) {
			foreach ($instituteData as $attributeId=>$attributeValues) {

				$rankScoreInstitute = 0;
				$rankScoreInstitute = $this->calculateRank($attributeValues, $configData[$attributeId]);

				$insertValues[] = " ( " . $instituteId . ", '" . $attributeType . "' , " . $attributeId . "," . $attributeValues['pageViews'] . "," . $attributeValues['responseCount'] . "," . $rankScoreInstitute . " ) ";

			}
		}
		return $insertValues;

	}

	function calculateRank($attributeValues,$configurableData = array()) {

		global $parameterFunctionMapping;
		global $rankFormula;

		$formulaToCalculate = $rankFormula;

		// Replace Page Views & ResponseCount
		$paramValues  = array_keys($parameterFunctionMapping);	
		foreach ($paramValues as $param) {
			${$param} = $attributeValues[$param];
			
			$formulaToCalculate = str_replace($param, ${$param}, $formulaToCalculate);
			
		}

		//replace constants

		$variablesToBeReplaced = array('ranklimit', 'rankValue', 'rankWeight');

		if(!empty($configurableData)) {

			$variablesReplacedWith = array($configurableData['rank_limit'], $configurableData['rank_factor'], $configurableData['rank_weight']);

		} else {

			global $ranklimit;
			global $rankValue;
			global $rankWeight;

			$variablesReplacedWith = array($ranklimit, $rankValue, $rankWeight);

		}

		$formulaToCalculate = str_replace($variablesToBeReplaced, $variablesReplacedWith, $formulaToCalculate);

		$rank = eval('return '.$formulaToCalculate.';');				//to convert string into mathematical function

		unset($formulaToCalculate);
		return intval($rank);
	}

}

?>