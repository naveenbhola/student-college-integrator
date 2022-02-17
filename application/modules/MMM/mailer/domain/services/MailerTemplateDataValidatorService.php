<?php

class MailerTemplateDataValidatorService
{
	public function validate(Mailer $mailer, $data)
	{
		$templateCriteria = $mailer->getTemplateCriteria();
		
		if (!empty($templateCriteria)) {
			return $this->getIntersectSets($templateCriteria, $data);
		}
		unset($templateCriteria);
		unset($data);
		return TRUE;
	}

	private function getIntersectSets($templateCriteria, $data)
	{
		$andSets = explode('AND', $templateCriteria);
		
		foreach ($andSets as $andSet) {
			if (!$this->getUnionSets($andSet, $data)) {
				unset($andSets);
				unset($andSet);
				unset($data);
				unset($templateCriteria);
				return FALSE;
			}
		}
		unset($andSets);
		unset($andSet);
		unset($data);
		unset($templateCriteria);
		return TRUE;
	}

	private function getUnionSets($andSet, $data)
	{
		$orSets = explode('OR', $andSet);
		
		$result = FALSE;
		foreach ($orSets as $orSet) {
			$result = !empty($data[trim($orSet)]) || $result;
			if ($result) {
				unset($orSets);
				unset($orSet);
				unset($data);
				unset($andSet);
				return TRUE;
			}
		}
		unset($orSets);
		unset($orSet);
		unset($data);
		unset($andSet);
		return $result;
	}

}
