<script>
$j(document).ready(function() {
	<?php
		foreach($courseIdArr as $clientCourseId){
			$pageKey = empty($userComparedData[$clientCourseId]['trackeyId']) ? $compareHomePageKeyId : $userComparedData[$clientCourseId]['trackeyId'];?>
			responseForm.createViewedResponse('<?php echo $clientCourseId;?>', '<?php echo $actionType;?>','<?php echo $pageKey;?>');
		<?php } ?>
});
</script>
