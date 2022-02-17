<?php
$examPageBuilder = new ExamPageBuilder($params);
$builderRequest = $examPageBuilder->getRequest();

$examPageRepository = $examPageBuilder->getExamPageRepository();
$examPageData = $examPageRepository->find($examName);

if($examPageData){ 
    $examPagesActiveSections = $this->config->item('examPagesActiveSections');
    $examPagesActiveSections = array_keys($examPagesActiveSections);
    $sectionData            = $examPageData->getSectionInfo();
    $activeTileDetails      = array();
    foreach($sectionData as $sectionName=>$section)
    {
        if(in_array($sectionName, $examPagesActiveSections))
        {
            $activeTileDetails[] = $section;
        }
    }

    $builderRequest->setExamName($examName);
    $home               = $builderRequest->getUrl('home');
    $syllabus           = $builderRequest->getUrl('syllabus');
    $imp_dates          = $builderRequest->getUrl('imp_dates');
    $article            = $builderRequest->getUrl('article');
    $results            = $builderRequest->getUrl('results');
    $discussion         = $builderRequest->getUrl('discussion');
    $colleges['url']    = $examPageData->getTileLink();
    $preptips           = $builderRequest->getUrl("preptips");
    
    $sectionNamesMapping = $this->config->item("sectionNamesMapping");
    
    
    ?>
    
    <article class="explore-details" id="rightSideLinks<?=$params?>" style='display:none' >
            <ul>
                <?php
                foreach ($activeTileDetails as $activeTileDetail){
                    // don't show the nav link if its flag is off
                    if($activeTileDetail['show_link_in_menu'] == 0 ){
                        continue;
                    }
                    if($activeTileDetail['name'] == 'discussion'){
                        continue;
                    }
                    if($sectionNamesMapping[$activeTileDetail['name']] == 'Top Colleges') {
                        $sectionName = 'Colleges Accepting '.$examName;
                    } else {
                        $sectionName = $sectionNamesMapping[$activeTileDetail['name']];
                    }
        		    //Variables for GA TRacking
        		    $GAExamName = strtoupper(preg_replace('/[^a-zA-Z0-9_]/', '_', $examName));
        		    $GALinkName = strtoupper(preg_replace('/[^a-zA-Z0-9_]/', '_', $sectionNamesMapping[$activeTileDetail['name']] )); ?>
                    <li>
                        <a onClick="trackEventByGAMobile('HOMEPAGE_<?=$GAExamName?>_<?=$GALinkName?>');"  href="<?php echo ${$activeTileDetail['name']}['url'];?>"><?=$sectionName?></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="msprite box-angle3"></div>
    </article>
<?php
}
unset($examPageBuilder);
unset($builderRequest);
?>
