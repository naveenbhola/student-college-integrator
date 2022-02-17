<div class="footer-dv">
  <a href="#searchLayerContainer" data-rel="dialog" data-transition="slide">
    <div class="footer-search">
       <p class="footr-field">Search College, Course or Exam</p>
       <!-- <input type="text" placeholder="Search College, Course or Exam"> -->
       <div class="src-box"><i class="srch-icon"></i></div>
    </div>
  </a>
  <div class="footer-section">
      <div class="footer-left">
          <ul>
              <?php 
              foreach ($footerData as $group => $groupData) {
              ?>
              <li>
                  <label for="footMenu1" class="footer-accordion-label footerTab-div fnt-wt"><?php echo $group; ?>
                    <label class="drop-icon" for="footMenu1"></label>
                  </label>
                  <div class="footer-accordion-div footer-subMenu">
                      <?php 
                      $separatorHTML = '';
                      foreach ($groupData as $key => $linkData) {
                        echo $separatorHTML;
                        ?>
                        <a href="<?php echo $linkData['url']; ?>"><?php echo $linkData['text']; ?></a> 
                        <?php 
                        $separatorHTML = '<span>|</span>';
                      }
                      ?>
                  </div>
              </li>
              <?php 
              }
              ?>
          </ul>
      </div>
      <div class="footer-right">
         <div class="rgt-cont">
              <ul>
                  <li>
                      <label for="footMenuCnt" class="footerTab-div fnt-wt footer-accordion-label">Contact Us
                        <label class="drop-icon" for="footMenuCnt"></label>
                      </label>
                      <div class="cnt-det footer-subMenu footer-accordion-div">
                          <p>Students Helpline</p>
                          <p><i class="phone-icn"></i><a class="hlp-phn" href="tel:<?php echo ABROAD_STUDENT_HELPLINE; ?>"><?php echo ABROAD_STUDENT_HELPLINE; ?></a></p>
                          <p class="font-12">(09:30 AM to 06:30 PM, Monday to Friday)</p>
                          <a href=" mailto:studyabroad@shiksha.com" class=""><i class="mail-icn"></i>studyabroad@shiksha.com</a>
                          <ul class="social-links">
                              <li><a href="https://www.facebook.com/studyabroad.shiksha" target="_blank"><i class="fb-icn"></i></a></li>
                              <li><a href="https://www.youtube.com/channel/UCBM1K45nZFcDPDNFkweclNw" target="_blank"><i class="yutube-icn"></i></a></li>
                          </ul>
                          <p class="ent-lgn"><a href="<?=ENTERPRISE_HOME?>">Enterprise Login</a></p>
                          <div>
                            <input type="checkbox" class="toggledata" name="toggledata" id="toggle1">
                            <p class="enqury-dv"> 
                              <label for="toggle1"> For Advertising/Sales Enquiries </label>
                            </p>
                            <div class="enqury-info">
                               <p>Nandita Bandopdhyay</p>
                                <i class="mail-icn"></i><a href="mailto:nandita@shiksha.com">nandita@shiksha.com</a>
                            </div>
                          </div>
                          
                      </div>
                  </li>
              </ul>
               
                 
         </div>
         <div class="rgt-logo">
         </div>
      </div>
  </div>
  <div class="footer-graphics">
      <div class="grphc-img"></div>
  </div>
  <?php 
  $partnerSites = array(
    'Infoedge.in' => 'http://www.infoedge.in',
    'Shiksha.com' => 'http://www.shiksha.com',
    'Naukri.com' => 'http://www.naukri.com',
    'Firstnaukri.com' => 'http://www.firstnaukri.com',
    'Naukrigulf.com' => 'http://www.naukrigulf.com',
    '99acres.com' => 'http://www.99acres.com',
    'Jeevansathi.com' => 'http://www.jeevansathi.com',
    'AmbitionBox.com' => 'http://www.ambitionbox.com',
    'Zomato.com' => 'http://www.zomata.com',
    'Policybazaar.com' => 'http://www.policybazaar.com',
    'Meritnation.com' => 'http://www.meritnation.com',
  );
  ?>
  <div class="footer-bottom">
      <div class="part-left">
          <ul>
              <li>
                  <label for="partMenu" class="footerTab-div fnt-wt footer-accordion-label">Partner Sites
                    <label class="drop-icon white-icn" for="partMenu"></label>
                  </label>
                  <div class="footer-subMenu footer-accordion-div">
                      <?php 
                      $separatorHTML = '';
                      foreach ($partnerSites as $label => $link) {
                        echo $separatorHTML;
                        ?>
                        <a href="<?=$link?>"><?=$label?></a>
                        <?php 
                        $separatorHTML = '<span>|</span>';
                      }
                      ?>
                      <p class="grp-cmpnyTxt">A <i class="naukri-logo"></i> Group Company</p>
                  </div>
              </li>
          </ul>
      </div>
      <div class="part-right">
          <p>A <i class="naukri-logo"></i> Group Company</p>
      </div>
  </div>
  <div class="footer-rights">
      <div class="policy-div">
         <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/privacyPolicy">Privacy Policy</a> <span class="footr-bullet">&#8226;</span>
         <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition">Terms and Conditions</a> <span class="footr-bullet">&#8226;</span>
         <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/grievances">Grievances</a> <span class="footr-bullet">&#8226;</span>
         <a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/summonsNotices">Summons & Notices</a> <span class="footr-bullet">&#8226;</span>
         <a href="https://aboutus.shiksha.com">About Us</a>
      </div>
      <div class="border-bottom"></div>
      <div class="policy-trdmrk">
          <p>All trademarks belong to the respective owners. Copyright © <?=date('Y')?> Info Edge India Ltd. All rights reserved.</p>
      </div>
  </div>
</div>