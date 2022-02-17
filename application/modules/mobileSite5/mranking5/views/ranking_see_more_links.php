<?php
$examId 	= $page_request->getExamId();
$cityId 	= $page_request->getCityId();
$stateId 	= $page_request->getStateId();
$countryId 	= $page_request->getCountryId();

if(array_key_exists('subcat_name', $ranking_page_cat_details)){
		$subcategoryName = $ranking_page_cat_details['subcat_name'];
}

$subcategoryName = str_replace("Full Time", "", $subcategoryName);
$subcategoryName = trim($subcategoryName);
if(!empty($seo_links)){
?>
				<?php
				for($count=0;$count < count($seo_links); $count++){
						if($count==8) break;
				?>
				<section class="content-wrap2  clearfix" >
						<a href="<?php echo $seo_links[$count]->getURL();?>">				
						<?php if($count==0){ ?>
						<h2 class="ques-title">
						    <p>Related results for <?php echo $subcategoryName;?> courses</p>
						</h2>
						<?php } ?>
						
						<article class="req-bro-box clearfix" style="cursor: pointer;" >
								<div class="details" style="margin-bottom: 10px;">
										<?php echo $seo_links[$count]->getName();?>
								</div>
						</article>
						</a>
				</section>
				<?php }
				?>
<?php
}
?>
