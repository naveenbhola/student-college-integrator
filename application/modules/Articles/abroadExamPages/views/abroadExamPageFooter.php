<!--Exam Page Ends-->
<img id = 'beacon_img' width=1 height=1 >
<?php
	$footerComponents = array(
			'js'                => array('studyAbroadExamPage','jquery.royalslider.min','jquery.tinycarouselV2.min'),
            'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
		);
	$this->load->view('common/studyAbroadFooter',$footerComponents);

	$contentId = $examPageObj->getExamPageId();
	$contentType = 'examPage';
?>
<script>
var contentId 		= '<?=$contentId?>';
var contentType 	= '<?=$contentType?>';
var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
</script>

<?php	
	if($scrollDown == "1"){
?>
	<script> navigate("examPageHeadingTitle");</script>
<?php
	}
?>

<script>
	var navBarHead = $j("#leftNavBar").offset().top;
	var footerPosition = $j("#footer").offset().top;
	var stopPosition = -1;
	var sectionarray = [];
	var leftNavSubsectionElements = [];
	sectionarray.push($j(".active0").offset().top - 35);
	leftNavSubsectionElements.push($j(".active0"));
	
    $j(document).ready(function() {
		
		for(var i = 1;i<6;i++){
			var temp = document.getElementById("section" + i);
			if (temp) {
				sectionarray.push($j(temp).offset().top - 35);
				leftNavSubsectionElements.push($j('#leftNavBarSubsection'+i));
			}
		}
    });
	
	setTimeout("startScrollingEffectsDelayed()	",100);
</script>