<?php 
function getETPUrl($entityType, $entityId, $instUnivType = null){
	if($instUnivType)
		$url = SHIKSHA_HOME."/analytics/ShikshaTrends/showETP/".$instUnivType."/".$entityId;
	else
		$url = SHIKSHA_HOME."/analytics/ShikshaTrends/showETP/".$entityType."/".$entityId;

	return $url;
}