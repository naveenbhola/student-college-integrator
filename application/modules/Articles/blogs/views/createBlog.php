<?php
		$headerComponents = array(
								'css'	=>	array('articles'),
								'js'	=>	array('common','blog','imageUpload'),
								'title'	=>	'Education::Blog',
								'tabName'	=>	'Blog',
								'taburl' =>  site_url('blogs/shikshaBlog/blogHome'),
								'metaKeywords'	=>'Some Meta Keywords',
								'category_id'   => (isset($CategoryId)?$CategoryId:1),
                                                                'country_id'    => (isset($country_id)?$country_id:2),
                                                                'product' => 'blogs',
                                                                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                                'callShiksha'=>1	
							);
		//$this->load->view('common/header', $headerComponents);
		$this->load->view('common/homepage', $headerComponents);
?>
<?php 
	$headerValue = array();
	$headerValue['searchAction'] = 'blogs/shikshaBlog/blogSearch/1/2';		
	//$this->load->view('common/blog_search',$headerValue);
?>
<script>var categoryTreeMain = eval(<?php echo $category_tree; ?>);	
</script>
<div class="lineSpace_8">&nbsp;</div>

<div>
	<!--End_Right_Panel-->
	<div style="display:block; width:245px; margin-left:0px; margin-right:5px; float:right">
	 <div class="grayFont"> All fields marked with &nbsp;<span style="color:#FF0000;">*</span>&nbsp;are required.</div>	
	 </div>
	<!--End_Right_Panel-->
	
	
	<!--Start_Left_Panel-->
	<div style="display:block; width:154px; margin-left:5px; margin-right:0px; float:left">

			
			<div class="raised_blue_nL"> <b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> </div>
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
                  <div class="row">
                  <!-- <div class="row_blue1" id="categoryTreeSpace">
	                            <?php $treeRoot = 'Root'; $pageUrl = "";	?>
	                            <div class="deactiveselectCategory" id="<?php echo $treeRoot;?>">
	                                <script>
					document.writeln(generateTree(categoryTreeMain,'<?php echo $treeRoot;?>','<?php echo $treeRoot;?>'))
	                                </script>
	                            </div>
			</div> -->
                  </div>
                  
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b> 
			</div>
			<!--End_Course_TYPE-->
	</div>
	<!--End_Left_Panel-->
	
	<!--Start_Mid_Panel-->
	<div style="margin-left:174px; margin-right:174px; padding-left:0px;">
		<div><a href="">blogs</a> > <a href="">My blogs</a> > <span class="blackLink"><a href="">Create blog </a><span></div>
	<?php $this->load->view('blogs/createBlog_Form'); ?>
			   <div class="lineSpace_12">&nbsp;</div>	
	</div>
	
<!--End_Mid_Panel-->
<script>
createCategoryCombo(document.getElementById('countrySelect'),'categoryPlace');
</script>
<?php 
     echo "<script language=\"javascript\"> ";
     echo "var captchacheckUrl = '".site_url('blogs/shikshaBlog/validateTitleAndCaptcha')."'";	
     echo "</script>";
?>
<br clear="all" />
</div>
<!--End_Center-->

<?php $this->load->view('common/footer');  ?>

