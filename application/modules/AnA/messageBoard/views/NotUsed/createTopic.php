<?php
		$title='Ask and Answer- Enter Question Details – Education Community Questions';
		$metaDescription='Ask Questions related to Education Community, Career, test preparation, education events and study programs in India and Abroad.';
		$metaKeywords='Ask and answer, education questions, education, events, education, professional education, education, answers, agriculture, architecture, biological sciences, chemistry, civil engineering, electronics, mathematics, mechanical engineering, questions, shiksha';
		if(isset($isRelated) && ($isRelated == 'true')):
			$title='Ask and Answer- Review Question and Submit- Education Community Questions';
			$metaDescription='Ask questions related to education community and review your questions before submission to site.';
			$metaKeywords='Ask and answer, review question, submit questions, education questions, education, events, education, professional education, education, answers, agriculture, architecture, biological sciences, chemistry, civil engineering, electronics, mathematics, mechanical engineering, questions, shiksha.';
		endif;
		$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'HEADER');
		$headerComponents = array(
						'css'	=>	array('raised_all','mainStyle','header'),
						'js'=> array('common'),
						'jsFooter'=> array('discussion','tooltip'),
						'title'	=> $title,
						'tabName' =>	'Discussion',
						'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
						'metaDescription' => $metaDescription,
						'metaKeywords' => $metaKeywords,
						'product' => 'forums',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'bannerProperties' => $bannerProperties,	
						'notShowSearch' => true,
                                                'callShiksha'=>1
					);
		$this->load->view('common/homepage', $headerComponents);
		
		$tabText = 'Ask Shiksha.com';
		if($questionId > 0){
			$tabText = 'Edit Your Question';
		}
?>

<div class="mar_left_10p myHeadingControl bld"><span class="fontSize_14p"><?php echo $tabText; ?></span></div>
<div class="lineSpace_15">&nbsp;</div>
<div class="mar_full_10p">
	<div style="display:inline; float:left; width:100%">
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
						<?php  $this->load->view('messageBoard/createTopicForm'); ?>
				</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>	
</div>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1); 
?>
<script>
function showHideCatSection(showIt){
	if(showIt){
		document.getElementById('categorySectionLinkForAskQuestion').style.display='none';
		document.getElementById('categorySectionId').style.display='block';
	}else{
		document.getElementById('categorySectionLinkForAskQuestion').style.display='block';
		document.getElementById('categorySectionId').style.display='none';
	}
}
var questionIdForJs = <?php echo $questionId; ?>;
var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
var handleForRelatedQuestionAjaxCall = null;
if(document.getElementById('c_categories_combo'))
{
	getCategories(true,'c_categories_combo','selectCategory','selectCategory',false,'question_category');
}
<?php if($csvCatList != '') { ?>
if(document.getElementById('selectCategory'))
{
	var valueToSelect = '<?php echo $csvCatList;?>';
	valueToSelect = valueToSelect.split(',');
	selectMultiComboBox(document.getElementById('selectCategory'),valueToSelect);
}
<?php  } ?>
if(document.getElementById('topicDesc'))
{
	var FormObject = document.getElementById('topicDesc').form;
	addOnFocusToopTip(FormObject);
	addOnBlurValidate(FormObject);
}
</script>
