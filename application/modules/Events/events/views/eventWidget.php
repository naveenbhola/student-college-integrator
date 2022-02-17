	<?php if(!empty($eventList)){ ?>
	<div class="raised_greenGradient_ww">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_greenGradient_ww">
			<div class="clear_B lineSpace_5">&nbsp;</div>
			<?php if($listing_type=='institute'){
				$widgetTitleFull="Important dates of ".$details['title'];
				 }else if($listing_type=='course'){
				$widgetTitleFull="Important dates of ".$details['title'];
				 }
				if(strlen($widgetTitleFull)>50){
				$widgetTitle=substr($widgetTitleFull,0,50)."....";
				}else{
				$widgetTitle=$widgetTitleFull;
				}
			?>
			<div class="mar_full_10p">
				<div class="Fnt16 orangeColor wdh100" title="<?php echo $widgetTitleFull; ?>" ><img src="/public/images/date_icon.gif"/>&nbsp; <?php echo $widgetTitle; ?></b></div>
					<div class="float_R" style="width:70px;height:16px;overflow:hidden">
					 <div id="bothSliders" style="display:none" >
                                                            <div class="arrowMove">
                                                                <a href="javascript:backSlideOnClick();" class="spirit_middle shik_leftMoveTxtArrow" id="leftSlider">&nbsp;</a><a href="javascript:slideOnClick()" style="margin-left: 3px;" class="spirit_middle shik_rightMoveTxtArrow" id="rightSlider">&nbsp;</a>
                                                            </div>
                                          </div>
                                          <div id="leftSliders" style="display:none">
                                                            <div class="arrowMove">
                                                                <a href="javascript:backSlideOnClick();" class="spirit_middle shik_leftMoveTxtArrow" id="leftSlider">&nbsp;</a><a href="javascript:void(0);" style="margin-left: 3px;" class="spirit_middle shik_rightMoveTxtArrow" id="rightSlider">&nbsp;</a>
                                          </div>
                                                        </div>
                                           <div id="rightSliders" style="display:none">
                                                            <div class="arrowMove">
                                                                <a href="javascript:void(0);" class="spirit_middle shik_leftMoveTxtArrow" id="leftSlider">&nbsp;</a><a href="javascript:slideOnClick();" style="margin-left: 3px;" class="spirit_middle shik_rightMoveTxtArrow" id="rightSlider">&nbsp;</a>
                                                            </div>
                                           </div>
				</div>				
				<div class="clear_B lineSpace_15">&nbsp;</div>
				<div>
					<ul class="entList" id="eventList">
						<?php
							foreach($eventList as $temp){               
							$count=$temp['count'];
							$start=$temp['start'];
							$event_id=$temp['id'];
							$event_title=$temp['title'];
						?>
						<li style="width:245px">
							<div class="rw1">
								<input type="hidden" id="startFrom" autocomplete="off" value="0" />
								<div class="sdtBg">
									<div class="whiteColor"><?php echo date("M",strtotime($temp['end_date']));?></div>
									<div><?php echo date("j",strtotime($temp['end_date']));?></div>
								</div>
                            </div>
							<?php 
								if(strlen($temp['title'])>40){
								$titleOfEvent=substr($temp['title'],0,40)."....";					
								}else{
								$titleOfEvent=$temp['title'];
								}
							?>
							<div class="rw2" style="width:185px">
								<div><a href="<?php echo getSeoUrl($temp['id'],'event',$temp['title']); ?>" title="<?php echo $temp['title'] ?>"><?php echo $titleOfEvent; ?></a></div>
							<?php
							$Title=$details['title'];
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {
                                                        $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $event_id; ?>/'<?php $listing_type; ?>'/<?php echo $type_id; ?>/'<?php echo $event_title; ?>'");
                                                        $onClick = "calloverlayEvents('$event_id','".addslashes($event_title)."','EVENTS');return false;";
                                                        $onClickAll = "calloverlayEvents('$type_id','Listing detail page','$listing_type',2,0,2,'$Title',1,'$Title');return false;";
                                                        }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php echo $base64url?>/1\');return false;';
                                                        } else {
							$onClick = "calloverlayEvents('$event_id','".addslashes($event_title)."','EVENTS');return false;";
                                                        $onClickAll = "calloverlayEvents('$type_id','Listing detail page','$listing_type',2,0,2,'$Title',1,'$Title');return false;";
                                                        }
                                                        }
                                                        ?>
								<a href="javascript:void(0);" onClick="<?php echo $onClick; ?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($event_title); ?>');return false;" class="Fnt11"><b>Subscribe to this event</b></a>
							</div>
							<div class="clear_B">&nbsp;</div>
						</li>
						<?php
							}
						?>
							<li style="width:245px" class="txt_align_r"><input type="button" value="Subscribe" onClick="<?php echo $onClickAll?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo $event_title; ?>');return false;" class="btn_scrib" /></li>
					</ul>
                </div>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
<?php 
	}
