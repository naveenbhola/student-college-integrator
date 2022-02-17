<?php
$location = 'Study Abroad';
if($selectedCountryName != '' && $selectedCountryName != 'All'){
    $location = $selectedCountryName;
}
if($selectedCategoryName !='' && $selectedCategoryName != 'All') {
    $location = ' - '. $location;
}
$headerComponents = array(
        'css'	=>	array('header','mainStyle','raised_all','footer','search'),
        'jsFooter'      =>      array('common','prototype','search'),
        'title'	=>	 $selectedCategoryName . $location .' Scholarships',
        'tabName'	=>	'Scholarships',
        'taburl' =>  $_SERVER['REQUEST_URI'],
        'metaKeywords'	=> $selectedCategoryName . $location .'Scholarship, study abroad, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institutes, courses, coaching, technical education, higher education, education career experts, ask experts, admissions, results, events, scholarships, shiksha',
        'metaDescription' => 'Find '. $selectedCategoryName . $location .' scholarship articles, study abroad scholarship articles, article on '. $selectedCategoryName . $location .'.',
        'category_id'   => (isset($CategoryId)?$CategoryId:1),
        'country_id'    => (isset($country_id)?$country_id:2),
        'product' => 'Scholarships',   
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'callShiksha'=>1
        );
$this->load->view('common/homepage', $headerComponents);
?>
<input type="hidden" name="categoryId" id="categoryId" value="<?php echo $selectedCategory; ?>"/>
<input type="hidden" name="countryId" id="countryId" value="<?php echo $selectedCountry; ?>"/>
<?php
    if($total< 1) { $resultText = 'No Scholarships'; }
    if($total== 1) { $resultText = $total.' Scholarship'; }
    if($total> 1) { $resultText = $total.' Scholarships'; }
?>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
    <div>
        <div class="float_R" style="padding:5px">
            <div id="pagingIDc">
                <!--Pagination Related hidden fields Starts-->
                <input type="hidden" id="startOffset" value="<?php echo isset($_REQUEST['startOffset']) && $_REQUEST['startOffset'] != '' ? $_REQUEST['startOffset'] : 0; ?>"/>
                <input type="hidden" id="countOffset" value="<?php echo isset($_REQUEST['countOffset']) && $_REQUEST['countOffset'] != '' ? $_REQUEST['countOffset'] : 20; ?>"/>
                <input type="hidden" id="methodName" value="getScholarshipsForCriteria"/>
                <!--Pagination Related hidden fields Ends  -->
	
			    <span style="margin-right:22px;">
			        <span class="pagingID" id="paginataionPlace1"></span>
				</span>
				<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
				    <select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $total > 10 ?'inline' : 'none'; ?>">
					    <option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
					</select>
				</span>
            </div>
        </div>
        <div class="fontSize_16p bld" style="padding:5px;">
            Found <span id="resultText"><?php echo $resultText; ?></span> For <span id="criteriaLabel" class="OrgangeFont">Science &amp; Engineering</span>
        </div>
    </div>
    <div class="dottedLine">&nbsp;</div>
    <!--RightPanel-->
	<!--<div class="float_R" style="width:147px">&nbsp;</div>-->
	<!--End_RightPanel-->
	<!--LeftPanel-->
	<div class="float_L" style="width:200px;display:none">
            <?php $this->load->view('blogs/showArticlesListLeftPanel', array('productName' => 'Scholarships')); ?>
	</div>
	<!--End_LeftPanel-->
	<!--MiddlePanel-->
	<div style="margin:0 0px 0 0px">
        <div>
            <?php $this->load->view('listing/scholarshipsList'); ?>
        </div>
        <div align="right">
			    <span style="margin-right:22px;">
			        <span class="pagingID" id="paginataionPlace2"></span>
				</span>
				<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
				    <select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $total > 10 ?'inline' : 'none'; ?>">
					    <option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
					</select>
				</span>
            </div>
		<div class="lineSpace_15">&nbsp;</div>
    </div>
	<!--End_MidPanel-->
    <div class="clear_L"></div>			
</div>
<?php
$this->load->view('common/footer', $bannerProperties);
?>    
<script>
    selectComboBox(document.getElementById('countOffset_DD1'), document.getElementById('countOffset').value);
    selectComboBox(document.getElementById('countOffset_DD2'), document.getElementById('countOffset').value);
    doPagination('<?php echo $total;?>','startOffset','countOffset','paginataionPlace1','paginataionPlace2','methodName',4);
    function getScholarshipsForCriteria() {
        var categoryId = document.getElementById('categoryId').value;
        var countryId = document.getElementById('countryId').value;
        var startOffset = document.getElementById('startOffset').value;
        var countOffset = document.getElementById('countOffset').value;
        var urlParams = 'categoryId='+ categoryId +'&countryId='+ countryId  +'&startOffset='+startOffset +'&countOffset='+countOffset
        location.replace('/index.php/listing/Listing/showScholarshipsList?'+ urlParams);
    }
</script>
