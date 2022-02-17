<div class="widget-wrap clearwidth">
    <h2>Popular universities in <?=$universityObj->getLocation()->getCountry()->getName()?></h2>
    <div class="flLt updated-pop-univ-list">
    <?php   $i=0;
            while($i<8){
                if($universityDataObject = current($topUniversities['universities'])){;
                    if($universityDataObject->getId() == $universityObj->getId()){
                        $universityDataObject = next($topUniversities['universities']);
                    }
                    ++$i;
                    if(!($universityDataObject instanceof University)){
                        break;
                    }
    ?>
        <ul>
            <li class="flLt">
                <a href="<?=$universityDataObject->getURL()?>"><?=  htmlentities($universityDataObject->getName())?></a><br>
                <span><?=$universityDataObject->getLocation()->getCity()->getName()?></span>
            </li>
            <?php   if($universityDataObject = next($topUniversities['universities'])){
                        if($universityDataObject->getId() == $universityObj->getId()){
                            $universityDataObject = next($topUniversities['universities']);
                        }
                        ++$i;
                        if($universityDataObject instanceof University){
            ?>
                        <li class="flRt">
                            <a href="<?=$universityDataObject->getURL()?>"><?=  htmlentities($universityDataObject->getName())?></a><br>
                            <span><?=$universityDataObject->getLocation()->getCity()->getName()?></span>
                        </li>
            <?php       }next($topUniversities['universities']);
                    }
            ?>
        </ul>
    <?php       }else{
                    break;
                }
            }
    ?>
    </div>
    <div class="clearfix"></div>
    <a class="flRt" href="<?=$topUniversities['countryPageUrl']?>">View all <?=$topUniversities['totalUniversityCount']?> universities in <?=$universityObj->getLocation()->getCountry()->getName()?> <strong class="univ-link">&gt;</strong></a>
</div>