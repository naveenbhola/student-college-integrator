<section class="detail-widget">
    <div class="detail-widegt-sec clearfix">
        <div class="detail-info-sec">
            <strong>People who viewed this university also viewed</strong>
            <ul class="vwd-tpl">
                <?php foreach($alsoViewedUniversityData as $key=>$tupleData){
                ?>
                <li>
                  <div class="vwd-img">
                    <a href="<?=$tupleData['url'];?>"><img src="<?php echo $tupleData['univImg']?>" width="75" height="50"></a>
                  </div>
                  <div class="vwd-info">
                  <a href="<?= $tupleData['url'];?>" class="ui-link"><?= htmlentities(formatArticleTitle($tupleData['name'],60));?></a>
                    <span><?= $tupleData['cityName'];?>, <?= $tupleData['countryName'];?></span>
                    <p class="view-all-course-link"><?= ($tupleData['courseCount']>1)?" ".$tupleData['courseCount']." courses":" 1 course";?> available</p>
                  </div>

                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</section>