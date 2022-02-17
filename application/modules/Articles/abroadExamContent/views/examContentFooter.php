
<?php
$footerComponents = array('nonAsyncJSBundle' => 'sa-exam-content',
    'asyncJSBundle'    => 'async-sa-exam-content'
);
$this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>
<img id = 'beacon_img' width=1 height=1 >
<script>
    var isExamContentPage = 1;
    var contentId = parseInt('<?php echo $contentId; ?>');
	var img = document.getElementById('beacon_img');
	var randNum = Math.floor(Math.random()*Math.pow(10,16));
	img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
    $j(window).on('load',function(){
        initializeOnloadExam();

    });
</script>
