<?php 

if(!empty($topNavData['content_type_title']) && !empty($topNavData['links_data']))
{
?>
<div class="wrapper mb10">
  <div class="content-NavHead">
    
    <h1 itemprop="headline name">
    <?php  echo $H1Title;
  
    ?></h1>
  </div>

  <div class="content-quickLink">
    <div class="content-grd"><i class="arrow right"></i></div>
    <div class="content-qckLins">
      <ul>
        <?php 
        foreach ($topNavData['links_data'] as $linkContentId => $linkData) {
        ?>
          <li><a <?php echo ($linkContentId==$contentId?'class="virtual_active"':''); ?> href="<?php echo $linkData['url']; ?>"><?php echo htmlentities($linkData['label']); ?></a></li>
        <?php 
        }
        ?>
      </ul>
    </div>
  </div>
</div>
<?php
}
?>