?>	
<input type="hidden" id="listingTypeId" autocomplete="off" value="<?php echo $type_id; ?>" />
<input type="hidden" id="listing_type" autocomplete="off" value="<?php echo $listing_type; ?>" />
<input type="hidden" id="start" autocomplete="off" value="<?php echo $start; ?>" />
<input type="hidden" id="count" autocomplete="off" value="<?php echo $count; ?>" />
<script>
function slideOnClick(){
	document.getElementById('start').value=document.getElementById('startFrom').value;
	var type= document.getElementById('listing_type').value;
	var data = document.getElementById('listingTypeId').value;
	var start=parseInt(document.getElementById('start').value)+3;
	var count=3;
	var total=parseInt(document.getElementById('count').value);
	var url = '/events/Events/getEventsForListings/1/'+ type + '/' + data+'/'+start+'/'+count;
	var a  = new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess: function (xmlHttp) {
			var ajaxResp = eval(xmlHttp.responseText);
			var eventsHTML = '';
			for(var eventCount =0;eventCount<ajaxResp.length;eventCount++ ) {
			eventsHTML +='<li style="width:245px"> <div class="rw1"><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCount].month +'</div><div>'+ ajaxResp[eventCount].date +'</div></div></div><div class="rw2" style="width:185px"><div>';
			eventsHTML+='<a href="/events/Events/eventDetail/1/'+ ajaxResp[eventCount].id +'" title="'+ ajaxResp[eventCount].title +'">'+ ajaxResp[eventCount].eventTitle +'</a>';
			eventsHTML+='</div><a href="#" class="Fnt11"><b>Subscribe to this event</b></a></div><input type="hidden" id="startFrom" autocomplete="off" value="'+ ajaxResp[eventCount].start +'" /><input type="hidden" id="count" autocomplete="off" value="'+ ajaxResp[eventCount].count +'" /><div class="clear_B">&nbsp;</div></li>';
			}
			eventsHTML+='<li style="width:245px" class="txt_align_r"><input type="button" value="Subscribe" class="btn_scrib" /></li>';
			document.getElementById('eventList').innerHTML = eventsHTML;
			}});
			if(start<=0){
                        if(total<=start+3){
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'block';
                        }
                        document.getElementById('leftSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'none';
                        document.getElementById('leftSliders').style.display = 'block';  
                        }
                 if(total<=start+3){
                        if(start>0){
                        document.getElementById('leftSliders').style.display = 'block';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        }
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'block';  
                        }
                if(start <= 0 || total<=start+3){
                        document.getElementById('bothSliders').style.display = 'none';  
                }else{
                        document.getElementById('bothSliders').style.display = 'block';
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'none';
                }
	return;
}
function backSlideOnClick(){
	document.getElementById('start').value=document.getElementById('startFrom').value;
	var type=document.getElementById('listing_type').value;
        var data=document.getElementById('listingTypeId').value;
        var start=parseInt(document.getElementById('start').value)-3;
        var count=3;
	var total=parseInt(document.getElementById('count').value);
        var url = '/events/Events/getEventsForListings/1/'+ type +'/'+ data+'/'+start+'/'+count;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess: function (xmlHttp) {
                        var ajaxResp = eval(xmlHttp.responseText);
                        var eventsHTML = '';
                        for(var eventCount =0;eventCount<ajaxResp.length;eventCount++ ) {
                        eventsHTML +='<li style="width:245px"> <div class="rw1"><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCount].month +'</div><div>'+ ajaxResp[eventCount].date +'</div></div></div><div class="rw2" style="width:185px"><div>';
			eventsHTML+='<a href="/events/Events/eventDetail/1/'+ ajaxResp[eventCount].id +'" title="'+ ajaxResp[eventCount].title +'">'+ ajaxResp[eventCount].eventTitle +'</a>';
			eventsHTML+='</div><a href="#" class="Fnt11"><b>Subscribe to this event</b></a></div><input type="hidden" id="startFrom" autocomplete="off" value="'+ ajaxResp[eventCount].start +'" /><input type="hidden" id="count" autocomplete="off" value="'+ ajaxResp[eventCount].count +'" /><div class="clear_B">&nbsp;</div></li>';
                        }
			eventsHTML+='<li style="width:245px" class="txt_align_r"><input type="button" value="Subscribe" class="btn_scrib" /></li>';
                        document.getElementById('eventList').innerHTML = eventsHTML;
                        }});
			if(start<=0){
                        if(total<=start+3){
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'block';
                        }
                        document.getElementById('leftSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'none';
                        document.getElementById('leftSliders').style.display = 'block';  
                        }
                 if(total<=start+3){
                        if(start>0){
                        document.getElementById('leftSliders').style.display = 'block';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        }
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'block';  
                        }
                if(start <= 0 || total<=start+3){
                        document.getElementById('bothSliders').style.display = 'none';  
                }else{
                        document.getElementById('bothSliders').style.display = 'block';
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'none';
                }
        return;
}

function subscribeEvents(eventId,eventTitle){
        <!--
	var url = '/events/Events/subscribeEvents/1/'+ eventId +'/event/'+ eventId +'/'+ eventTitle;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (eventId), onSuccess: function (xmlHttp) {
        }});
   -->
        }

function onLoad(){
		var start=parseInt(document.getElementById('start').value);
		 var total=parseInt(document.getElementById('count').value);
		if(start<=0){
                        if(total<=start+3){
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'block';
                        }
                        document.getElementById('leftSliders').style.display = 'none';
                        }else{
                        document.getElementById('rightSliders').style.display = 'none';
                        document.getElementById('leftSliders').style.display = 'block';  
                        }
                 if(total<=start+3){
                        if(start>0){
                        document.getElementById('leftSliders').style.display = 'block';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        }
                        document.getElementById('rightSliders').style.display = 'none';
                        }else{
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'block';  
                        }
                if(start <= 0 || total<=start+3){
                        document.getElementById('bothSliders').style.display = 'none';  
                }else{
                        document.getElementById('bothSliders').style.display = 'block';
                        document.getElementById('leftSliders').style.display = 'none';
                        document.getElementById('rightSliders').style.display = 'none';
                }
}
if(document.getElementById('leftSlider')){
onLoad();
}
</script>
