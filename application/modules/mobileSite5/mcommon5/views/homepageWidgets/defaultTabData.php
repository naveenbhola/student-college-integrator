<ul class="lbl02" id="_ct<?php echo $key?>">
    <?php if(!empty($collegeCutoffData)){ ?>
    <li class="last collegecutoffPage" >
        <a>
            <div class="mba-exam">
            <div>
                <span><?php echo $collegeCutoffData['name']?></span>
            </div>
            <span>
                <i>&#x000BB</i>
            </span>
            </div>
        </a>
    </li>
    <?php } ?>
    <li class="last">
        <a class="tabClk">
            <div class="mba-exam">
            <div>
                <span>All <?php echo $streamName?></span>
                <input type="hidden" value="<?php echo $key; ?>" class="stream_only" />
                <div class="select-Class">
                    <select name="tabLocationSelect_<?php echo $key?>" max-limit="15" show-search="1" id="tabLocationSelect_<?php echo $key?>" style="display:none;" append-selected-value="1"></select>
                </div>
            </div>
			<span>
                <i>&#x000BB</i>
            </span>
            </div>
        </a>
    </li>
</ul>