  <?php 
  $headingText = $examAccepting['totalCount'] > 1 ? $examAccepting['headingText']. ' Institutes accept' : $examAccepting['totalCount'] .' Institute accepts';
  ?>
  <div class="card__right mt__15 ps__rl global-box-shadow">
                     <div id="examAccepting">  <h3 class="f16__clr3 fix__sec fnt__sb"><?=$headingText;?> <?=$examName;?></h3>
                        <?php if($examAccepting['totalCount'] > 10 && !empty($examAccepting['srpUrl'])) { ?>
                          <a class="ps__abs sm__btn" href="<?=$examAccepting['srpUrl'];?>" target="_blank" ga-attr="VIEW_ALL_INSTITUTE_ACCEPT">View All</a>
                        <?php } ?>
                     </div>
                      <div class="fixed__height mtop__10" id="acceptingExam">
                        <ul class="inst__ul">
                        <?php foreach ($examAccepting['instCourseMapping'] as $key => $value) { ?>
                          <li class="mt__10" ga-attr="INSTITUTE_ACCEPT">
                            <div class="main-divs">
                                 <!--img col-->
                                 <div class="slide__img">
                                    <a href="<?=$value['instituteUrl'];?>" target="_blank">
                                      <img class="lazy" data-original="<?=$value['imageUrl'];?>" alt="<?=htmlentities($value['instituteName']);?>" style="width: 60px;height: 60px;">
                                    </a>
                                 </div>
                                 <!--college inf-->
                                 <div class="slide__text">
                                 <?php 
                                 if(strlen($value['instituteName']) > 70)
                                 {
                                    $instituteName = substr($value['instituteName'],0,67).'...';
                                 }
                                 else
                                 {
                                    $instituteName = $value['instituteName'];
                                 }
                                 if(strlen($value['courseName']) > 80)
                                 {
                                    $courseName = substr($value['courseName'],0,77).'...'; 
                                 }
                                 else
                                 {
                                    $courseName = $value['courseName'];
                                 }
                                 ?>
                                    <a class="f14__clr3 fnt__sb ib__block word-wrap" href="<?=$value['instituteUrl'];?>" target="_blank" title="<?=htmlentities($value['instituteName']);?>"><?=htmlentities($instituteName);?></a>
                                    <a class="f12__clr3 i__block word-wrap" href="<?=$value['courseUrl'];?>" target="_blank" title="<?=htmlentities($value['courseName']);?>"><?=htmlentities($courseName);?></a>
                                    <!-- <span class="f12__clr6 i__block"><?=$value['courseName'];?></span> -->
                                  </div>
                               </div>
                          </li>
                        <?php } ?>
                        </ul>
                      </div>
                   </div>