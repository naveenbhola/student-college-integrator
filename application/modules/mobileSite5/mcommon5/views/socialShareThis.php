<?php if($pageType=='ampPage'){?>
	<div class="table social-share-widget <?php echo $className;?>">
	    <div class="table-cell social-share-widget-content">
	        <span class="txtmsg">Share this: </span>
	        <div class="addthis_inline_share_toolbox">
        		<amp-addthis width="200" height="65" style="overflow:hidden" data-pub-id="ra-5c1b59506e2754b7" data-widget-id="30du"></amp-addthis>
	        </div>
	    </div>
	</div>

<?php }else{?> 
	<div class="table social-share-widget <?php echo $className;?>">
	    <div class="table-cell social-share-widget-content">
	        <span class="txtmsg">Share this: </span><div class="addthis_inline_share_toolbox"></div>
	    </div>
	</div>
<?php }?>