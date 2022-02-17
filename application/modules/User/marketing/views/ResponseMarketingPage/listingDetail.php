<div class="layer-outer" style="width:1000px; font-family: Trebuchet MS;">
    <div class="layer-title">
        <h4><?php echo $institute->getName(); ?></h4>
        <span class="close"><a title="Close" href="#" onclick="messageObj.close(); return false;">x</a></span>
    </div>
    <iframe width="1000" height="5000" style="overflow-x: hidden;" src="<?php echo $url; ?>?inlineView=1" id="listingDetailFrame"></iframe>
</div>