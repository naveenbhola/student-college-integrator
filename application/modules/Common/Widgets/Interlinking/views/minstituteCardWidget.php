<div class="panel-pad hideen__div">
	<div class="search-widget instt-slider">
		<h3 class="col-heading s-hide">
		    Learn more about...
		</h3>
		<ul class="rhs__ul">
			<?php 
			foreach ($widgetInstituteData as $instituteId => $widgetData) {
				?>
				<li class="rhs__li">
					<div class="search__block">
						<div class="find-que">
							<div class="slider-col-tab">
								<ul class="slide-col-ul">
									<li>
									    <div class="data-cols">
									        <div class="main-divs">
									            <div class="slide-img">
									                <a href="<?php echo $widgetData['instituteUrl']; ?>">
									                    <img class="lazy" data-original="<?php echo $widgetData['imageUrl']; ?>" style="width: 60px;height: 48px;">
									                </a>
									            </div>
									            <div class="slide-text">
									                <p class="inf-txts"><?php echo $widgetData['instituteName']; ?></p>
									                <span class="sc-loc"><?php echo $widgetData['mainLocation']['city']; ?></span>
									                <a href="<?php echo $widgetData['instituteUrl']; ?>" ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_ViewCollegeDetails" class="link-col">View College Details</a>
									            </div>
									        </div>

									        <div class="max__height">
									        	<?php 
									        	if(!empty($widgetData['anaCount']) || !empty($widgetData['articleCount']) || !empty($widgetData['reviewCount']) || !empty($widgetData['showAdmissionLink'])){
									        	    ?>
									        	    <div class="border-class">
									        	        <ul class="widget-li">
									        	            <?php 
									        	            if(!empty($widgetData['reviewCount'])){
									        	                ?>
									        	                <li><a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_Reviews"  href="<?php echo $widgetData['reviewUrl']; ?>"><?php echo '<strong>'.$widgetData['reviewCount'].'</strong> Student Review'; echo ($widgetData['reviewCount'] > 1) ? 's' : ''; ?></a></li>
									        	                <?php
									        	            }
									        	            if(!empty($widgetData['anaCount'])){
									        	                ?>
									        	                <li><a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_QnA"  href="<?php echo $widgetData['anaUrl']; ?>"><?php echo '<strong>'.formatNumber($widgetData['anaCount']).'</strong> Answered Question'; echo ($widgetData['anaCount'] > 1) ? 's' : ''; ?></a></li>
									        	                <?php
									        	            }
									        	            if(!empty($widgetData['articleCount'])){
									        	                ?>
									        	                <li><a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_NewsnArticles"  href="<?php echo $widgetData['articleUrl']; ?>"><?php echo '<strong>'.$widgetData['articleCount'].'</strong> News & Articles'; ?></a></li>
									        	                <?php
									        	            }
									        	            if(!empty($widgetData['showAdmissionLink'])){
									        	                ?>
									        	                <li><a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_Admission_Process"  href="<?php echo $widgetData['admissionUrl']; ?>">Admission Process</a></li>
									        	                <?php
									        	            }
									        	            ?>
									        	        </ul>
									        	    </div>
									        	    <?php
									        	}

									        	?>
									        </div>
									        
									        <div class="most-viewd">
									            <?php 
									            if(!empty($widgetData['topCourses'])){
									                ?>
									                <h3 class="most-txt">Most viewed courses</h3>
									                <ul class="mvie-courses">
									                    <?php 
									                    foreach ($widgetData['topCourses'] as $value) {
									                        ?>
									                        <li><p><a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_MostViewedCourses"  href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></p></li>
									                        <?php
									                    }
									                    ?>
									                </ul>
									                <?php
									            }
									            ?>
									            <div class="dwn-btns-col">
									                <a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_ViewAllCourses"  href="<?php echo $widgetData['allCoursesUrl']; ?>" class="ana-btns f-btn">View All Courses <span>(<?php echo formatNumber($widgetData['allCoursesCount']); ?>)</span></a>
									                <a ga-attr="Institute_Cart" ga-optlabel="Mobile_Institute_Cart_DownloadBrochure"  href="javascript:void(0);" onclick="downloadCourseBrochure('<?php echo $widgetData['instituteId']; ?>','<?php echo $widgetTrackingKeyId; ?>',{'pageType':'entityWidget_<?php echo $pageType; ?>','listing_type':'<?php echo $widgetData['listingType']; ?>','callbackFunctionParams':{'pageType':'entityWidget_<?php echo $pageType; ?>','thisObj': this}});" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="ana-btns a-btn">Download Brochure</a>
									            </div>
									        </div>
									    </div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</li>
				<?php
			}
			// exam cards
            foreach ($examCardData as $key => $examData) {
            	?>
				<li class="rhs__li">
					<div class="search__block">
						<div class="find-que">
							<div class="slider-col-tab">
								<ul class="slide-col-ul">
									<li>
									    <div class="data-cols">
									        <div class="main-divs">
									            <div class="slide-text">
									                <p class="inf-txts"><?php echo $examData['examName']; ?> Exam</p>
									                <a href="<?php echo $examData['url']; ?>" ga-attr="Exam_Card" ga-optlabel="Mobile_EXAM_CARD_VIEWEXAMDETAILS" class="link-col">View Exam Details</a>
									            </div>
									        </div>

									        <div <?php if(empty($examData['anaData'])){?> style="margin-top: 15px;" <?php }?>>
									        	<?php 
									        	if(!empty($examData['anaData'])){
									        	    ?>
									        	    <div class="border-class">
									        	        <ul class="widget-li">
									        	            <li style="width: 100%"><a ga-attr="Exam_card_Ana" ga-optlabel="MOBILE_EXAM_CARD_ANA"  href="<?php echo $examData['anaData']['allQuestionURL']; ?>"> <strong><?php echo $examData['anaData']['totalNumber'];?></strong> Answered Questions</a></li>
									        	        </ul>
									        	    </div>
									        	    <?php
									        	}

									        	?>
									        </div>
									        
									        <div class="most-viewd">
									            <?php 
									            if(!empty($examData['sections'])){
									                ?>
									                <h3 class="most-txt">Most viewed information</h3>
									                <ul class="mvie-courses">
									                    <?php 
									                    foreach ($examData['sections'] as $value) {
									                    	if(!empty($value['url'])){ ?>
									                        <li><p><a ga-attr="MOBILE_EXAM_CARD_SECTION" ga-optlabel="DESKTOP_EXAM_CARD_VIEWED_INFORMATION"  href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></p></li>
									                        <?php }}?>
									                </ul>
									                <?php
									            }
									            ?>
									            <div class="dwn-btns-col">
									            	<?php if($examData['isGetSamplePaper']){?>
									                <a ga-attr="Mobile_Exam_Card" ga-optlabel="MOBILE_EXAM_CARD_GET_SAMPLEPAPERS"  href="javascript:void(0);" class="ana-btns f-btn dwn-esmpr" data-trackingkey="1391" data-groupId="<?php echo $examData['groupId'];?>" data-url="<?php echo $examData['url']; ?>" id="download_papers">Get Question Papers</a>
									                <?php }?>
									                <a ga-attr="Mobile_Exam_Card" ga-optlabel="Mobile_Exam_Card_DownloadGuide"  href="javascript:void(0);" class="ana-btns a-btn dwn-eguide <?php if($examData['isGuideDownloaded']){?> ecta-disable-btn <?php }?>dgub<?php echo $examData['groupId'];?>" data-trackingkey="1387" data-groupId="<?php echo $examData['groupId'];?>" id="download_guide"><?php if($examData['isGuideDownloaded']){?>Guide Mailed<?php }else{?>Download Guide<?php }?></a>
									            </div>
									        </div>
									    </div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</li>
				<?php
			
            }
			?>
		</ul>
		<?php 
		$manageDots = (count($widgetInstituteData) + count($examCardData));
		if($manageDots > 1){
			?>
			<div class="dots__section">
				<ol class="carausel__bullets">
					<?php 
					$isFirst = true;
					for ($i=0;$i<$manageDots;$i++) {
						echo ($isFirst) ? '<li class="active"></li>' : '<li></li>';
						$isFirst = false;
					}
					?>
				</ol>
			</div>
			<?php
		}
		?>
	</div>
</div>
