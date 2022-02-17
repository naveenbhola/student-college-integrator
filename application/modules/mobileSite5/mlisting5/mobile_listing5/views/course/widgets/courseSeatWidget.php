<div class="crs-widget listingTuple" id="seats">
     <h2 class="head-L2">Seats Break-up</h2>
      <div class="lcard">
          <?php if(!empty($seats['totalSeats'])) {?>
          <h3 class="seats-inf">Total Seats</h3>
          <p class="num-p fee-total"><?=$seats['totalSeats']?></p>
          <?php } ?>
          <?php if(!empty($seats['categoryWiseSeats']) || !empty($seats['examWiseSeats']) || !empty($seats['domicileWiseSeats']) ) {?>
          <div class="panel-group">
              <?php if(!empty($seats['categoryWiseSeats'])){?>
              <div class="panel seatsCategory">
                  <div class="panel-h"><p>Category</p></div>
                  <div class="panel-body" style="display: none">
                      <ul>
                          <?php                             
                            foreach ($seats['categoryWiseSeats'] as $key => $val) {
                              $hideClass = "";
                              if($key > 4){
                                $hideClass = "hidele";
                              }
                              ?>
                            <li><p class="<?=$hideClass?>"><?=$val['category']?><strong><?=$val['seats']?></strong></p></li>
                          <?php }?>           
                          <?php if(count($seats['categoryWiseSeats']) > 5){?>
                          <a class="link-blue-medium v-more expand-view">View all</a>
                          <?php } ?>
                      </ul>

                  </div>
              </div>
              <?php } ?>

              <?php if(!empty($seats['examWiseSeats'])){?>
              <div class="panel seatsExam">
                  <div class="panel-h"><p>Entrance Exam</p></div>
                  <div class="panel-body" style="display: none">
                      <ul>
                      <?php foreach ($seats['examWiseSeats'] as $key => $val) {
                              $hideClass = "";
                              if($key > 4){
                                $hideClass = "hidele";
                              }
                          ?>
                          <li><p class="<?=$hideClass?>"><?=$val['exam']?><strong><?=$val['seats']?></strong></p></li>
                      <?php } ?> 
                          <?php if(count($seats['examWiseSeats']) > 5){?>
                          <a class="link-blue-medium v-more expand-view">View all</a>
                          <?php } ?>                                                   
                      </ul>
                      
                  </div>
              </div>
              <?php } ?>

              <?php if(!empty($seats['domicileWiseSeats'])){?>
              <div class="panel seatsDomicile">
                  <div class="panel-h"><p>Domicile<?php if(!empty($seats['relatedStates'])) {?><i class="clg-sprite clg-inf seatsInfoTip"></i><?php } ?></p></div>
                  <div class="panel-body" style="display: none">
                      <ul>
                        <?php foreach ($seats['domicileWiseSeats'] as $key => $val) {
                          $hideClass = "";
                              if($key > 4){
                                $hideClass = "hidele";
                              }
                          ?>
                          <li><p class="<?=$hideClass?>"><?=$val['category']?><strong><?=$val['seats']?></strong></p></li>
                        <?php } ?> 
                         <?php if(count($seats['domicileWiseSeats']) > 5){?>
                          <a class="link-blue-medium v-more expand-view">View all</a>
                          <?php } ?>                         
                      </ul>                      
                  </div>
              </div>
              <?php } ?>
          </div>
          <?php } ?>
      </div>
      <div style="display:none" id="seatsRelatedStates">
      <div class="glry-div amen-div">
          <div class="hlp-info">
              <div class="loc-list-col">
                  <div class="prm-lst">
                  <div class="amen-box">
                    <?php if(!empty($seats['relatedStates'])) {?>
                    <p class="head-L3">Domicile</p>
                    <p class="para-L3"><?=$seats['relatedStates'];?></p>
                    <?php } ?>
                    </div>
                  </div>
              </div>
          </div>
      </div>

        
      </div>
  </div>