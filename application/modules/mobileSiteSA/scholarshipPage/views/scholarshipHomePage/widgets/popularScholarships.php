<section class="ps bg__white">
  <h2 class="fnt16_bold clr__3">Popular Scholarships to Study Abroad</h2>
  <div class="sld_card">
    <ul class="popular__ul">
       <?php foreach ($popularScholarships as $key=>$value) {  ?>
       <li>
          <div class="ndiv">
            <p class="sch_ttl">
              <a href="<?php echo $value['seoUrl'];?>" class="fnt12_bold clr__blue"> <?php echo formatArticleTitle(htmlentities($value['saScholarshipName']),50);?> </a>
            </p>
            <div class="schrl__dtls">
                <div class="hf mb__10">
                  <p class="fnt__12 clr__6">Scholarship amount <strong class="block clr__3"><?php echo ($value['saScholarshipAmount']>0) ? 'Rs '.moneyFormatIndia($value['saScholarshipAmount']).'/-' : 'Amount not available';?></strong> </p>
                  <?php if($value['saScholarshipAwardsCount']>0){?>
                    <p class="fnt__12 clr__9">(<?php echo $value['saScholarshipAwardsCount']. " student ". ($value['saScholarshipAwardsCount']==1 ? 'award' : 'awards') ?>)</p>
                  <?php } ?>
                </div>
                <p class="fnt__12 clr__6 mb__10">Scholarship type<strong class="block clr__3"><?php echo ucfirst($value['saScholarshipType']);?> Based</strong></p>
                <p class="fnt__12 clr__6">Applicability <strong class="ib__block clr__3"><?php echo $value['saScholarshipUniversity']; if($value['countryCount']>=3){echo " + ".($value['countryCount']-2)." more";} ?></strong></p>
            </div>
            <a href="<?php echo $value['seoUrl'];?>" class="btn_new p_btn fnt12_bold" onclick="gaTrackEventCustom('ScholarshipHomePage','ViewAndApply');">View &amp; Apply</a>
          </div>
       </li>
       <?php } ?>
    </ul>
  </div>
</section>