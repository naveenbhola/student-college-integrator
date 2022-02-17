
<a name="videos"></a>
<div style="margin-top:10px">
    <div class="contentBT" style="padding:0 0 0 10px;line-height:25px">
        <div class="float_R txt_align_r" style="width:50px;padding-right:10px">&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo $mediaUrlvideos; ?>" class="fontSize_12p">Edit</a> ]</span></div>

        <div class="fontSize_14p"><b>Videos:</b></div>
    </div>
    <div class="lineSpace_10">&nbsp;</div>
    <div style="margin-left:10px">

<?php if(isset($videoArr) && (count($videoArr) > 0)) { ?>
        <div>
        <?php for($i=0; $i < count($videoArr); $i++){ ?>
        
            <div class="float_L" style="padding-left:10px;" id="main_doc_<?php echo $videoArr[$i]['media_id'];?>">
                <div>
                <object width="300" height="250"><param name="movie" value="<?php echo $videoArr[$i]['url']; ?>&amp;hl=en&amp;fs=1&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="wmode" value="transparent"><embed wmode="transparent" src="<?php echo $videoArr[$i]['url']; ?>&amp;hl=en&amp;fs=1&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="250" title="<?php echo trim($videoArr[$i]['name']); ?>"></embed></object>
                </div>
                <div class="bld txt_align_c">
                    <div>
                        <label id="main_doc_title_name12_<?php echo $i;?>" title="<?php echo trim($videoArr[$i]['name']); ?>"><?php echo (strlen(trim($videoArr[$i]['name'])) > 30 ? substr(trim($videoArr[$i]['name']),0,30) .'...'  : trim($videoArr[$i]['name']) ); ?></label>
                    </div>
                    <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="javascript:void(0);" onclick="renameAction('main_doc_div12_<?php echo $videoArr[$i]['media_id']; ?>','videos');"  >Rename</a> ] | [ <a href="javascript:void(0);" onclick="deleteFile('institute','<?php echo $videoArr[$i]['listing_type_id']; ?>','<?php echo $videoArr[$i]['media_id']; ?>','<?php echo $i; ?>','<?php echo $videoArr[$i]['media_id']; ?>','videos');" >Delete</a> ]
                    </span>
                    <div style="display:none;" id='error_doc_msg12_<?php echo $i;?>'></div>
                    <div style="display:none;" id="main_doc_div12_<?php echo $videoArr[$i]['media_id']; ?>">
                            Rename Video Title:<br />
                            <input type="text" id="main_doc_title12_<?php echo $videoArr[$i]['media_id']; ?>" style="width:105px" value="<?php echo trim($videoArr[$i]['name']); ?>" maxlength="50" /><br />
                            <div class="lineSpace_3">&nbsp;</div>
                            <input type="button" onclick="EditImgMedia('institute','<?php echo $videoArr[$i]['listing_type_id']; ?>','<?php echo $videoArr[$i]['media_id']; ?>','videos','main_doc_title12_<?php echo $videoArr[$i]['media_id']; ?>','<?php echo $i;?>','main_doc_div12_<?php echo $videoArr[$i]['media_id']; ?>','<?php echo trim($videoArr[$i]['name']); ?>','videos');return false" value="" class="gUpdateBtn" />
                            <input type="button" onclick="CancleImgMedia('<?php echo $i; ?>','main_doc_div12_<?php echo $videoArr[$i]['media_id']; ?>','videos');return false" value="" class="gCancelBtn" />
                    </div>
                </div>
				<div style="line-height:25px">&nbsp;</div>
            </div>
            
        <?php } ?>
            <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
        </div>
        <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
        <?php }?>
        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
    </div>
    <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
</div>
<!--End_videos-->
