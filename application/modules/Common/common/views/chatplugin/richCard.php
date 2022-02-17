<?php
  foreach ($data['responses'] as $key => $value) {
    if(!empty($value['accordian'])){
?>
<div class="accordian-head">Similar questions asked on Shiksha</div>
<div class="accordian-box">
  <?php 
    foreach ($value['accordian'] as $accordian) {
  ?>
        <button class="accordion"><?php echo $accordian['name'];?></button>
        
        <div class="panel">
          <?php foreach ($accordian['text'] as $accordianRow) { ?>
          <p>
            <i>" </i><?php echo $accordianRow;?><i> "</i>
            <span>- Shiksha Contributor</span>
          </p>
          <?php } ?>
          
          <?php if(!empty($accordian['url'])) { ?>
          <a href="<?php echo $accordian['url'];?>" target="_blank">View All Answers</a>
          <?php } ?>
        </div>
        
        

  <?php
    }
  ?>
</div>
<?php
    }
    else if(empty($value['tupleList']['tuples'])){
?>
<div class="rich-card">
        <?php if($value['title']) { ?>
        <div class="main-answer">
          <span class="key"></span><strong class="value"><?php echo htmlentities($value['title']); ?></strong>
        </div>
        <?php } ?>
        <div class="answer-content">
          <div>
            <?php if($value['subTitle']) { ?>
            <strong class="main-heading"><?php echo htmlentities($value['subTitle']);?></strong>
            <?php } ?>
            <?php if($value['subTitle2']) { ?>
            <strong class="sub-heading"><?php echo htmlentities($value['subTitle2']);?></strong>
            <?php } ?>
          </div>

          <?php if($value['html']) { ?>
            <div>
              <p><?php echo $value['html'];?></p>
            </div>
          <?php } ?>

          <?php if($value['textLists']) { ?>
          <div>
            <ul>
              <?php foreach($value['textLists'] as $li) { ?>
              <li><b><?php echo htmlentities($li['name']);?></b> <?php echo htmlentities($li['text']);?></li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?>

          <!-- <?php if($value['tupleList']['tuples']) { ?>
          <div>
            <ul>
              <?php foreach($value['tupleList']['tuples'] as $li) { ?>
              <li><b><?php echo htmlentities($li['instituteName']);?></b></li>
              <?php } ?>
            </ul>
          </div>
          <?php } ?> -->

          <?php if($value['table']) { ?>
          <div>
            <table class="data-table">
              <tbody>
                <?php if($value['table']['head']) { ?>
                <tr>
                <?php foreach($value['table']['head'] as $tr) { ?>
                <th><?php echo $tr['name'];?></th>
                <?php } ?>
              </tr>
              <?php } ?>
              <?php foreach($value['table']['body'] as $tr) { ?>
                <tr>
                  <td><?php echo $tr['col1'];?></td>
                  <td><?php echo $tr['col2'];?></td>
                </tr>
              <?php } ?>
            </tbody></table>
          </div>

          <?php } ?>
        </div>
        <a class="expand hide" onclick="openContent(this);">More</a>

        <?php if(!empty($value['links'][0])) { ?>
        <div class="action-link">
          <a class="arrow" href="<?php echo $value['links'][0]['url'];?>" target="_blank" title="<?php echo htmlentities($value['links'][0]['name']);?>">View More</a>
        </div>
        <?php } ?>
        
      </div>
<?php
  }
  else if(!empty($value['tupleList']['tuples'])){
    $title = "";
    if(!empty($value['title'])){
      $title = $value['title'];
    }
    else if(!empty($value['subTitle'])){
      $title = $value['subTitle'];
    }
    else if(!empty($value['subTitle2'])){
      $title = $value['subTitle2'];
    }
?>
<div class="touple-block">
    <div class="touple-heading">
      <h3><?php echo htmlentities($title); ?></h3>
    </div>
    <div class="touple-list-cont">
      <ul class="ctp_popular touple-list">
        <?php foreach($value['tupleList']['tuples'] as $li) { ?>
         <li class="_flexirow touple-list-items">
            <a href="<?php echo $li['instituteUrl'];?>" target="_blank">
               <div class="flexi_img"><img alt="<?php echo htmlentities($li['instituteName']);?>" src="<?php echo ($li['imageUrl'] ? $li['imageUrl'] : 'https://images.shiksha.ws/public/mobile5/images/cat_default_mobile.png');?>"></div>
            </a>
            <div class="flexi_column">
               <a href="<?php echo $li['instituteUrl'];?>" target="_blank">
                  <p class="_clgname"><?php echo htmlentities($li['instituteName']);?></p>
               </a>
               <p class="ctp-cty"><i class="pwa_sprite clg-loc"></i> <?php echo $li['location'];?></p>
               <div class="ratingv1">
                  <div class="clg-col single-col">
                     <div class="clg-col single-col">
                        <span class="rating-block rvw-lyr" on="" role="button" tabindex="0">
                           <?php echo ($li['reviewMean'] ? $li['reviewMean']: '');?><i class="empty_stars starBg rvw-lyr"><i style="width:82%" class="full_starts starBg rvw-lyr"></i></i>
                        </span>
                     </div>
                  </div>
               </div>
            </div>
         </li>
        <?php } ?>
      </ul>          
    </div>
    <?php if(!empty($value['links'][0])){ ?>
    <div class="action-link">
      <a href="<?php echo $value['links'][0]['url'];?>" target="_blank"><?php echo $value['links'][0]['name'];?> <i class="arrow forward"></i></a>
    </div>        
    <?php } ?>
</div>
<?php
  }
}
?>
