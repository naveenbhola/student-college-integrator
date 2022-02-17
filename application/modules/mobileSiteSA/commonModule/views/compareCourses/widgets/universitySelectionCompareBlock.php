<?php
        $univPhotos        = $univDataObj->getPhotos();           
        if(count($univPhotos))
        {
            $imgUrl = $univPhotos['0']->getThumbURL('172x115');
        }
        else
        {
            $imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
        }
?>
<th style="width:50%; border-bottom:none;">
    <div class="compare-detail-content">
        <div class="SA-compare-fig">
            <img src="<?php echo $imgUrl?>" alt="<?php echo htmlentities($univDataObj->getName());?>" width="140" height="98" alt="compare-ins">
            <a href="javascript:void(0);" class="compare-remove-mark removeUniv">&times;</a>
        </div>
        <p><a href="<?php echo $univDataObj->getURL();?>"><?php echo htmlentities($univDataObj->getName());?></a></p>
    </div>
</th>