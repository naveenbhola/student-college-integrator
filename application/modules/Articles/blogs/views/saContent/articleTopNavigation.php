<?php
//_p($levelTwoNavBarData);
if(!empty($levelTwoNavBarData))
{
    ?>
    <section class="apl-contnt-sec clearfix">
        <div class="apl-contnt">
            <h1 class="newApply-heading" itemprop="headline name">
              <?php echo $levelTwoNavBarData['title'];?>
               </h1>

            <div class="headNav-wrap nav__block" id="TabSection">
              <a href="javascript:void(0);" class="nav__back" style="display:none;"> <i class="arrow left"></i> </a>
                <ul class="apply-headNav nav-bar-list">

                     <?php
                  foreach ($levelTwoNavBarData['navData'] as $navItem)
                  {

                      ?>
                      <li>
                        <a  <?php echo ($navItem['content_id']==$content['data']['content_id']?'class="active"':''); ?> href="<?php echo SHIKSHA_STUDYABROAD_HOME.($levelTwoNavBarData['navUrl'][$navItem['content_id']]);?>">
                              <?php echo $navItem['title'];?>
                          </a>
                      </li>
                      <?php
                  }
                  ?>
                </ul>
                <a href="javascript:void(0);" class="nav__nxt"> <i class="arrow right"></i> </a>
            </div>
        </div>
    </section>
    <?php
}
?>
