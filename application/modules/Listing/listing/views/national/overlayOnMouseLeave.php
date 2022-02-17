<!--Course Content starts here-->
<div id="overlayOnLeave" style="display:none;position:absolute;z-index:10002;">
        <div class="management-layer" style="width:825px;">
        <div class="layer-title">
                <a title="Close" class="flRt cross-icon-2" href="javascript:void(0);" onclick="hideOverlayOnEscape();">&times;</a>
                <div class="title">Did not find what you were looking for? You might find following links useful</div>
        </div>
        <div class="course-link-list">
            <ul>
                <?php
                foreach($rankingPageUrl as $urlData) { 
                    if($urlData['text'] != '' && $urlData['url'] != '') { ?>
                    <li><a href="<?php echo $urlData['url']?>"><?php echo $urlData['text']?></a></li>
                <?php 
                    }
                }
                ?>
                <?php
                foreach($categoryPageUrl as $urlData) { 
                    if($urlData['text'] != '' && $urlData['url'] != '') { ?>
                    <li><a href="<?php echo $urlData['url']?>"><?php echo $urlData['text']?></a></li>
                <?php }
                }
                ?>
                <div class="clearFix"></div>
            </ul>
        </div>
        <div id="similarCoursesOnOverlay">
            <div class="layer-title">
                    <div class="title">Students looking for <span class="college-info">
                        <?php  
                        list($instituteName) = explode(',',$institute->getName());
                        echo html_escape($instituteName); ?>
                        </span> in <span class="college-info">
                            <?php if(!$isMultilocation){?>
                <?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName()) ? $currentLocation->getLocality()->getName().", ":"")?>
                <?=$currentLocation->getCity()->getName()?>
                <?php }?>
                             </span> also looked at</div>
            </div>
            <div class="course-link-list">
                <ul>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="newOverlay" id="newOverlayForBounceUser" style="display: none;"></div>
<!--Course content ends here-->
            


<script>
var showOverlayOnMouseLeaveFlag = '<?php echo $showOverlayOnMouseLeave;?>';
showOverlayOnPageLeave(showOverlayOnMouseLeaveFlag,showOverlayOnMouseLeave,hideOverlayOnEscape);
hideDivOnBlur('overlayOnLeave','newOverlayForBounceUser');
</script>
