<section class="detail-widget 0detail-info">
        	<div class="detail-widegt-sec">
        		<div class="detail-info-sec explore-exam-widget">
                <p>Explore more <?php echo htmlentities($examPageObj->getExamName());?> exam</p>
                <ul class="explore-more-section-exam-page">
                	<?php foreach ($sectionLinks as $sectionName=> $sectionLinks) { ?>
                    <?php if($sectionName =='about section'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite about-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php }else if($sectionName =='exam pattern'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite exam-pttrn-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php } else if($sectionName =='scoring section'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite scoring-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?> <?php echo $examPageObj->getExamName();?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php } else if($sectionName =='important dates'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite imp-date-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php } else if($sectionName =='prepration tips'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite prep-tip-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php } else if($sectionName =='practice and sample paper'){?>
                    <li>
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite practice-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php } else if($sectionName =='syllabus'){?>
                    <li class="last">
                    	<div class="flLt">
                        	<i class="mobile-sop-sprite syllabus-icon"></i>
                        	<a href="<?php echo $sectionLinks['link']; ?>"><?php echo $examPageObj->getExamName();?> <?php echo $sectionLinks['title']; ?></a>
                        </div>
                        <i class="mobile-sop-sprite right-arrw-icn flRt"></i>
                        <div class="clearfix"></div>
                    </li>
                    <?php }  } ?>
                </ul>
    		</div>
    		</div>
		</section>

<section class="detail-widget 0detail-info" data-enhance="false">
            <div class="detail-widegt-sec clearfix">
                <div class="clearfix">
                     <?php $this->load->view('widgets/commentsSection'); ?>
					<?php //$this->load->view('widgets/socialLinks'); ?>
                </div>
            </div>
</section>