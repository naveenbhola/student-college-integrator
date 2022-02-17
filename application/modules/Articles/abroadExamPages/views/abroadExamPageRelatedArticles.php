<div class="about-exam-title clearwidth">
    <div id="examPageHeadingTitle" class="exam-heading clearwidth">
        <h1 class="flLt">Articles related to <?=$examPageObj->getExamName()?></h1>
        <div class="flRt" style="margin-bottom:5px;">
                <?php if($examPageObj->getDownloadLink()){ ?>
                <!--Download Guide Top Button-->
                <a style="margin-right:18px; vertical-align:middle" href="javascript:void(0);" class="button-style dwnld-pdf" onclick="directDownloadORShowOneStepLayer('<?=base64_encode($examPageObj->getDownloadLink())?>)','<?=$examPageObj->getExamPageId()?>','<?=$loggedInUserData['isLDBUser']?>');">
                    <i class="abroad-exam-sprite pdf-icon"></i>
                    <span class="font-12" style="font-weight:bold">Download Exam Guide</span>
                </a>
                <?php } ?>
        </div>
    </div>
</div>
<div class="article-display clearwidth">
    <ul><?php
    $articlesCount = count($subSectionDetails);    
    for($i = 0; $i < $articlesCount; $i++)
    {
        $commentCountStr = "";
        if($subSectionDetails[$i]['commentCount'] != 0) {
            $commentCountStr = $subSectionDetails[$i]['commentCount']. ($subSectionDetails[$i]['commentCount'] > 1 ? " comments" : " comment");
        }
        
        $viewCountStr = "";
        if($subSectionDetails[$i]['viewCount'] != 0) {
            $viewCountStr = $subSectionDetails[$i]['viewCount']. ($subSectionDetails[$i]['viewCount'] > 1 ? " views" : " view");
        }
        
        if($commentCountStr != "" && $commentCountStr != "") {
            $commentCountStr .= " | ";
        }
        
       if($i % 2 == 0) {
            $class = "flLt";
            $liOpenTag = "<li>";
            $liCloseTag = "";
       } else {
            $class = "flRt";
            $liOpenTag = "";
            $liCloseTag = "</li>";
       }
       
       echo $liOpenTag;
    ?>        
            <div class="article-block <?=$class?>">
                <strong><a href="<?php echo $subSectionDetails[$i]['contentURL'];?>" target="_blank"><?php echo formatArticleTitle(htmlentities($subSectionDetails[$i]['strip_title']),80);?></a></strong>
                <div class="article-img">
                    <a href="<?php echo $subSectionDetails[$i]['contentURL'];?>" target="_blank"><img  title="<?=htmlentities($subSectionDetails[$i]['strip_title'])?>" alt="<?=htmlentities($subSectionDetails[$i]['strip_title'])?>" src="<?php echo str_replace("_s","_300x200",$subSectionDetails[$i]['contentImageURL']);?>" width="302" height="200"></a>
                </div>
                <div class="article-caption">
                    <p><?php echo formatArticleTitle(strip_tags($subSectionDetails[$i]['summary']),130);?></p>
                </div>
                
                <div class="article-cmnt">
                    <?php if($commentCountStr != "") { ?><i class="abroad-exam-sprite comment-icon"></i><?php echo $commentCountStr; } ?> <?php echo $viewCountStr;?>
                </div>
                <a href="<?php echo $subSectionDetails[$i]['contentURL'];?>" class="flRt read-more-btn" target="_blank">Read More <span>&rsaquo;</span> </a>
            </div><?php
            
        echo $liCloseTag;
            
    }   // End of for($i = 0; $i < $articlesCount; $i++).
    
    if($articlesCount % 2 != 0){
        echo "</li>";
    }
        ?>
    </ul>
</div>