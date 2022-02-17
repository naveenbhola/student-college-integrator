<?php 

if(!empty($topNavData['content_type_title']) && !empty($topNavData['links_data']))
{
?>
<section class="exm-contnt-sec clearfix">
  <div class="exm-contnt">
  <h1 class="newExam-heading" itemprop="headline name">
    <?php echo $H1Title
    ?></h1>
  <div class="headNav-wrap">
  <ul class="exam-headNav">
    <?php 
    foreach ($topNavData['links_data'] as $linkContentId => $linkData) {
    ?>
      <li><a <?php echo ($linkContentId==$contentDetails['content_id']?'class="active"':''); ?> href="<?php echo $linkData['url']; ?>"><?php echo htmlentities($linkData['label']); ?></a></li>
    <?php 
    }
    ?>
  </ul>
  </div>
</div>
</section>
<?php
}
?>