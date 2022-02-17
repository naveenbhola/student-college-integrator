<?php 
  $headingText = $examAccepting['totalCount'] > 1 ? $examAccepting['headingText']. ' Institutes accept' : $examAccepting['totalCount'] .' Institute accepts';
  ?>

<section>
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6"><?=$headingText;?>  <?=$examName;?></h2>
                <div class="card-cmn color-w f14">
                    <ul class="ins-acc-ul l-12">
                      <?php foreach ($examAccepting['instCourseMapping'] as $key => $value) { ?>
                        <li class="ga-analytic" data-vars-event-name="INSTITUTE_ACCEPT">
                            <div class="ins-acc-img"><a href="<?=$value['instituteUrl'];?>"><amp-img src="<?=$value['imageUrl'];?>" alt="<?=htmlentities($value['instituteName']);?>" width="79" height="60"></amp-img></a></div>
                            <div class="inln">
                                <?php 
                                 if(strlen($value['instituteName']) > 75)
                                 {
                                    $instituteName = substr($value['instituteName'],0,72).'...';
                                 }
                                 else
                                 {
                                    $instituteName = $value['instituteName'];
                                 }
                                 if(strlen($value['courseName']) > 85)
                                 {
                                    $courseName = substr($value['courseName'],0,82).'...'; 
                                 }
                                 else
                                 {
                                    $courseName = $value['courseName'];
                                 }
                                 ?>
                                <a class="color-3 f13 font-w6 l-12" href="<?=$value['instituteUrl'];?>"><?=htmlentities($instituteName);?></a>
                                <a class="color-6 f11 block ll-14" href="<?=$value['courseUrl'];?>"><?=htmlentities($courseName);?></a>
                            </div>

                        </li>
                        <?php } ?>
				          </ul>
                  <?php if($examAccepting['totalCount'] > $instituteAccptLimit && !empty($examAccepting['srpUrl'])) { ?>
                    <div class="btn-sec">
                        <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic" data-vars-event-name="INSTITUTE_ACCEPT_VIEW_ALL" href="<?=$examAccepting['srpUrl'];?>">View All</a>
                      </div>
                    <?php } ?>
                </div>
            </div>
          </section>