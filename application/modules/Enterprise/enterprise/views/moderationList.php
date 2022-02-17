<?php if ($moderationList==NULL) { ?>
<div class="mar_top_6p">No pending moderation Listings.</div>
<?php } else { 
        $i=1;
        $k=1;
    ?>
<div class="boxcontent_lgraynoBG bld">
    <div style="background-color:#EFEFEF; border-right:1px solid;padding-left:2px;width:100%">
        <div class="float_L" style="background-color:#EFEFEF; width:5%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp;<input id="selectAll" type="checkbox" onClick="selectAllKeyIds();" /></div>
        <div class="float_L" style="background-color:#EFEFEF; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Listing_Type</div>
        <div class="float_L" style="background-color:#EFEFEF; width:30%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Name</div>
        <div class="float_L" style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Posted Date</div>
        <div class="float_L" style="background-color:#EFEFEF; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Added By</div>
        <div class="float_L" style="background-color:#EFEFEF; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;View Details</div>
        <div class="float_L" style="background-color:#EFEFEF; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Edit/Review</div>
        <div class="clear_L"></div>
    </div>
<?php
    $colorFlag = true;
    $bgcolor = '';
    foreach($moderationList as $key=>$val){
        
        if($val['listing_type']=='institute'){
            $colorFlag = !$colorFlag;
            $k=$i;
        }
        if($colorFlag){
            $bgcolor='#f7f7f9';
        }else{
            $bgcolor='#e2e2e7';
        }
?>
<div style="background-color:<?php echo $bgcolor; ?>; border-right:1px solid;padding-left:2px;width:100%">
    <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:5%; padding-top:5px; padding-bottom:6px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp;<?php if($val['status']=='queued'){ ?><input id="userNo_<?php echo $i; ?>" type="checkbox" value="<?php echo $val['listing_type'],'#',$val['listing_type_id']; ?>" name="moderateList[]" onClick="selectAllCheck();"/><?php if($val['listing_type']=='institute'){ ?>
            <input type="hidden" id="<?php echo $k; ?>_inst" /><?php } ?> 
            <input type="hidden" id="<?php echo $i; ?>_marker" value="<?php echo $k; ?>" />
            <input type="hidden" id="<?php echo $i; ?>_listTitle" value="<?php echo $val['listing_title']; ?>" />
            <?php $i++; } ?>
        </div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:10%; padding-top:8px; padding-bottom:9px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;<?php echo $val['listing_type'];?></div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:30%; padding-top:8px; padding-bottom:9px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;<?php echo $val['listing_title'];?></div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:20%; padding-top:8px; padding-bottom:9px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;<?php echo $val['submit_date'];?></div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:10%; padding-top:8px; padding-bottom:9px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;<?php echo $val['userName'];?></div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:10%; padding-top:8px; padding-bottom:8px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp; <a onCLick="return popitup('/enterprise/ShowForms/fetchPreviewPage/<?php echo $val["listing_type"],'/',$val["listing_type_id"]; ?>')" href="javascript:" >View Details</a></div>
        <div class="float_L" style="background-color:<?php echo $bgcolor; ?>; width:10%; padding-top:8px; padding-bottom:8px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;<a 
                <?php if($val["listing_type"]=="institute"){ ?>
                href='/enterprise/ShowForms/editInstituteForm/<?php echo $val["listing_type_id"]; ?>'
                <?php } 
                    if($val["listing_type"]=="course"){ ?>
                    href='/enterprise/ShowForms/showCourseEditForm/<?php echo $val["listing_type_id"]; ?>'
                    <?php } ?> 
                >Edit Listing</a></div>
        <div class="clear_L"></div>
    </div>
    <?php } ?>
</div>


<input type="hidden" id="totalUserCount" name="totalUserCount" value="<?php echo $i-1; ?>" />
<input type="hidden" id="approvalAction" name="approvalAction" value="" />

<div id="checkbox_unselect_error" style="display:none;color:red;"></div><br/>
<div id="insti_unselect_error" style="display:none;color:red;"></div><br/>

<div class="lineSpace_5">&nbsp;</div>
<div class="bld mar_full_10p" style="">
    <button class="btn-submit7 w7" id="searchScholCMS" value="Search_Schol_CMS" type="button" onclick="showHideCancelComment('hide');$('frmSelectTransact').action='/enterprise/Enterprise/approveDisapproveListing';$('approvalAction').value='APPROVE';validateFormModeration();">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Approve</p></div>
    </button>
    
    <button class="btn-submit7 w7" onclick="showHideCancelComment('show','disapprove');" type="button" value="" >
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Disapprove</p></div>
    </button>
    
    <button class="btn-submit7 w7" onclick="showHideCancelComment('show','delete');" type="button" value="">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete</p></div>
    </button>
   
</div>

<div class="lineSpace_10">&nbsp;</div>

<div id="cancelDiv" style="display:none;">
    <b> Please input reason for Disapproval .. </b><br/>
    <textarea type="text" name="disapprovalComments" id="CancelComments" minlength="0" validate="validateStr" maxlength="5000" style="height:130px;" caption="Cancel Comments" /></textarea><br/>
     
    <div id="disapproveButton" style="display:none;">
    <button class="btn-submit7 w7" value="Search_Schol_CMS" type="button" onclick="$('frmSelectTransact').action='/enterprise/Enterprise/approveDisapproveListing';$('approvalAction').value='DISAPPROVE';validateFormModeration();">
    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Disapprove Now</p></div>
    </button>
    </div>

    <div id="deleteButton" style="display:none;">
    <button class="btn-submit7 w7" value="Search_Schol_CMS" type="button" onclick="$('frmSelectTransact').action='/enterprise/Enterprise/approveDisapproveListing';$('approvalAction').value='DELETE';validateFormModeration();">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Delete Now</p></div>
    </button>
    </div>
    
    <button class="btn-submit7 w7" onclick="showHideCancelComment('hide');" type="button" value="">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Reset</p></div>
    </button>
</div>
<div class="lineSpace_5">&nbsp;</div>
<?php } ?>
