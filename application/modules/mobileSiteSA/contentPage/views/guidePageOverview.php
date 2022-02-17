<?php 
   	$this->load->view('guidePageHeader');
   	echo jsb9recordServerTime('SA_MOB_GUIDEPAGE',1);
    $this->load->view('guidePageContents');
    $this->load->view('guidePageFooter');
?>

<script>
	var contentId 		= '<?php echo $content['data']['content_id']; ?>';
	var contentType 	= '<?php echo $content['data']['type']; ?>';
	var email 		= '<?php echo base64_encode($content['data']['email']); ?>';
	var name 		= '<?php echo base64_encode($content['data']['username']);?>';
	var stripTitle 		= '<?php echo base64_encode($content['data']['strip_title']); ?>';
	var contentUrl 		= '<?php echo base64_encode($content['data']['contentURL']);?>';
    var authorId		= '<?=$content["data"]["created_by"]?>';
	var totalDislikes 	= '<?php echo $content['rating']['totalLikesAnsDisLikes'] - $content['rating']['totalLikes']; ?>';
    var totalLikes 		= '<?php echo $content['rating']['totalLikes']; ?>';
	
</script>