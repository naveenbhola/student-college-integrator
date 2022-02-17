<?php
$headerComponents = array(
	'css'               => array('studyAbroadCommon', 'studyAbroadCategoryPage'),
	'canonicalURL'      => $seoUrl,	
	'title'             => 'My Saved Courses - Shiksha.com',
	'metaDescription'   => 'User Saved Courses - Shiksha.com',
	'metaKeywords'      => '',
    'robotsMetaTag'     => $robotsMetaTag,
	'pgType'	        => 'shortlistPage',
	'pageIdentifier'    => $beaconTrackData['pageIdentifier']

);
$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<style>
.font-15{font-size:15px;} 
.all-univ-table{width:100%; margin-top:20px;}
.all-univ-table tr th{background:#ebebeb; border-bottom:2px solid #4573b1; color:#333; padding:15px 10px; text-align:left;}
.all-univ-table tr{-moz-transition:background-color 500ms ease-out; -webkit-transition:background-color 500ms ease-out; transition:background-color 500ms ease-out;}
.all-univ-table tr:hover{background-color:#f1f1f1}
.all-univ-table tr td{vertical-align:top; padding:12px; border-bottom:1px solid #ebebeb; line-height:18px; color:#666;}
.view-btn{border:1px solid #ccc; -moz-border-radius:2px; -webkit-border-radius:2px;  border-radius:2px;padding:4px 7px; color:#333 !important; text-decoration:none !important; display:block; margin:5px 0 0 0; width:158px; background:#fff; -moz-transition-property:background-color, color,; -moz-transition-duration:500ms; -moz-transition-timing-function:ease-out;-webkit-transition-property:background-color, color,; -webkit-transition-duration:500ms; -webkit-transition-timing-function:ease-out; transition-property:background-color, color,; transition-duration:500ms; transition-timing-function:ease-out;}
.view-btn:hover{color:#fff !important; background:#F78640;}
.all-univ-table tr td span{display:block;}
.all-univ-table tr td.last{border-right:1px solid #ebebeb;}
.view-btn span{font-size:18px; float:right;}
.alt-rowbg{background:#fafafa;}
.number-bg{background:#f6f6f6; font-weight:normal; border-bottom:0 none !important;}

</style>
<script>
	var showCompareLayerPage = true;
</script>

<?php
$this->load->view('categoryList/abroad/abroadShortListBody');

	$footerComponents = array(
		'js'                => array('studyAbroadCategoryPage','json2','jquery.royalslider.min','jquery.tinycarouselV2.min'),
	    'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min')
	);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>

<script>
	var rmcPageTitle = "<?=base64_encode('Saved Courses');?>";
	var isShortlistPage = true;
	function showExamDiv(obj){
		$j(obj).closest('.detail-col').find('.extra-exam-div').slideDown();
		$j(obj).hide();
	}
	
	var focusCourseId = '<?=$rmcTupleToFocus?>';
	$j(document).ready(function(){
		if ($j("#rateMyChanceListing_tupleId_"+focusCourseId).length > 0) {
			$j('html, body').animate({
				scrollTop: $j("#rateMyChanceListing_tupleId_"+focusCourseId).offset().top
			}, 2000);
		}
	});
	
	var shortlistTab = '<?=$showShortlistTab?>';
	if (shortlistTab == '1') {
		$j(".shortlistTab").find("a").click();
	}
</script>