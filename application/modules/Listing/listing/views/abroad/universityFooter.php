<?php $footerComponents = array(
			   'nonAsyncJSBundle'=>'sa-univ-page',
			   'asyncJSBundle'=>'async-sa-univ-page'
			);
	$this->load->view('studyAbroadCommon/saFooter',$footerComponents); 
?>
<script>
    var univId = '';
    <?php
    if(!(empty($universityObj)) && ($universityObj->getId()>0))
    {
    ?>
    univId = '<?php echo $universityObj->getId(); ?>';
    <?php
    }
    ?>
$j(document).ready(function($j) {
	initializeUnivPage("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['currentSchool']->getValues()))); ?>");
});

$j(window).on('load',function () {
    universityPageInitOnLoad();
});

var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011006/<?=$universityObj->getId()?>+<?=$listingType?>';
</script>
<?php //$this->load->view('registration/common/jsInitialization'); ?>