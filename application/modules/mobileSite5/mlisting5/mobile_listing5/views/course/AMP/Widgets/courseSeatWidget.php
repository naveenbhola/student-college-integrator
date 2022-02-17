<!--seats block-->
         <section>
             <div class="data-card m-5btm" id="seats">
                 <h2 class="color-3 f16 heading-gap font-w6">Seats Break-up</h2>
                 <div class="card-cmn color-w">
                 <?php if(!empty($seats['totalSeats'])) {?>
                     <h3 class="color-9 pos-rl f14 font-w4">Total Seats</h3>
                     <p class="color-3 f26 font-w6 m-3btm"><?=$seats['totalSeats']?></p>
                <?php } ?>

                  <?php if(!empty($seats['categoryWiseSeats']) || !empty($seats['examWiseSeats']) || !empty($seats['domicileWiseSeats']) ) {?>
                     <amp-accordion disable-session-states>
                       <?php if(!empty($seats['categoryWiseSeats'])){?>
                         <section expanded class="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION">
                             <h4 class="color-w f14 pad8 font-w6 color-3">Catagory</h4>
                             <div class="pad10 res-col">
                                 <input type="checkbox" class="read-more-state hide" id="categorySeats">
                                 <table class="seats-table read-table">
                                  <?php                             
                                    foreach ($seats['categoryWiseSeats'] as $key => $val) {
                                      $hideClass = "";
                                      if($key > 4){
                                        $hideClass = "read-more-target";
                                      }
                                      ?>
                                     <tr class="<?=$hideClass;?>">
                                         <td><p class="f14 color-3 font-w4"><?=$val['category']?></p></td>
                                         <td><p class="pad3 f14 font-w6 t-cntr"><?=$val['seats']?></p></td>
                                     </tr>
                                     <?php } ?>
                                 </table>
                                 <?php if(count($seats['categoryWiseSeats']) > 5){?>
                                    <label for="categorySeats" class="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_BREAKUP_VIEWALL">View all </label>
                                  <?php } ?>
                              
                             </div>
                         </section>
                         <?php } ?>

                      <?php if(!empty($seats['examWiseSeats'])){ ?>
                         <section class="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION">
                             <h4 class="color-w f14 pad8 font-w6 color-3">Entrance Exam</h4>
                             <div class="pad10 res-col">
                              <input type="checkbox" class="read-more-state hide" id="examWiseSeats">
                              <table class="seats-table read-table">
                                <?php foreach ($seats['examWiseSeats'] as $key => $val) {
                                    $hideClass = "";
                                    if($key > 4){
                                      $hideClass = "read-more-target";
                                    }
                                ?>
                                    <tr class="<?=$hideClass;?>">
                                      <td><p class="f14 color-3 font-w4"><?=$val['exam']?></p></td>
                                      <td><p class="pad3 f14 font-w6 t-cntr"><?=$val['seats']?></p></td>
                                    </tr>
                                <?php } ?>
                               </table>
                               <?php if(count($seats['examWiseSeats']) > 5){?>
                                    <label for="examWiseSeats" class="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_ENTRANCEEXAM_VIEWALL">View all </label>
                                <?php } ?>
                             </div>
                         </section>
                      <?php } ?>

                      <?php if(!empty($seats['domicileWiseSeats'])){?>
                         <section class="seats-drop ga-analytic" data-vars-event-name="SEAT_BREAKUP_ACCORDION">
                             <h4 class="color-w f14 pad8 font-w6 color-3">Domicile</h4>
                             <div class="pad10 res-col">
                             <input type="checkbox" class="read-more-state hide" id="domicileWiseSeats">
                             <table class="seats-table">
                             <?php foreach ($seats['domicileWiseSeats'] as $key => $val) {
                                $hideClass = "";
                                    if($key > 4){
                                      $hideClass = "read-more-target";
                                    }
                                ?>
                                    <tr class="<?=$hideClass;?>">
                                        <td><p class="f14 color-3 font-w4"><?=$val['category']?>  <?php if($val['category'] == 'Related State' && !empty($seats['relatedStates'])){ ?><a class="i-block color-b f12 font-w6" on="tap:domicileSeats" role="button" tabindex="0"><i class="cmn-sprite clg-info i-block v-mdl"></i></a><?php } ?></p></td>
                                        <td><p class="pad3 f14 font-w6 t-cntr"><?=$val['seats']?></p></td>
                                      </tr>
                                <?php } ?>
                               </table>
                                <?php if(count($seats['domicileWiseSeats']) > 5){?>
                                    <label for="domicileWiseSeats" class="block m-top color-b t-cntr f14 font-w6 v-arr read-more-trigger ga-analytic" data-vars-event-name="SEAT_DOMICILE_VIEWALL">View all </label>
                                <?php } ?>
                              
                             </div>
                         </section>
                        <?php } ?>
                     </amp-accordion>
                     <?php } ?>
                 </div>

             </div>
         </section>
<?php if(!empty($seats['relatedStates'])) {?>
  <amp-lightbox class="" id="domicileSeats" layout="nodisplay" scrollable>
       <div class="lightbox" >
         <a class="cls-lightbox f25 color-f font-w6" on="tap:domicileSeats.close" role="button" tabindex="0">&times;</a>
         <div class="m-layer">
                <div class="min-div color-w pad10">
                 <p class="m-btm f14 color-3 font-w6">Domicile</p>
                    <ul>
                          <li class="f12 color-6 m-5btm al-ul"><?=$seats['relatedStates'];?></li>
                    </ul>
                </div>
            </div>
         </div>
     </amp-lightbox>         
  <?php } ?>