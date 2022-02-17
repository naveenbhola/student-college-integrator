<?php
class EntityCleanupLib
{
	function removeSpecialCharsAndSpaces($data){
		$returnArr = array();
		foreach ($data as $entityType => $arr) {
			foreach ($arr['actualName'] as $key => $name) {
				$returnArr[$entityType]['modifiedName'][] =  preg_replace('/[^A-Za-z]/', '', trim(strtolower($name)));
			}
		}
		return $returnArr;
	}
}