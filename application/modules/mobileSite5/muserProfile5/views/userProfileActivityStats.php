<?php $cardPerGrid =3; 
      $cardCounter=0;
      $breakFirstLoopFlag = false;
?>


 <div class="swiper-container">
        <div class="swiper-wrapper">
           
           

            <div class="swiper-slide">
                  <?php 
                  $this->load->view('userProfileActivityStatSection',array('stats' => $stats,'cardPerGrid'=>$cardPerGrid,'cardCounter'=>$cardCounter));
                  
                  ?>
            </div>

            <?php if($statCount > ($cardPerGrid*2) ){?>
            <div class="swiper-slide">
                   <?php 
                       $this->load->view('userProfileActivityStatSection',array('stats' => $stats,'cardPerGrid'=>$cardPerGrid,'cardCounter'=>$cardPerGrid*2));?>
                       
            </div>
            <?php } ?>

             <?php if($statCount > ($cardPerGrid*4) ){?>
            <div class="swiper-slide">
                   <?php 
                       $this->load->view('userProfileActivityStatSection',array('stats' => $stats,'cardPerGrid'=>$cardPerGrid,'cardCounter'=>$cardPerGrid*4));?>
                       
            </div>
            <?php } ?>

        </div>

        <?php if($statCount > ($cardPerGrid*2) ){?>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        <?php } ?>
        
    </div>
