<?php
    if($userDetailsArray[0][0][0]['userName']==' ') 
	$displayUserName = $userDetailsArray[0][0][0]['displayname'];
    else 
	$displayUserName = $userDetailsArray[0][0][0]['userName'];
    $headerComponents = array(
        'css'	=>	array('articles','category-styles'),
        'jsFooter'      =>      array('common'),
        'title'	=>	$pageNumber.$displayUserName .'\'s Shiksha.com Articles',
        'taburl' =>  $_SERVER['REQUEST_URI'],
        'metaKeywords'	=>'Some Meta Keywords',
        //'product' => 'Articles',
	'product'	=>'forums',
        'shikshaProduct' => 'Articles',   
    	'bannerProperties' => array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'HEADER'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'callShiksha'=>1,
	'canonicalURL' => $canonicalURL,
	'previousURL' => $previousURL,
	'nextURL' => $nextURL,
	'tabName' =>	'Discussion',
	'metaDescription'=>$pageNumber.'Articles written by '.$displayUserName.' at the Shiksha.com',
        );
$this->load->view('common/header', $headerComponents);
$paginatedPage = doPaginationForArticleAuthor($totalArticles,$paginationURL,$startOffset,$countOffset,3);
$paginationHTML = preg_replace('/-1"/','"',$paginatedPage);

//$selectBoxHTML = getPaginationSelectBox($totalArticles,$paginationURL,$startOffset,$countOffset,$displayArr,"View : ");
//$reportsPerPage = isset($countOffset)?$countOffset:5;
//  $number5 = '';$number10='';$number20='';$number30='';
//  switch($reportsPerPage){
//  case 5: $number5 = "selected";
//	    $data['number5']=$number5;
//	      break;
//  case 10: $number10 = "selected";
//	    $data['number10']=$number10;
//	      break;
//  case 15: $number15 = "selected";
//	     $data['number15']=$number15;
//	      break;
//  case 20: $number20 = "selected";
//	    $data['number20']=$number20;
//	      break;
//  }

?>

<input type="hidden" name="categoryId" id="categoryId" value="<?php echo $selectedCategory; ?>"/>
<input type="hidden" name="countryId" id="countryId" value="<?php echo $selectedCountry; ?>"/>
<input type="hidden" name="articleType" id="articleType" value="<?php echo $selectedType ; ?>"/>
<?php
    if($totalArticles < 1) { $resultText = 'No Articles'; }
    if($totalArticles == 1) { $resultText = $totalArticles .' Article'; }
    if($totalArticles > 1) { $resultText = $totalArticles .' Articles'; }
    if(isset($userDetailsArray) && is_array($userDetailsArray) && is_array($otherUserDetails)){
    if($userDetailsArray[0][0][0]['userName']==' ') 
	$displayUserName = $userDetailsArray[0][0][0]['displayname'];
    else 
	$displayUserName = $userDetailsArray[0][0][0]['userName'];
}
?>

 <?php ?>
