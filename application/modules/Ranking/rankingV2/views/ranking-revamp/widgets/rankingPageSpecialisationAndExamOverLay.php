<?php 
    if($divId == 'examDropdownLayer'){
        $divClass = "exam-layer";
        $trackLabel = "filter-exam";
        $liClass = "";
    }else{
        $divClass = "specialization-layer";
        $trackLabel = "filter-specialization";
        $liClass = "class='flLt'";
    } 
?>

<div class="<?=$divClass?> <?=$divId?>" style="display: none;top:36px;">
    <div class="scrollbar1 filtersOverlay">	
        <div class="scrollbar">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:240px;overflow:hidden;">
            <div class="overview">
                <div class="exam-list">
                    <?php if(!empty($filterResult) &&  $showFilters !== false){ ?>
                        <ul>
                            <?php if($divId == 'specialisationDropdownLayer'){?>
                                <?php
                                    $title      = $filterResult['parentUrl']['name'];
                                    $url        = $filterResult['parentUrl']['url'];
                                ?>
                                <li <?php echo $liClass;?> >
                                    <a href='<?php echo $url; ?>' label='<?=$trackLabel?>' title='<?=$title?>'>
                                        <?php echo $title; ?>
                                    </a>
                                </li>
                                <?php
                                    foreach($filterResult['childrenUrl'] as $filter){
                                        $title      = $filter['name'];
                                        $url        = $filter['url'];
                                ?>
                                        <li <?php echo $liClass;?> >
                                            <a href='<?php echo $url; ?>' label='<?=$trackLabel?>' title='<?=$title?>'>
                                                <?php echo $title; ?>
                                            </a>
                                        </li>
                                <?php 
                                    }
                                ?>
                            <?php } ?>
                            <?php
                                if($divId == 'examDropdownLayer'){
                                    foreach($filterResult as $filter){
                                        $title      = $filter->getName();
                                        $url        = $filter->getURL();
                                        $isSelected = $filter->isSelected();
                                        if($isSelected != true){
                            ?>
                                            <li <?php echo $liClass;?> >
                                                <a href='<?php echo $url; ?>' label='<?=$trackLabel?>' title='<?=$title?>'>
                                                    <?php if($title=='All'){ echo 'All (Exams)'; }else{ echo $title; }?>
                                                </a>
                                            </li>
                            <?php       
                                        }
                                    }
                                }
                            ?>
                        </ul>
                    <?php  }?>
                </div>
            </div>
        </div>
    </div>
</div>