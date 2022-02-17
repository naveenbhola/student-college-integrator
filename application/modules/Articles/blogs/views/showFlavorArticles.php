<?php
if(isset($latestUpdates) && $latestUpdates){
    $title = 'Education::Article archives';
    $latestUpdates = true;
}
else{
    $title = 'Education::Articles';
    $latestUpdates = false;
}

$headerComponents = array(
        'css'	=>	array('articles','category-styles'),
        'jsFooter'      =>      array('common'),
        'title'	=>	$title,
        'tabName'	=>	'Articles',
        'taburl' =>  $_SERVER['REQUEST_URI'],
        'metaKeywords'	=>'Some Meta Keywords',
        'product' => 'Articles',   
        'shikshaProduct' => 'Articles',   
    	'bannerProperties' => array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'HEADER'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'callShiksha'=>1,
	'canonicalURL' => $canonicalURL,
	'previousURL' => $previousURL,
	'nextURL' => $nextURL,
        'searchEnable'=>true
        );
$this->load->view('common/header', $headerComponents);
//$this->load->view('blogs/headerSearchPanelForArticles');
$paginationHTML = doPagination($totalArticles,$paginationURL,$startOffset,$countOffset,3);
if($latestUpdates || $mustReadFlag){
  $displayArr = array(10,15,20,25,50);
}
else{
  $displayArr = array(10,15,20,25);
}
//$selectBoxHTML = getPaginationSelectBox($totalArticles,$paginationURL,$startOffset,$countOffset,$displayArr,"View : ");

?>
<script>if($('tempSearchType')) $('tempSearchType').value = 'blog';</script>
<script>if($('tempkeyword')){ $('tempkeyword').value = 'Search Articles'; $('tempkeyword').setAttribute('default','Search Articles'); }</script>
<input type="hidden" name="categoryId" id="categoryId" value="<?php echo $selectedCategory; ?>"/>
<input type="hidden" name="countryId" id="countryId" value="<?php echo $selectedCountry; ?>"/>
<input type="hidden" name="articleType" id="articleType" value="<?php echo $selectedType ; ?>"/>
<?php
    if($totalArticles < 1) { $resultText = 'No Articles'; }
    if($totalArticles == 1) { $resultText = $totalArticles .' Article'; }
    if($totalArticles > 1) { $resultText = $totalArticles .' Articles'; }
    
?>
<div class="spacer10 clearFix"></div>
<div class="mar_full_10p">
    <div style="line-height:15px">
        <div class="float_R" style="padding:5px">
            <div id="pagingIDc">
                <!--Pagination Related hidden fields Starts-->
                <!--<input type="hidden" id="startOffset" value="<?php echo isset($_REQUEST['startOffset']) && $_REQUEST['startOffset'] != '' ? $_REQUEST['startOffset'] : 0; ?>"/>
                <input type="hidden" id="countOffset" value="<?php echo isset($_REQUEST['countOffset']) && $_REQUEST['countOffset'] != '' ? $_REQUEST['countOffset'] : 20; ?>"/>
                <input type="hidden" id="methodName" value="getFlavourArticles"/>-->
                <!--Pagination Related hidden fields Ends  -->
	
			    <span>
			        <span class="pagingID" id="paginataionPlace1"><?php echo preg_replace('/\/0\/20|\/0\/50/','',$paginationHTML);?></span>
				</span>
				<span class="normaltxt_11p_blk bld pd_Right_6p" align="right" id="countOffset_DD1"></span>
				<!--<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
				    <select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $totalArticles > 10 ?'inline' : 'none'; ?>">
					    <option value="10">10</option>
						<option value="15">15</option>
						<option value="20" selected>20</option>
						<option value="25">25</option>
					</select>
				</span>-->
            </div>
        </div>
        <div style="padding:5px;font-family:Georgia,Times New Roman,Times,serif; font-size:30px"><?php if($latestUpdates) echo "Latest Updates"; else if($mustReadFlag) echo "Must Read Articles"; else echo "Flavors of the weeks"; ?></span>
        </div>
    </div>
    <div class="dottedLine">&nbsp;</div>
	<!--MiddlePanel-->
        <?php 
	$data['paginationHTML'] = $paginationHTML;
	$this->load->view('blogs/articleListMiddlePanel',$data); ?>
	<!--End_MidPanel-->
    <div class="clearFix"></div>			
</div>
<?php
$this->load->view('common/footer', $bannerProperties);
?>    
<script>
    //selectComboBox(document.getElementById('countOffset_DD1'), document.getElementById('countOffset').value);
    //selectComboBox(document.getElementById('countOffset_DD2'), document.getElementById('countOffset').value);
    //doPagination(<?php echo $totalArticles;?>,'startOffset','countOffset','paginataionPlace1','paginataionPlace2','methodName',4);
    /*function getFlavourArticles(){
        var startOffset = document.getElementById('startOffset').value;
        var countOffset = document.getElementById('countOffset').value;
        var urlParams = 'startOffset='+startOffset +'&countOffset='+countOffset;
        location.replace('/index.php/blogs/shikshaBlog/getFlavorArticles?'+ urlParams);
    }*/
    callPaginationMethod();
    function callPaginationMethod()
    {
	  $('countOffset_DD1').innerHTML = '<?php echo $selectBoxHTML;?>';
	  $('countOffset_DD2').innerHTML = '<?php echo $selectBoxHTML;?>';
    }
</script>
