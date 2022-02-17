  <div class="cmnst-container">
      <!--comment secion heading-->
       <div class="notification-col">
          <div class="c-box" style='width:87%;'><p class="c-titl">NOTIFICATIONS</p></div>
          <div class="c-cls" style='z-index:1000;width:8%;text-align:right;'><a href="javascript:void(0)" data-enhance="false" data-rel="back" onclick='updateNotification();$("#anaNotificationsCount").html(0);'>&times;</a></div>
       </div>
      <!--comment section card view--> 
       <div class="cmnts-show">
         <div class="notify-column">
         <!--notification list begins-->
          
            <?php 

            foreach ($data as $key => $notificationData) {
              $aleady_visit = "";
              if($notificationData['readStatus'] === false)
                $aleady_visit = "already-visit";

              if($notificationData['landingURL'] != ""){
                ?>
                  <div class="notify-box <?=$aleady_visit;?>" onclick='window.location = "<?php echo $notificationData['landingURL'];?>"'>
                <?php
              } else{
                ?>
                  <div class="notify-box <?=$aleady_visit;?>">
                <?php
              }
              ?>

              
                <p class="notify-show"><?=$notificationData['messageDescription'];?></p>
                <span class="time-slot"><?=$notificationData['time'];?></span>
              </div>
              <?php
            }

            ?>
            
          
     </div>
       </div>
  </div>
  <?php
    if(empty($data)){
        ?>
        <div class='notify-show' style='min-height:150px;text-align:center;margin:100px 0;font-size:14px;'>
          <p style="margin-top:50%;">
            <?=$emptyNotifications;?>
          </p>
        </div>
        <?php
    }
  ?>


