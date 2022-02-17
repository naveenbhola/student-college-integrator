<?php
/**
 * Created by PhpStorm.
 * User: prateek
 * Date: 4/1/19
 * Time: 10:46 AM
 */

if(!empty($levelTwoNavBarData))
{
    ?>
    <div class="article-nav" id="tab-section">
          <div class="article-navHead content-NavHead">
            <h1><?php echo $levelTwoNavBarData['title'];?></h1>
          </div>
          <div class="nav-List content-quickLink expanded">
              <span class="expnd-circle"><span class="ib-circle"><i class="expnd-switch"></i></span></span>
              <ul class="navList-items" id="navContainer"
                  style="transition: transform 150ms ease-out 0s; transform: translate3d(0px, 0px, 0px);">
                  <?php
                  foreach ($levelTwoNavBarData['navData'] as $navItem)
                  {

                      ?>
                      <li <?php echo ($navItem['content_id']==$content['data']['content_id']?'class="active"':''); ?>><a href="<?php echo SHIKSHA_STUDYABROAD_HOME.($levelTwoNavBarData['navUrl'][$navItem['content_id']]);?>">
                              <?php echo $navItem['title'];?>
                          </a>
                      </li>
                      <?php
                  }
                  ?>
              </ul>
          </div>
      </div>
    <?php
}
?>
