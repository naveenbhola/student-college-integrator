<?php 
    $reasonJoin = unserialize(base64_decode($whyJoin));
    if(!empty($reasonJoin['0']['0']['details'])){
    ?>

<div class="wdh100">

                                <div class="wdh100">
                                    <h3><div class="nlt_head Fnt14 bld mb10">Why join <?php echo $details['title']."?";?></div></h3>
                                    <div class="mlr5">
                                        <div class="mb5">
                                            <?php if(!empty($reasonJoin['0']['0']['photoUrl'])){?>
                                        	<img src="<?php echo $reasonJoin['0']['0']['photoUrl']?>" border="0" class="mb10" />
                                                <?php }?>
                                        	<!--Here are some great reasons to apply at--> <?php //echo $details['title'];?>
                                        </div>
                                        <div class="clear_B">&nbsp;</div>
                                        <div>
                                            <table cellpadding="0" cellspacing="0" border="0" id="why_join_container"><tr><td valign="top">
                                            <?php //echo insertWbr($reasonJoin['0']['0']['details'],75);?>
					    <?php echo wordwrap($reasonJoin['0']['0']['details']);?>
                                            </td></tr></table>
                                        </div>
                                        <div class="clear_B">&nbsp;</div>
                                    </div>
                                    <div class="lineSpace_10">&nbsp;</div>
                                    <?php if($details['packType']=='1'||$details['packType']=='2'){?>
                                    <div><a href="javascript:void(0)" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_WHY_JOIN_APPLY_BTN_CLICK'); focusOnField()" style="text-decoration:none;color:#0065DE" /><strong>Apply Now &raquo;</strong></a></div>
									<!--<div><input type="button" value="&nbsp;" class="sprt_nlt_btn nlt_btn4" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_WHY_JOIN_APPLY_BTN_CLICK'); focusOnField()" /></div>-->
                                    <?php }?>
                                </div>
</div>
<div class="lineSpace_20">&nbsp;</div>
<?php }?>
