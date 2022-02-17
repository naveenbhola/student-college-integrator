<!-- Counseler slider Section -->
<div class="apl-widgt vid-sec apl-box">
    <p class="apl-hed">Meet some of our counselors</p>
    <p class="apl-cptn">Get access to India's leading counselors from the comfort of your home</p>
  <div class="cnslr-sldr">
      <ul class="anim-box">
      <?php 
      foreach ($counselorWidgetData as $key=>$value) {
        //_p($value);
      ?>
        <li>
          <div class="cnslr-prf clearfix">
            <div class="cnsl-img"><img src="<?php echo MEDIAHOSTURL.resizeImage($value['counsellor_image'],'160x160') ?>"/></div>
            <div class="cnsl-det">
                <strong><?php echo $value['counsellor_name'] ?></strong>
                <label><?php echo $value['counsellor_expertise'] ?></label>
            </div>
          </div>
            <div class="cns-list">
                <div class="cnslr-list">
                    <label>Overall Rating</label>
                    <section class="cnsl-rtng"><strong class="overall"><?php echo $value['counselorRatings']['overAllRating'] ?></strong></section>
                </div>
                <div class="cnslr-list">
                    <label>Responsiveness
                       <span class="info-ico hlpTxt" key="responsiveness<?php echo $key; ?>"></span>
                       <span class="input-helper" id="responsiveness<?php echo $key; ?>">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselor getting back to students as per requirements.</span>
                        </span>
                    </label>
                    <section class="prgs-dv">
                        <div class="myProgress">
                            <p class="myBar" style="width: <?php echo $value['counselorRatings']['responseRating']*10 ?>%"></p>
                        </div>
                        <strong><?php echo $value['counselorRatings']['responseRating'] ?>/10</strong>
                    </section>
                </div>
                <div class="cnslr-list">
                    <label>Knowledge
                        <span class="info-ico hlpTxt" key="knowledge<?php echo $key; ?>"></span>
                        <span class="input-helper" id="knowledge<?php echo $key; ?>">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates counselors understanding on country, courses, applications & visa procedure.</span>
                        </span>
                    </label>
                    <section class="prgs-dv">
                        <div class="myProgress">
                            <p class="myBar" style="width: <?php echo $value['counselorRatings']['knowledgeRating']*10 ?>%"></p>
                        </div>
                        <strong><?php echo $value['counselorRatings']['knowledgeRating'] ?>/10</strong>
                    </section>
                </div>
                <div class="cnslr-list">
                    <label>Guidance
                        <span class="info-ico hlpTxt" key="guidance<?php echo $key; ?>"></span>
                        <span class="input-helper" id="guidance<?php echo $key; ?>">
                            <span class="up-arrow"></span>
                            <span class="helper-text">Indicates how well the counselor guided students.</span>
                        </span>
                    </label>
                    <section class="prgs-dv">
                        <div class="myProgress">
                            <p class="myBar" style="width: <?php echo $value['counselorRatings']['guidanceRating']*10 ?>%"></p>
                        </div>
                        <strong><?php echo $value['counselorRatings']['guidanceRating'] ?>/10</strong>
                    </section>
                </div>
            </div>
            <div class="vw-prfl">
                <a href="<?php echo SHIKSHA_STUDYABROAD_HOME.$value['seoUrl'] ?>">View Counselor Profile</a>
            </div>
        </li>
        <?php 
        }
        ?>
      </ul>
  </div>
</div>
<!---Counseler slider Section--- -->