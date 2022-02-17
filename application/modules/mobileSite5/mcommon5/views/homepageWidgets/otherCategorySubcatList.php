<ul class="lbl02" id="_ct<?php echo $key?>">
    <?php if(!empty($collegeCutoffData)){ ?>
    <li class="last collegecutoffPage">
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
    <li>
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

<?php $i = 1;foreach($subStreamsAnsSpec as $id=>$data){?>
    
    <li <?php if($i == 1){?> class="first" <?php }else if($i > 10){?> class="_ct<?php echo $key?>" style="display: none;" <?php }else if($i == count($subStreamsAnsSpec)){?> class="last" <?php }?>>
        <a class="tabClk">
            <div class="mba-exam">
                <div>
                    <span><?php echo $data['name']?></span>
                </div>
                <span>
                    <i>&#x000BB</i>
                </span>
            </div>
            <?php 
            if($data['listType'] == 'subStream'){
            ?>
                <input type="hidden" value="<?php echo $key.'_'.$data['id']; ?>" class="stream_subStream" />
            <?php 
            }elseif($data['listType'] == 'specialization'){
            ?>
                <input type="hidden" value="<?php echo $key.'_'.$data['id']; ?>" class="stream_spec" />
            <?php 
            }
            ?>
            <div class="select-Class">
                <select name="tabLocationSelect_<?php echo $data['id']?>" max-limit="15" show-search="1" id="tabLocationSelect_<?php echo $data['id']?>" style="display:none;" append-selected-value="1"></select>
            </div>
        </a>
    </li>
    <?php $i++;} if($i>10){?>
    <li class="last">
        <a>
            <div class="mba-exam">
                <div class="view-more">
                    <span data-cls="_ct<?php echo $key?>">View More</span>
                </div>
            </div>
        </a>
    </li>
    <?php }?>
</ul>