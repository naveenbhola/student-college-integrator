<?php
$newsData    = $newsArticleWidgetData['newsData'];
$articleData = $newsArticleWidgetData['articleData'];

if(!($newsData || $articleData))
    return;
?>
<div class="content-tupple expg-widget">
    <div class="expg-tab" id="newsArticleWidgetTabs">
        <?php if($newsData){ ?>
            <a href="javascript:void(0);" tabname="news" class="active">News</a>
        <?php } 
        if($articleData){ ?>
            <a href="javascript:void(0);" tabname="article" <?php if(empty($newsData)) {echo "class='active'";} ?>>Articles</a>
        <?php } ?>
    </div>

<?php if($newsData){ ?>
    <div id="rs_newsWidget" class="sliderwidget">
    <div class="royalSlider rsDefault">
    <?php
        for($i = 0; $i < count($newsData); $i+=2) {
    ?>
        <div class="rsContent">
            <?php if($newsData[$i]) $this->load->view("widgets/newsArticleWidgetTuple", array("tupledata" => $newsData[$i], "widgetType" => "news"));?>
            <?php if($newsData[$i+1]) $this->load->view("widgets/newsArticleWidgetTuple", array("tupledata" => $newsData[$i+1], "widgetType" => "news"));?>
        </div>
    <?php
       }
    ?>
    </div>
    <div class="expg-carausel"></div>
    </div>
<?php }
if($articleData){
?>
    <div id="rs_articleWidget" class="sliderwidget" style="<?php if($newsData) { echo 'display:none;'; } ?>">
    <div class="royalSlider rsDefault">
    <?php
        for($i = 0; $i < count($articleData); $i+=2) {
    ?>
        <div class="rsContent">
            <?php if($articleData[$i]) $this->load->view("widgets/newsArticleWidgetTuple", array("tupledata" => $articleData[$i], "widgetType" => "article"));?>
            <?php if($articleData[$i+1]) $this->load->view("widgets/newsArticleWidgetTuple", array("tupledata" => $articleData[$i+1], "widgetType" => "article"));?>
        </div>
    <?php
       }
    ?>
    </div>
    <div class="expg-carausel"></div>
    </div>
<?php }
?>
</div>
