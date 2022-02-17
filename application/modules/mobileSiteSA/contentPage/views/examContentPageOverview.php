<?php
	$this->load->view('contentPage/examContentPageHeader');
	echo jsb9recordServerTime('SA_MOB_EXAMPAGE',1);
	$this->load->view('contentPage/examContentPageContents');
	$this->load->view('contentPage/examContentPageFooter');
?>
<script>
	var contentId 		= '<?php echo $content['data']['content_id']; ?>';
	var contentType 	= '<?php echo $content['data']['type']; ?>';
	var authorId		= '<?=$contentDetails['created_by'];?>';
</script>