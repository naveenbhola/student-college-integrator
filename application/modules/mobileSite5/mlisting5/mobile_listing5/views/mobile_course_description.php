            <?php $courseDescriptions = array();
			$i=0;
			foreach($courseComplete->getDescriptionAttributes() as $attribute){
                            
				$contentTitle = $attribute->getName();
				if($contentTitle == "Course Description"){
					$courseDescription = array($attribute);
				}else{
					$courseDescriptions[] = $attribute;
				}
			}
			if($courseDescription){
				$courseDescriptions = array_merge($courseDescription,$courseDescriptions);
			}
			foreach($courseDescriptions as $attribute){
				
				$itempropFlag = false;
				$contentTitle = $attribute->getName();
				if($contentTitle == "Course Description")
					$itempropFlag = true;
				if(strlen($contentTitle)>30){
					$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 30))."...";
				}?>
                <dt>
                    <a href="javascript: void(0);">
                        <h3><?=$contentTitle?></h3>
                        <i id="desc<?=$i?>" class="icon-arrow-up"></i>
                    </a>
                </dt>

                <dd>
                      <div class="tiny-contents">
					    <?php
						    $summary = new tidy();
						    $summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
						    $summary->cleanRepair();
					    ?>
					    <?php
					    if($itempropFlag)
					    	echo '<p itemprop="description">'.$summary.'</p>';
					    else
					    	echo $summary;

					    ?>
					    <?php if($i == 0){?>
					    <?php $isInternal=false; echo Modules::run('mOnlineForms5/OnlineFormsMobile/applyNowButton',$courseId,$isInternal,$applicationTrackingPageKeyId); }?>
					</div>
                </dd>
                <?php if($i == 0){ ?>
                <p class="clearfix"></p>
                <?php } ?>
            <?php $i++;
                     }?>

          
   
