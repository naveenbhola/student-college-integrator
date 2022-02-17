<?php
function formatTitle($content, $charToDisplay) {
        if(strlen($content) <= $charToDisplay)
            return($content);
        else
            return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))."...") ;
}
?>
<div class="cateQuickLinkBlock">
            <h2>Popular Articles</h2>
            <ul id ="bxsliderquicklinks" style="height:60px;overflow:hidden;">
            <?php foreach($articleWidgetsData as $data) :?>
            <li>
            <div class="spacer10 clearFix"></div>
            <div class="quickLinkFigure"><img src="/public/images/category_widget_images/<?=$categoryID.".jpg"?>" alt="" /></div>
            <div class="quickLinkContent" id="articlePlaceHolder">
            <a href="<?php echo $data['articleURL']; ?>" title="<?=$data['articleTitle']?>">
            <?php         
            	echo formatTitle($data['articleTitle'], 52); ?>
            </a>
            </div>
            <div class="clearFix"></div>
            </li>
            <?php endforeach;?>
            </ul>
</div>