<div id="content-child-wrap">			  
		<div class="authors-profile">
        		<h4>Author's Profile</h4>
   	   		<div class="profile-details">
			    <div class="author-pic">
				<img src="<?php if($userDetailsArray[0][0][0]['imageURL']!='') echo getMediumImage($userDetailsArray[0][0][0]['imageURL']);else echo '/public/images/default_author.gif';?>" width="104" height="78" alt="<?=$displayUserName ;?>"/>
			    </div>
			    <div class="author-detail">
				    <h5><?=$displayUserName ;?>
				    </h5>
				<p class="about-head">About me</p>
				<p><strong>Current Position</strong>: <?= $authorData['about_me_current_position'];?></p>
				<p><strong>Specialises In</strong>: <?= $authorData['specialisation'];?></p>
				<br/>
				<p><?= $authorData['about_me_education'];?></p>
			    </div>
			</div>
			<?php if($authorData['gplusprofile']!=''): ?>
        		<div class="follow-us">
				<ul>
				     <li><p><strong>Follow me</strong> on</p></li>
				     <li>
				     <a href="<?= $authorData['gplusprofile'];?>?prsrc=3"
					rel="author" target="_top" style="text-decoration:none;">
					<img src="//ssl.gstatic.com/images/icons/gplus-32.png" alt="Google+" style="border:0;width:32px;height:32px;"/>
				    </a>
				     </li>
				</ul>
			</div>
			<?php endif;?>
           		<div class="clearFix"></div>
        	 </div>  

		<?php
			if($userDetailsArray[0][0][0]['firstname']==' ') {
				$name = trim($userDetailsArray[0][0][0]['displayname']);
			}
			else{
				if($userDetailsArray[0][0][0]['lastname']==' '){
					$name = trim($userDetailsArray[0][0][0]['firstname']);
				}
				else{
					$name = trim($userDetailsArray[0][0][0]['firstname'])." ".trim($userDetailsArray[0][0][0]['lastname']);;
				}
			}
		?>             
		<div class="article-heading">
		    <h2><?=$name?>'s Articles</h2>
		    <div class="article-arrow"></div>
		</div>
	    

		<div style="line-height:15px">
		    <div class="float_R" style="padding:5px">
			<div id="pagingIDc">
			    <!--Pagination Related hidden fields Starts-->
			 <!----   <input type="hidden" id="startOffset" value="<?php echo isset($_REQUEST['startOffset']) && $_REQUEST['startOffset'] != '' ? $_REQUEST['startOffset'] : 0; ?>"/>
			    <input type="hidden" id="countOffset" value="<?php echo isset($_REQUEST['countOffset']) && $_REQUEST['countOffset'] != '' ? $_REQUEST['countOffset'] : 5; ?>"/>
			    <input type="hidden" id="displayName" value="<?php echo $userDetailsArray[0][0][0]['displayname'];?>"/>
			    <input type="hidden" id="methodName" value="getUserBlogs"/>--->
			    <!--Pagination Related hidden fields Ends  -->
		    
					<span style="margin-right:22px;">
					    <span class="pagingID" id="paginataionPlace1"><?php echo preg_replace('/\/0\/20|\/0\/50/','',$paginationHTML);?></span>
					    </span>
					    <span class="normaltxt_11p_blk bld pd_Right_6p" align="right" id="countOffset_DD1"></span>
					    <!---<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
						<select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $totalArticles > 5 ?'inline' : 'none'; ?>">
							    <option value="5" <?php //echo $number5;?>>5</option>
							    <option value="10" <?php// echo $number10;?>>10</option>
							    <option value="15" <?php //echo $number15;?>>15</option>
							    <option value="20" <?php// echo $number20;?>>20</option>
						</select>
					    </span>-->
			</div>
		    </div>
		</div>
  
	<!--MiddlePanel-->
        <?php 
	$data['paginationHTML'] = $paginationHTML;
	$this->load->view('blogs/articleListAuthor',$data); ?>
	<!--End_MidPanel-->
    		
</div>
</div>
<?php
$this->load->view('common/footer', $bannerProperties);
?>    
<script>
//    selectComboBox(document.getElementById('countOffset_DD1'), document.getElementById('countOffset').value);
//    selectComboBox(document.getElementById('countOffset_DD2'), document.getElementById('countOffset').value);
//    doPagination(<?php echo $totalArticles;?>,'startOffset','countOffset','paginataionPlace1','paginataionPlace2','methodName',4);
//    function getUserBlogs(){
//        var startOffset = document.getElementById('startOffset').value;
//        var countOffset = document.getElementById('countOffset').value;
//	var displayName = document.getElementById('displayName').value;
//        var urlParams = 'startOffset='+startOffset +'&countOffset='+countOffset+'&displayName='+displayName;
//        location.replace('/index.php/blogs/shikshaBlog/getUserBlogs?'+ urlParams);
//    }
//    callPaginationMethod();
//    function callPaginationMethod()
//    {
//
//	$('countOffset_DD1').innerHTML = '<?php echo $selectBoxHTML;?>';
//	$('countOffset_DD2').innerHTML = '<?php echo $selectBoxHTML;?>';
//	$('countOffset_DD1').options[0].selected = 'selected';
//	$('countOffset_DD2').options[0].selected = 'selected';
//    }
</script>
