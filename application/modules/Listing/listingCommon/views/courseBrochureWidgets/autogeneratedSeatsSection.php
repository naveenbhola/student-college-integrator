<div class="cmn-card mb2">
    <h2 class="f20 clor3 mb2 f-weight1">Seats Info</h2>
    <div class="seats mb2">
        <?php if(!empty($seats['totalSeats'])) { ?>
            <div>
              <p class="max-seats"><?=$seats['totalSeats']?><span>Total seats</span></p>
            </div>
        <?php } ?>
    </div>
    <?php if(!empty($seats['categoryWiseSeats']) || !empty($seats['examWiseSeats']) || !empty($seats['domicileWiseSeats']) ) { ?>
      <div class="seats-count">
      <?php if(!empty($seats['categoryWiseSeats'])){?>
            <div class="seats">
               <p class="f16 clor6 f-bold">Category</p>
                <ul class="flex-box">
                <?php foreach ($seats['categoryWiseSeats'] as $key => $val) {?>
                        <li>
                           <p><?=$val['seats']?><span><?=$val['category']?></span></p>
                        </li>
                <?php } ?>
                </ul>
             </div>
      <?php } ?>

      <?php if(!empty($seats['examWiseSeats'])){?>
            <div class="seats">
               <p class="f16 clor6 f-bold">Entrance Exam</p>
                <ul class="flex-box">
                <?php foreach ($seats['examWiseSeats'] as $key => $val) {?>
                        <li>
                           <p><?=$val['seats']?><span><?=$val['exam']?></span></p>
                        </li>
                <?php } ?>
                </ul>
             </div>
      <?php } ?>

      <?php if(!empty($seats['domicileWiseSeats'])){?>
            <div class="seats">
               <p class="f16 clor6 f-bold">Domicile</p>
                <ul class="flex-box">
                <?php foreach ($seats['domicileWiseSeats'] as $key => $val) {?>
                        <li>
                           <p><?=$val['seats']?><span><?=$val['category']?></span></p>
                        </li>
                <?php } ?>
                </ul>
             </div>
      <?php } ?>
      </div>
    <?php } ?>
</div>