<?php
	$this->load->view('contentPage/applyContentHeader');
	echo jsb9recordServerTime('SA_MOB_APPLYCONTENTPAGE',1);
	$this->load->view('contentPage/applyContentContent');
	$this->load->view('contentPage/applyContentFooter');
?>
<script>
	var contentId 		= '<?php echo $content['data']['content_id']; ?>';
	var contentType 	= '<?php echo $content['data']['type']; ?>';
	var authorId		= '<?=$contentData['created_by'];?>';
</script>