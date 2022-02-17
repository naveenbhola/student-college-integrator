

<div class="row x_title" style="margin-left:0px !important">
    <div class="row tile_count">
      <div class="row top_tiles">
        <?php
        if(in_array($userDataArray['groupId'],$teamGroupIds['admin'])){
          //_p($topTiles);die;
          foreach ($topTiles as $heading => $topTiles ) { ?>
            <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12"> <h3><?php echo $heading; ?></h3></div>
            <?php foreach ($topTiles as $key => $value) { ?>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
              <div >
                <div class="count " title="Requests (Tasks)" style="font-size:30px !important">
                    <div class="" id = "" style="position:relative;width:25% !important;float: left;">
                      <a  id='<?php echo $value['id']; ?>' target='_blank'  href='<?php echo $value['href'];  ?>'>0</a>  
                    </div>
                    <div class="" style="position:relative;margin-top: 10px;height:100px !important;width:75% !important;float: left;" id='<?php echo $value['id'].'_label'; ?>'  ></div>
                </div>
              </div>
                <div style="clear:both">
                  <h2 style="width:100%">  &nbsp;  <?php echo $value['topTileHeading'];?></h2>
                  <p><?php echo $value['topTileDetails']; ?></p>
                </div>
              
              </div>
            </div>  
           <?php }
            ?>
          <?php  } 
        }else if(in_array($userDataArray['groupId'],$teamGroupIds['salesTeam'])){ ?>
          <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
              <div >
                <div class="count" title="Requests (Tasks)" style="font-size:30px !important" >
                    <div class="" id = "" style="position:relative;width:25% !important;float: left;">
                      <a  id='<?php echo $topTiles['requestedCreated']['id']; ?>' target='_blank'  href='<?php echo $topTiles['requestedCreated']['href'];  ?>'>0</a>
                    </div>
                    <div class="" style="position:relative;margin-top: 10px;height:50px !important;width:75% !important;float: left;" id='<?php echo $topTiles['requestedCreated']['id'].'_label'; ?>'  >
                    </div>
                </div>
              </div>
                <div style="clear:both">
                  <h2 style="width:100%">  &nbsp;  <?php echo $topTiles['requestedCreated']['topTileHeading'];?></h2>
                  <p><?php echo $topTiles['requestedCreated']['topTileDetails']; ?></p>
                </div>
              </div>              
          </div>
          <div class="clearfix"></div>
          <div class="x_title" style="font-size:20px">
            <b>Request Tasks:</b>
          </div>
            <?php
          foreach ($topTiles as $key => $value ) {
            if($key == 'requestedCreated'){
              continue;
            }
           ?>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
              <div >
                <div class="count" title="Requests (Tasks)" style="font-size:30px !important">
                  <div class="" id = "" style="position:relative;width:25% !important;float: left;">
                      <a  id='<?php echo $value['id']; ?>' target='_blank'  href='<?php echo $value['href'];  ?>'>0</a>  
                    </div>
                    <div class="" style="position:relative;margin-top: 10px;height:100px !important;width:75% !important;float: left;" id='<?php echo $value['id'].'_label'; ?>'  ></div>
                </div>
              </div>
                <div style="clear:both">
                  <h2 style="width:100%">  &nbsp;  <?php echo $value['topTileHeading'];?></h2>
                  <p><?php echo $value['topTileDetails']; ?></p>
                </div>
              </div>
            </div>
        <?php  }
        }else{
          foreach ($topTiles['taskAssignedToUser'] as $key => $value ) { ?>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
              <div >
                <div class="count" title="Requests (Tasks)" style="font-size:30px !important" ><div class="" id = "" style="position:relative;width:25% !important;float: left;">
                      <a  id='<?php echo $value['id']; ?>' target='_blank'  href='<?php echo $value['href'];  ?>'>0</a>  
                    </div>
                    <div class="" style="position:relative;margin-top: 10px;height:100px !important;width:75% !important;float: left;" id='<?php echo $value['id'].'_label'; ?>'  ></div></div>
              </div>
                <div style="clear:both">
                  <h2 style="width:100%">  &nbsp;  <?php echo $value['topTileHeading'];?></h2>
                  <p><?php echo $value['topTileDetails']; ?></p>
                </div>
              
              </div>
            </div>
        <?php  } ?>
        <div class="clearfix"></div>
        <?php foreach ($topTiles['pendingTaskToUser'] as $key => $value ) { ?>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
              <div class="tile-stats">
              <div >
                <div class="count" title="Requests (Tasks)" style="font-size:30px !important"><div class="" id = "" style="position:relative;width:25% !important;float: left;">
                      <a  id='<?php echo $value['id']; ?>' target='_blank'  href='<?php echo $value['href'];  ?>'>0</a>  
                    </div>
                    <div class="" style="position:relative;margin-top: 10px;height:100px !important;width:75% !important;float: left;" id='<?php echo $value['id'].'_label'; ?>'  ></div></div>
              </div>
                <div style="clear:both">
                  <h2 style="width:100%">  &nbsp;  <?php echo $value['topTileHeading'];?></h2>
                  <p><?php echo $value['topTileDetails']; ?></p>
                </div>
              </div>
            </div>
        <?php  }
      } ?>
      </div>
    </div>
</div>