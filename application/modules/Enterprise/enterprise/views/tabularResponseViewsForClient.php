
<div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
				<div class="mar_full_10p" <?php if(count($listings) < 1) echo 'style="display:none"'; ?>>
					From <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="" readonly="true" name="top_fromdate" id="top_fromdate" />
					<img src="/public/images/cal-icn.gif" id="top_fromdate_img" onclick="daterangeFrom('top',1);" style="vertical-align: middle; cursor:pointer;" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					To <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="" readonly="true" name="top_todate" id="top_todate" />
					<img src="/public/images/cal-icn.gif" id="top_todate_img" onclick="daterangeTo('top',1);" style="vertical-align: middle; cursor:pointer;" />
					&nbsp;&nbsp;
					
					<button style="vertical-align: middle;" class="btn-submit7" type="button" uniqueattr="Enterprise/ListingsResponses/Download/top" onClick="downloadResponses('top','<?php echo $tabStatus; ?>');">
						<div class="btn-submit7"><p style="line-height: 30px;" class="btn-submit8 btnTxtBlog">Download Responses</p></div>
					</button>
				</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="mar_full_10p">
					<div class="float_R" style="padding:5px">
						<div class="pagingID" id="paginataionPlace2"></div>
						<div class="pagingID" id="paginataionPlace3"></div>
					</div>
					<div class="clear_L"></div>
				</div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <?php if(!isset($elasticFlag) && count($listingsForClient) < 1 || isset($no_listing_found) && $no_listing_found == true) {
                            ?>
                            <div class="Fnt16 OrgangeFont" align="center">You need to buy our listing product to receive responses.</div>
                            <?php
                            }
                            else if(count($listings) < 1) {
                            ?>
                            <div class="Fnt16 OrgangeFont" align="center">There is no response generated for your listing.</div>
                            <?php
                            }
                            ?>
							
                            <div class="row normaltxt_11p_blk bld" <?php if(count($listings) < 1) echo 'style="display:none"'; ?>>
                                <!-- <input type="hidden" id="methodName" value="searchCoursesCMS"/>-->
                                <input type="hidden" id="methodName" value="getPopularCourColCMSajax"/>
                                <div id="paginataionPlace1" style="display:none;"></div>
                                <div class="boxcontent_lgraynoBG">
                                    <div style="background-color:#EFEFEF; padding-left:2px;width:955px">
                                        <div class="float_L" style="background-color:#EFEFEF; <?php if($tabStatus == 'live') echo 'width:250px;'; else echo 'width:300px;'; ?> padding-top:5px; padding-bottom:5px; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Institute Name</div>
										<div class="float_L" style="background-color:#EFEFEF; <?php if($tabStatus == 'live') echo 'width:150px;'; else echo 'width:200px;'; ?> padding-top:5px; padding-bottom:5px; border-right:1px solid #B0AFB4">&nbsp; &nbsp;City</div>
										<div class="float_L" style="background-color:#EFEFEF; <?php if($tabStatus == 'live') echo 'width:150px;'; else echo 'width:200px;'; ?> padding-top:5px; padding-bottom:5px; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Locality</div>
                                        <div class="float_L" style="background-color:#EFEFEF; <?php if($tabStatus == 'live') echo 'width:200px;'; else echo 'width:250px;'; ?> padding-top:5px; padding-bottom:5px; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Responses</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:200px; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; <?php if($tabStatus == 'deleted') echo 'display:none;'; ?>">&nbsp; &nbsp;Daily Email</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="cmsCourColTable" name="cmsCourColTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:310px; overflow:auto">
                                 <?php if(!empty($listings)){ ?>
                                    <input type="checkbox" id="selectall" onclick="selectAll();" /> Select All
                                <?php } ?>
                                            
                                <?php
                                     //_P($listings);
                                    foreach($listings as $listing) {
									   //$responseKey = $listing['listing_type_id'].'_'.$listing['institute_location_id'];
                                       //$totalResponses = isset($listingsCount[$responseKey]['totalResponses']) && !empty($listingsCount[$responseKey]['totalResponses']) ? $listingsCount[$responseKey]['totalResponses'] : 0 ;
                                       $listingType       = isset($listing['institute_id']) ? 'institute' : 'university';
                                       $listingId         = $listingType == 'institute' ? $listing['institute_id'] : $listing['university_id'];
                                       $listingLocationId = $listing['locationId'];
                                ?>
                                <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999; width:940px;">
                                    <div class="float_L" style="<?php if($tabStatus == 'live') echo 'width:250px;'; else echo 'width:300px;'; ?>">
                                        <div class="mar_full_10p">
                                           <div class="lineSpace_10">&nbsp;</div>
                                            <div><input class="checkbox_res" type="checkbox" onclick="checkUncheckBox();" name="listing" value="<?php echo $listingId.'|'.$listingLocationId.'|'.$listingType; ?>"><?php echo $listing['listing_title']; ?></div>
                                            <div class="clear_L"></div>
                                        </div>
                                    </div>
									<div class="float_L" style="<?php if($tabStatus == 'live') echo 'width:150px;'; else echo 'width:200px;'; ?>">
                                        <div class="mar_full_10p">
                                            <div class="lineSpace_10">&nbsp;</div>
                                            <div><?php echo $listingLocationId == 1 ? "" : $listing['city_name']; ?></div>
                                            <div class="clear_L"></div>
                                        </div>
                                    </div>
									<div class="float_L" style="<?php if($tabStatus == 'live') echo 'width:150px;'; else echo 'width:200px;'; ?>">
                                        <div class="mar_full_10p">
                                            <div class="lineSpace_10">&nbsp;</div>
											<div><?php echo $listingLocationId == 1 ? "" : $listing['localityName']; ?></div>
                                            <div class="clear_L"></div>
                                        </div>
                                    </div>
                                    <div class="float_L" style="width:45px;">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div class="mar_full_10p"><?php echo $listing['totalResponses'];?></div> <br />
                                    </div>
                                    <?php 
                                    if($listing['totalResponses'] > 0 && $tabStatus == 'live') {
                                    ?>
                                    <div class="float_L">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div class="mar_full_10p">
                                            <div class="buttr3">
                                                <button class="btn-submit7 w9" type="button" uniqueattr="Enterprise/ListingsResponses/View/<?php echo $listingId; ?>" onClick="location.replace('/enterprise/Enterprise/getResponsesForListing/<?php echo $listingId; ?>/<?php echo $listingType; ?>/both/<?php echo $listingLocationId; ?>')">
                                                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">View Responses</p></div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                    ?>
                                    <?php 
                                        if($tabStatus == 'live') {
                                    ?>
                                    <div class="float_L">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <?php
                                        
                                        $listing_type_email = $listingType;
										foreach($listingsForClient as $value) {
											if($value['listing_type_id'] == $listingId && $value['listing_type'] == 'university_national') {
													$listing_type_email = $value['listing_type'];
													break;
											}	
										}
										
                                        if(empty($listing['emailExport'])) {																					
                                        ?>
                                        <div id="<?php echo $listingId.'_'.$listingLocationId; ?>"><input type="text" style="width:130px;" name="<?php echo "listingEmail_".$listingId.'_'.$listingLocationId; ?>" listingLocationId="<?php echo $listingLocationId; ?>" listingId="<?php echo $listingId; ?>" listingType="<?php echo $listing_type_email; ?>" value="Enter Email" onfocus="focusin('<?php echo $listingId.'_'.$listingLocationId; ?>');" onblur="focusout('<?php echo $listingId.'_'.$listingLocationId; ?>');" />
                                        <span class="mar_full_10p"><a onClick="saveEmail('<?php echo $listingId.'_'.$listingLocationId; ?>');">Save</a></span></div>
                                        <div id="<?php echo "editEmail_".$listingId.'_'.$listingLocationId; ?>" style="display:none;"></div>
                                        <?php
                                        }
                                        else {
											//_P($listingsForClient);
                                        ?>
                                        <div id="<?php echo $listingId.'_'.$listingLocationId; ?>" style="display:none;"><input type="text" style="width:130px;" name="<?php echo "listingEmail_".$listingId.'_'.$listingLocationId; ?>" listingLocationId="<?php echo $listingLocationId; ?>" listingId="<?php echo $listingId; ?>" listingType="<?php echo $listing_type_email; ?>" value="<?php echo $listing['emailExport']; ?>" onfocus="focusin('<?php echo $listingId.'_'.$listingLocationId; ?>');" onblur="focusout('<?php echo $listingId.'_'.$listingLocationId; ?>');" />
                                        <span class="mar_full_10p"><a onClick="saveEmail('<?php echo $listingId.'_'.$listingLocationId; ?>');">Save</a></span></div>
                                        <div id="<?php echo "editEmail_".$listingId.'_'.$listingLocationId; ?>"><div style="width: 140px; overflow: hidden; float:left;"><?php echo $listing['emailExport']; ?></div><span class="mar_full_10p"><a onClick="editEmail('<?php echo $listingId.'_'.$listingLocationId; ?>');">Edit</a></span></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php 
                                        }
                                    ?>
                                    <div class="clear_L"></div>
                                    <div class="lineSpace_5">&nbsp;</div>
                                </div>
                                <?php
                                    }
                                    ?>
                            </div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <div class="mar_full_10p" <?php if(count($listings) < 1) echo 'style="display:none"'; ?>>
                                From <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="" readonly="true" name="bottom_fromdate" id="bottom_fromdate" />
								<img src="/public/images/cal-icn.gif" id="bottom_fromdate_img" onclick="daterangeFrom('bottom',1);" style="vertical-align: middle; cursor:pointer;" />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								
								To <input style="width: 82px; vertical-align: middle; color: #7c7c7c" type="text" value="" readonly="true" name="bottom_todate" id="bottom_todate" />
								<img src="/public/images/cal-icn.gif" id="bottom_todate_img" onclick="daterangeTo('bottom',1);" style="vertical-align: middle; cursor:pointer;" />
								&nbsp;&nbsp;
								
								<button style="vertical-align: middle;" class="btn-submit7" type="button" uniqueattr="Enterprise/ListingsResponses/Download/bottom" onClick="downloadResponses('bottom','<?php echo $tabStatus; ?>');">
									<div class="btn-submit7"><p style="line-height: 30px;" class="btn-submit8 btnTxtBlog">Download Responses</p></div>
								</button>
                            </div>
                            <div class="clear_L"></div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <form id="dummyFormForDownload" action="/enterprise/Enterprise/getDownloadResponses" method="post" style="display:none">
                                <input type="hidden" name="values">
                                <input type="hidden" name="fromdate">
                                <input type="hidden" name="todate">
                                <input type="hidden" name="status">
                            </form>


						<!-- code for pagination ends -->
<script>
//doPagination();

    function selectAll(){
        var flag = true;
        $j('.checkbox_res').each(function(){
            if(!$j(this).is(":checked")){
                flag = false;
            }
        });

        if(!flag){
            $j('.checkbox_res').prop('checked', true);    
        }else{
             $j('.checkbox_res').prop('checked', false);    
             $j('#selectall').prop('checked', false);    
        } 
    }

    function checkUncheckBox(){
         var flag = true;
        $j('.checkbox_res').each(function(){
            if(!$j(this).is(":checked")){
                flag = false;
            }
        });
        if(!flag){
            $j('#selectall').prop('checked', false);    
        }else{
             $j('#selectall').prop('checked', true);    
        } 
    }

function focusin(id){
    var name = 'input[name="listingEmail_' + id + '"]';
	if($j(name).val() == 'Enter Email') {
		$j(name).val('');
	}
}

function focusout(id){
    var name = 'input[name="listingEmail_' + id + '"]';
	if($j(name).val() == '') {
		$j(name).val('Enter Email');
	}
}

function editEmail(id) {
    var name = '#editEmail_' + id;
    $j(name).css('display', 'none');
    $j('#'+id).css('display', 'block');
}

function saveEmail(id) {
    var name = 'input[name="listingEmail_' + id + '"]';
    var listingLocationId = $j(name).attr("listingLocationId");
    var listingId = $j(name).attr("listingId");
    var listingType = $j(name).attr("listingType");
    var email = $j(name).val();
    
    if(email == 'Enter Email' || email == '') {
        email = '';
	}
    else {
        email = email.replace(/\s/gi,'').replace(/,/gi,'|').replace(/\;/gi,'|').split('|');
    	for (var i = 0; i < email.length; i++) {
    		if (validateEmail(email[i].toLowerCase(),'email address',125,10) != true) {
    		    alert("one or more email addresses entered is invalid "+email[i]);
    		    return false;
    		}
    	}
    	email = email.join(", ");
    }
    
	$j.ajax({
		type: "post",
		url: '/enterprise/Enterprise/saveEmailsForListing/',
		cache: false,
		data: "email=" + email + "&listingId=" + listingId + "&listingLocationId=" + listingLocationId + "&listingType=" +listingType,
		success: function( result ){
			if( result ) {
				if(email == '') {
				    return false;
				}
				else {
                    alert('Successfully saved');
    			    var name = '#editEmail_' + id;
    			    $j(name).empty();
    			    $j(name).prepend('<div style="width: 140px; overflow: hidden; float:left;">' + email + '</div><span class="mar_full_10p"><a onClick="editEmail(\'' + id + '\');">Edit</a></span>');
    			    $j('#'+id).css('display', 'none');
    			    $j(name).css('display', 'block');
				}
			}
			else {
			    alert('Error occured');
			    return false;
			}
		}
	});
}

function downloadResponses(postion,status) {
	var elems = $j('input[name="listing"]:checked');
	var values = $j.map(elems, function (elem) {
		return $j(elem).val();
	});
	if(values.length) {
		if(validateRange(postion)) {
			values = values.join();
			var fromdateobj = $(postion+'_fromdate');
			if (fromdateobj.value == "") {
				var fromdate = 'none';
			}
			else {
				var fromdate = fromdateobj.value;
			}
			var todateobj = $(postion+'_todate');
			if (todateobj.value == "") {
				var todate = 'none';
			}
			else {
				var todate = todateobj.value;
			}
            // window.open('/enterprise/Enterprise/getDownloadResponses/'+values+'/'+fromdate+'/'+todate+'/'+status+'/');

            $j("#dummyFormForDownload input[name='values']").val(values);
            $j("#dummyFormForDownload input[name='fromdate']").val(fromdate);
            $j("#dummyFormForDownload input[name='todate']").val(todate);
            $j("#dummyFormForDownload input[name='status']").val(status);

            $j('#dummyFormForDownload').submit();
		}
	}
	else {
		alert('Select atleast one Institute');
		return false;
	}
}

var isresponseViewer = 0;
function daterangeFrom(postion, responseViewer)
{
    var fromdateobj = $(postion+'_fromdate');
    var calMain = new CalendarPopup('calendardiv');

    isresponseViewer = responseViewer == 1? 1:0;
    disDate = null;
    frmdisDate = new Date();
    calMain.select(fromdateobj,postion+'_fromdate_img','yyyy-mm-dd');
    return false;
}

function daterangeTo(postion, responseViewer)
{
    var todateobj = $(postion+'_todate');
    var fromdateobj = $(postion+'_fromdate');
    var calMain = new CalendarPopup('calendardiv');
    var mindate = fromdateobj.value; //yyyy-mm-dd
    var dateStr = mindate.split("-");
    var passedDate = dateStr[2]%32;
    var passedMonth = dateStr[1]%13;
    var passedYear = dateStr[0];
    isresponseViewer = responseViewer == 1? 1:0;
    disDate = new Date(passedYear,passedMonth-1,passedDate);
    frmdisDate = new Date();
    calMain.select(todateobj,postion+'_todate_img','yyyy-mm-dd',fromdateobj.value);
    return false;
}

function validateRange(postion)
{
        var fromdateobj = $(postion+'_fromdate');
        var todateobj = $(postion+'_todate');
        if (fromdateobj.value == "" || todateobj.value == "") {
            return true;
        }
        else {
           //convert yyyy-mm-dd to mm/dd/yyyy
           var fromdate = fromdateobj.value;
           fromdate = fromdate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
           var todate = todateobj.value;
           todate = todate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
           if (Date.parse(todate) >= Date.parse(fromdate)) {
               return true;
           }
           else {
               alert("Please select a date greater than from date");
               fromdateobj.value = todateobj.value;
               return false;
           }
        }
}
</script>
