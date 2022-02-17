<?php
    $newsData    = $newsArticleWidgetData['newsData'];
    $articleData = $newsArticleWidgetData['articleData'];

    if(!($newsData || $articleData))
        return;
?>

<section class="content-wrap2 clearfix">
<div id="newsArticleWidgetTabs" class="expg-m-tab">
<?php if($newsData) { ?>
    <a class="active" href="javascript:void(0);" tabname="news">News</a>
    <?php }
    if($articleData){ ?>
    <a <?php if(empty($newsData)) echo "class='active'"?> href="javascript:void(0);" tabname="article">Articles</a>
    <?php } ?>
</div>

<?php if($newsData) { ?>
<div id="rs_newsWidget" class="flexslider newArticleSliderWidget" style="padding-left: 5px; padding-right: 5px;">
  <ul class="slides" style="padding:5px 0px;">

        <?php foreach ($newsData as $newsDataItem){ 
            $this->load->view("widgets/newsArticleSliderTuple", array("tupledata" => $newsDataItem, "widgetType" => "news", "totalSlides" => count($newsData)));
         } ?>
        
    </ul>
</div>
<?php } 
    if($articleData) { ?>

<div id="rs_articleWidget" class="flexslider newArticleSliderWidget" style="padding-left: 5px; padding-right: 5px;">
  <ul class="slides" style="padding:5px 0px;">
        <?php foreach ($articleData as $articleDataItem){ 
            $this->load->view("widgets/newsArticleSliderTuple", array("tupledata" => $articleDataItem, "widgetType" => "article", "totalSlides" => count($articleData)));
         } ?>
        
    </ul>
</div>
<?php } ?>
</section>