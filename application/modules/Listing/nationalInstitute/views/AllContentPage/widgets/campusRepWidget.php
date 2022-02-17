<!--
<div class="right-panel" id="rightUsersWidgetParent" style=""><div class="panel-head">
     <h2 class="headp-p">Current Students<span></span></h2>
     <span>Ask questions to the current students</span>
   </div>
   <div class="u-list" id="rightUsersWidget">
<div class="scrollbar" style=""><div class="track" style="">
<div class="thumb" style=""><div class="end"></div></div></div></div>
<div class="viewport" style="">
<div class="overview" style="top: 0px;">
<ul id="rightWidgetList">
   <?php foreach ($campusReps as $rep){ ?>
   <li class="cta-block">
        <div class="c-block">
              <span class="new-avatar">
                          <?php
                            if($rep['imageURL'] != '' && strpos($rep['imageURL'],'photoNotAvailable') === false){
                                $rep['imageURL'] = addingDomainNameToUrl(array('url' => $rep['imageURL'] , 'domainName' => MEDIAHOSTURL));
                              ?>
                                <img src="<?=getSmallImage($rep['imageURL'])?>" alt="Shiksha Ask &amp; Answer" style="width: 60px;height: 60px;" />
                          <?php }else{ ?>
                                <?= ucwords(substr($rep['displayName'],0,1)); ?>
                          <?php } ?>

              </span>
              <div class="c-inf">
                                   <span class="avatar-name"><?=$rep['displayName']?></span>
              </div>
        </div>
   </li>
   <?php } ?>
   </ul>
</div>
</div>
</div></div>
<div class="candy-l"></div>
-->
         <script>
                <?php foreach ($campusReps as $rep){ ?>
		$j('span[id="currentStudent_<?=$rep['userId']?>"]').show();
                <?php } ?>

		<?php if(count($campusReps)>3){ ?>
		//$j("#rightUsersWidget").tinyscrollbar();
		<?php } ?>
        </script>

