<img id = 'beacon_img' width=1 height=1 >
<?php
$footerComponents = array('nonAsyncJSBundle' => 'sa-apply-content',
'asyncJSBundle'    => 'async-sa-apply-content'
);
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>

<?php
		$contentId = $contentData['id'];
		$contentType = $contentData['type'];
?>
<script>
	var contentId 		= '<?=$contentId?>';
	var contentType 	= '<?=$contentType?>';
	var img = document.getElementById('beacon_img');
	var randNum = Math.floor(Math.random()*Math.pow(10,16));
	img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
</script>
<script>

	/**********function which makes sticky the right panel**********************/
    $j(window).on('load',function(){
        initializeOnloadApplyContent();
    });

</script>