
<?php if($product == "MAllCoursesPage" && ($instituteObj->getType() == "university" || $isReviewExist || $isAnAExist || $isArticleExist)){ ?>
	<div class="crs-widget listingTuple">
		<h2 class="head-L2 intrstd-head"> 
        Other <?php
                    if($instituteObj->getType() == "institute"){
                        echo "college";
                    }else{
                        echo $instituteObj->getType();    
                    }
                    
                 ?> information      
        </h2>
		<div class="intrstd-clgWdgt">
			<ul>
				<?php if($instituteObj->getType() == "university"){
                                $listingId = $instituteObj->getId();
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $instituteObj->getType();
                                $optionalArgs['typeOfPage'] = 'admission';
                                $admissionUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $admissionUrl;?>" >
                                   Admission Process
                                </a>
                            </li>
                            <?php
                    }?>
				  <?php if($isReviewExist){
                                $listingId = $instituteObj->getId();
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $instituteObj->getType();
                                $optionalArgs['typeOfPage'] = 'reviews';
                                $reviewUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $reviewUrl;?>">
                                   <?=$isReviewExist ?> Student Review<?=($isReviewExist > 1 ? 's' : '')?>
                                </a>
                            </li>
                            <?php
                    }?>
                   <?php if($isAnAExist){
                            $listingId = $instituteObj->getId();
                            $type = 'all_content_pages';
                            $optionalArgs['typeOfListing'] = $instituteObj->getType();
                            $optionalArgs['typeOfPage'] = 'questions';
                            $anaUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $anaUrl;?>">
                                   <?=$isAnAExist ?> Answered Question<?=($isAnAExist > 1 ? 's' : '')?>
                                </a>
                            </li>
                            <?php
                    }?>

                    <?php if($isArticleExist){
                              $listingId = $instituteObj->getId();
                            $type = 'all_content_pages';
                            $optionalArgs['typeOfListing'] = $instituteObj->getType();
                            $optionalArgs['typeOfPage'] = 'articles';
                            $articlesUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                            ?>
                            <li>
                                <a href="<?php echo $articlesUrl;?>" >
                                    <?=$isArticleExist ?> News &amp; Article<?=($isArticleExist > 1 ? 's' : '')?>
                                </a>
                            </li>
                            <?php
                    }?>

                    <?php if($isScholarshipExist){
                            $scholarshipUrl =  $instituteObj->getAllContentPageUrl('scholarships');
                            ?>
                            <li>
                                <a href="<?php echo $scholarshipUrl;?>">
                                   Scholarships
                                </a>
                            </li>
                            <?php
                    }?>
			</ul>
		</div>
	</div>
<?php } ?>