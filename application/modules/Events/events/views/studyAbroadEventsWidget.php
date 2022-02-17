<!--Start_Impotent_Dates-->
<?php if(!empty($notifications)){?>
<input type="hidden" id="categoryId" autocomplete="off" value="<?php echo $categoryId; ?>" />
<input type="hidden" id="countryId" autocomplete="off" value="<?php echo $countryId; ?>" />
<input type="hidden" id="country_name" autocomplete="off" value="<?php echo $countryNameSelected; ?>" />
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                	
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt14" style="padding-left:10px"><b>Important Dates</b></span></div>                                        
			<div id="activeUnactive">      
				  <div class="wdh100">
                                            <div class="bgTab">
                                                <div class="lh10"></div>
                                                <div class="impDt" style="margin-left:5px">
                                                    <a href="javascript:viewEventsForStudyAbroad(0);" class="active">App Deadline</a>
                                                    <a href="javascript:viewEventsForStudyAbroad(1);" class="unactive">Course Commencement</a>
                                                    <a href="javascript:viewEventsForStudyAbroad(5);" class="unactive">General</a>
                                                </div>
                                            </div>
                                        </div>
			</div>
                                        <div class="mlr10">
                                            <div class="wdh100">
                                                <div>
                                                    <div class="float_R w43">
                                                        <div class="wdh100">
                                                            <div class="lineSpace_8">&nbsp;</div>
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
                                                    </div>
                                                    <div style="margin-right:60px">
                                                        <div class="float_L wdh100">
                                                            <div class="lineSpace_5">&nbsp;</div>
							<?php
                                                        foreach($notifications as $temp){
								$total_events=$temp['total_events'];
								$start=$temp['start'];
							}
							?>
							<div id="showCount">
                                                            <div>Showing 1 - <?php $temp = (($start+3) > $total_events)?$total_events:($start+3); echo $temp; ?> out of <?php echo $total_events; ?></div>
							</div>
                                                        </div>
                                                    </div>
                                                    <div class="clear_B"></div>
                                                    <div class="lh10"></div>
                                                </div>                                                
                                                <div>
                                                    <ul class="impDts">
							<div id="eventListByType">
                                                        <!--Start_Repeating_Row-->
							<?php
							foreach($notifications as $temp){
		                                        $event_id=$temp['event_id'];
		                                        $titleEvent=$temp['event_title'];
		                                        $startEvent=$temp['start_date'];
		                                        $endEvent=$temp['end_date'];
		                                        $fromOthers=$temp['fromOthers'];
                                		        $country_name=$temp['country_name'];
                		                        $city_name=$temp['city_name'];
							$total_events=$temp['total_events'];
		                                        ?>
                                                        <li>
                                                            <div class="rw1">
                                                 <?php			if(date("jS M,y",strtotime($startEvent))!=date("jS M,y",strtotime($endEvent))){
                                                                                $currentDate=date("Y-m-d");
                                                                                if($startEvent>=$currentDate){ ?>
                                                                        <div class="Fnt10">starts on</div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                                <?php }else{  ?>
                                                                        <div class="Fnt10">upto</div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($endEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($endEvent));?></div>
                                                                        </div>
                                                                        <?php }
                                                                                }else{
                                                                                ?>
                                                                        <div class="Fnt10"></div>
                                                                        <div class="sdtBg">
                                                                                <div class="whiteColor"><?php echo date("M",strtotime($startEvent));?></div>
                                                                                <div><?php echo date("j",strtotime($startEvent));?></div>
                                                                        </div>
                                                                        <?php } ?>
		                                            </div>
								<input type="hidden" id="start" autocomplete="off" value="0" />
								<input type="hidden" id="from_others" autocomplete="off" value="<?php echo $fromOthers; ?>" />
								<input type="hidden" id="total_events" autocomplete="off" value="<?php echo $total_events; ?>" />
                                                            <div class="rw2">
                                                             <div><a href="<?php echo getSeoUrl($event_id,'event',$titleEvent); ?>"class=""><?php echo $titleEvent; ?></a></div>
							<?php
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {
                                                        $onRedirect = base64_encode("/events/Events/subscribeEvents/1/<?php echo $event_id; ?>/event/<?php echo $event_id; ?>/<?php echo $titleEvent; ?>");
                                                        $onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
							$onClickAll = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','ALLSAEVENTS','$countryNameSelected',$fromOthers,'$countryId','$countryNameSelected');return false;";
                                                        }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                        echo $base64url?>/1\');return false;';
                                                        } else {
							$onClick = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','EVENTS');return false;";
                                                        $onClickAll = "calloverlayEvents('$event_id','".addslashes($titleEvent)."','ALLSAEVENTS','$countryNameSelected',$fromOthers,'$countryId','$countryNameSelected');return false;";
                                                        }
                                                        }
                                                        ?>
								<div class="drkGry"><?php echo $city_name?>, <?php echo $country_name?><?php if($fromOthers>3){?>, <?php echo date("jS M,y",strtotime($startEvent)) ?> - <?php echo date("jS M,y",strtotime($endEvent)) ?><?php } ?></div>
								<a href="javascript:void(0);" onClick="<?php echo $onClick?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($titleEvent); ?>');return false;"><b>Subscribe</b></a></div>
                                                            <div class="clear_B"></div>
                                                        </li>
							<?php }?>
							</div>
                                                        <!--End_Repeating_Row-->
                                                    </ul>
                                                </div>
						<div class="lineSpace_15">&nbsp;</div>
						<div><span id="subsAllEventsId"><input type="button" onClick="<?php echo $onClickAll; ?>;subscribeEvents('<?php echo $event_id; ?>','<?php echo addslashes($titleEvent); ?>');return false;" class="btn_scrib" value="Subscribe" /></span>
						<?php if(!empty($total_events) && $total_events>3){?>
						<span class="float_R" id="viewAllLink">
                	                                <a href="<?php echo constant('SHIKSHA_EVENTS_HOME_URL') ?>/events/Events/viewAllEvents/<?php echo $countryNameSelected; ?>/<?php echo $fromOthers; ?>/1/All/<?php echo $countryId?>/<?php echo $countryNameSelected; ?>/0/10/All" class="">View All</a>
                                                </span>	
						<?php } ?></div>
                                            </div>                                            
                                        </div>         
                                        <div class="lh10">&nbsp;</div>
                                    </div>
                                </div>
<input type="hidden" id="onClick" value="<?php echo $onClick ?>"/>
<input type="hidden" id="onClickAll" value="<?php echo $onClickAll ?>"/>
				 <div class="lh10">&nbsp;</div>
<?php } ?>
                                <!--End_Start_Impotent_Dates-->
<script>
function addslashes (str) {
    return (str+'').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}
function subscribeEvents(eventId,eventTitle){
        <!--
	var url = '/events/Events/subscribeEvents/1/'+ eventId +'/event/'+ eventId +'/'+ eventTitle;
       var a  = new Ajax.Request (url,{ method:'post', parameters: (eventId), onSuccess: function (xmlHttp) {
        }});
        -->
        }
function checkClass(from_others,total_events){
	var activeHTML='<div class="wdh100"><div class="bgTab"><div class="lh10"></div><div class="impDt" style="margin-left:5px">';
	var countryId=document.getElementById('countryId').value;
	var country_name=document.getElementById('country_name').value;
	if(from_others==0){
		activeHTML+='<a href="javascript:viewEventsForStudyAbroad(0);" class="active">App Deadline</a><a href="javascript:viewEventsForStudyAbroad(1);" class="unactive">Course Commencement</a><a href="javascript:viewEventsForStudyAbroad(5);" class="unactive">General</a>';
	}else if(from_others==1){
		activeHTML+='<a href="javascript:viewEventsForStudyAbroad(0);" class="unactive">App Deadline</a><a href="javascript:viewEventsForStudyAbroad(1);" class="active">Course Commencement</a><a href="javascript:viewEventsForStudyAbroad(5);" class="unactive">General</a>';
	}else if(from_others==5){
		activeHTML+='<a href="javascript:viewEventsForStudyAbroad(0);" class="unactive">App Deadline</a><a href="javascript:viewEventsForStudyAbroad(1);" class="unactive">Course Commencement</a><a href="javascript:viewEventsForStudyAbroad(5);" class="active">General</a>';
	}
		activeHTML+='</div></div></div>';
		document.getElementById('activeUnactive').innerHTML = activeHTML;
		if(total_events>3){
		var viewAllHTML='<a href="/events/Events/viewAllEvents/'+ country_name +'/'+ from_others +'/1/All/'+ countryId +'/'+ country_name +'/0/10/All" class="bld">View All</a>';
		}else{
		var viewAllHTML='';
		}
		document.getElementById('viewAllLink').innerHTML = viewAllHTML;
		}
function viewEventsForStudyAbroad(from_others){
        var today = new Date();
        var enteredDate = new Date();
        var dateArray = '';
        var eneterdYear = '';
        var eneterdMonth = '';
	var titleOfTheEvent = '';
        var eneterdDay = '';
	var days='';
	var start=0;
        var countryId= document.getElementById('countryId').value;
	var country_name= document.getElementById('country_name').value;
        var category_id= document.getElementById('categoryId').value;
        var total_events=0;
	var count=3;
	var onClick = document.getElementById('onClick').value;
	var onClickAll = document.getElementById('onClickAll').value;
        var url = '/events/Events/studyAbroadEvents/1/'+ from_others +'/'+ countryId + '/' + category_id +'/'+ start +'/'+ count;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (category_id), onSuccess: function (xmlHttp) {
                        var ajaxResp = eval(xmlHttp.responseText);
                        var eventsHTML='';
			var subsAllEventsHTML='';

				if(ajaxResp.length!=0){
				for(var eventCounts =0;eventCounts<ajaxResp.length;eventCounts++ ){
					subsAllEventsHTML='';
					total_events=ajaxResp[eventCounts].total_events;
					eventsHTML+='<li><div class="rw1">';
					dateArray = ajaxResp[eventCounts].start_date.split("-");
                                        eneterdYear = dateArray[0];
                                        eneterdMonth = dateArray[1];
                                        eneterdDay = dateArray[2];
                                        enteredDate.setDate(eneterdDay.substr(0,2));
                                        enteredDate.setMonth(eneterdMonth-1);
                                        enteredDate.setYear(eneterdYear);
					if(ajaxResp[eventCounts].start_date_format!=ajaxResp[eventCounts].end_date_format){
                                        if(enteredDate>=today){
                                        eventsHTML+='<div class="Fnt10">starts on</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].start_month +'</div><div>'+ ajaxResp[eventCounts].start_day +'</div></div>';
                                        }else{
                                        eventsHTML+='<div class="Fnt10">upto</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].end_month +'</div><div>'+ ajaxResp[eventCounts].end_day +'</div></div>';
                                        }}else{
                                        eventsHTML+='<div class="Fnt10"></div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].start_month +'</div><div>'+ ajaxResp[eventCounts].start_day +'</div></div>';
                                        }
                                        eventsHTML+='</div><input type="hidden" id="start" autocomplete="off" value="'+ ajaxResp[eventCounts].start +'" /><input type="hidden" id="from_others" autocomplete="off" value="'+ ajaxResp[eventCounts].fromOthers +'" /><input type="hidden" id="total_events" autocomplete="off" value="'+ ajaxResp[eventCounts].total_events +'" /><div class="rw2"><div>';
					titleOfTheEvent=(ajaxResp[eventCounts].event_title).replace(new RegExp(' ','g'),'-');
                                        eventsHTML+='<a href="/events/Events/eventDetail/1/'+ ajaxResp[eventCounts].event_id +'/'+ titleOfTheEvent +'" class="">'+ ajaxResp[eventCounts].event_title +'</a>';
					eventsHTML+='</div><div class="drkGry">'+ ajaxResp[eventCounts].city_name +', '+ ajaxResp[eventCounts].country_name;
					if(ajaxResp[eventCounts].fromOthers>3){
					eventsHTML+=', '+ ajaxResp[eventCounts].start_date_format +' - '+ ajaxResp[eventCounts].end_date_format;
					}
					if(onClick!=''){
                                        onClick='calloverlayEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\',\'EVENTS\');return false;';
                                        }
					if(onClickAll!=''){
                                        onClickAll='calloverlayEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\',\'ALLSAEVENTS\',\''+ country_name +'\','+ from_others +',\''+ countryId +'\',\''+ country_name +'\');return false;';
                                        }
					eventsHTML+='</div><a href="javascript:void(0);" onClick="'+ onClick +' subscribeEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\');return false;" class="vDLink_2">Subscribe</a></div><div class="clear_B"></div></li>';
					subsAllEventsHTML+='<span><input type="button" onClick="'+ onClickAll +'subscribeEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\');return false;" class="btn_scrib" value="Subscribe" /></span';
                                       }
			}else{
			eventsHTML+='No events to display under this selection.';
			}
			document.getElementById('eventListByType').innerHTML = eventsHTML;
			document.getElementById('subsAllEventsId').innerHTML = subsAllEventsHTML;
			var showingEventsHTML='';
			if(total_events!=0){
			if(total_events>3){
                        showingEventsHTML+='<div class="float_L">Showing 1 - 3 out of '+ total_events +'</div>';
			}else{
			showingEventsHTML+='<div class="float_L">Showing 1 - '+ total_events +' out of '+ total_events +'</div>';
			}}else{
			showingEventsHTML+='';
			}
                        document.getElementById('showCount').innerHTML = showingEventsHTML;
			onSALoad(total_events);		
			checkClass(from_others,total_events);	
                        }});
        }
function slideOnClick(){
	var today = new Date();
        var enteredDate = new Date();
        var dateArray = '';
        var eneterdYear = '';
        var eneterdMonth = '';
	var titleOfTheEvent = '';
        var eneterdDay = '';
        var start=parseInt(document.getElementById('start').value)+3;
	var from_others=document.getElementById('from_others').value;
	var countryId= document.getElementById('countryId').value;
        var category_id= document.getElementById('categoryId').value;
        var count=3;
	var onClick = document.getElementById('onClick').value;
        var total_events=parseInt(document.getElementById('total_events').value);
	var days='';
        var url = '/events/Events/studyAbroadEvents/1/'+ from_others +'/'+ countryId + '/' + category_id +'/'+ start +'/'+ count;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (from_others), onSuccess: function (xmlHttp) {
                        var ajaxResp = eval(xmlHttp.responseText);
                        var eventsHTML = '';
                        for(var eventCounts =0;eventCounts<ajaxResp.length;eventCounts++ ){
					total_events=ajaxResp[eventCounts].total_events;
                                        eventsHTML+='<li><div class="rw1">';
                                        dateArray = ajaxResp[eventCounts].start_date.split("-");
                                        eneterdYear = dateArray[0];
                                        eneterdMonth = dateArray[1];
                                        eneterdDay = dateArray[2];
                                        enteredDate.setDate(eneterdDay.substr(0,2));
                                        enteredDate.setMonth(eneterdMonth-1);
                                        enteredDate.setYear(eneterdYear);
					if(ajaxResp[eventCounts].start_date_format!=ajaxResp[eventCounts].end_date_format){
                                        if(enteredDate>=today){
                                        eventsHTML+='<div class="Fnt10">starts on</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].start_month +'</div><div>'+ ajaxResp[eventCounts].start_day +'</div></div>';
                                        }else{
                                        eventsHTML+='<div class="Fnt10">upto</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].end_month +'</div><div>'+ ajaxResp[eventCounts].end_day +'</div></div>';
                                        }}else{
                                        eventsHTML+='<div class="Fnt10"></div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].start_month +'</div><div>'+ ajaxResp[eventCounts].start_day +'</div></div>';
                                        }
                                        eventsHTML+='</div><input type="hidden" id="start" autocomplete="off" value="'+ ajaxResp[eventCounts].start +'" /><input type="hidden" id="from_others" autocomplete="off" value="'+ ajaxResp[eventCounts].fromOthers +'" /><input type="hidden" id="total_events" autocomplete="off" value="'+ ajaxResp[eventCounts].total_events +'" /><div class="rw2"><div>';
					titleOfTheEvent=(ajaxResp[eventCounts].event_title).replace(new RegExp(' ','g'),'-');
                                        eventsHTML+='<a href="/events/Events/eventDetail/1/'+ ajaxResp[eventCounts].event_id +'/'+ titleOfTheEvent +'" class="">'+ ajaxResp[eventCounts].event_title +'</a>';
					eventsHTML+='</div><div class="drkGry">'+ ajaxResp[eventCounts].city_name +', '+ ajaxResp[eventCounts].country_name;
					if(ajaxResp[eventCounts].fromOthers>3){
					eventsHTML+=', '+ ajaxResp[eventCounts].start_date_format +' - '+ ajaxResp[eventCounts].end_date_format;
					}
					if(onClick!=''){
                                        onClick='calloverlayEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\',\'EVENTS\');return false;';
                                        }
					eventsHTML+='</div><a href="javascript:void(0);" onClick="'+ onClick +' subscribeEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\');return false;" class="vDLink_2">Subscribe</a></div><div class="clear_B"></div></li>';
                                                         }
                        document.getElementById('eventListByType').innerHTML = eventsHTML;
			var showingEventsHTML='';
			var startTemp=start+3;
			var startDisp=start+1;
                        if(total_events>startTemp){
                        showingEventsHTML+='<div class="float_L">Showing '+ startDisp +' - '+ startTemp +' out of '+ total_events +'</div>';
                        }else{
                        showingEventsHTML+='<div class="float_L">Showing '+ startDisp +' - '+ total_events +' out of '+ total_events +'</div>';
                        }
                        document.getElementById('showCount').innerHTML = showingEventsHTML;
			onSALoad(total_events);
                        }});
        return;
}
function backSlideOnClick(){
	var today = new Date();
        var enteredDate = new Date();
        var dateArray = '';
        var eneterdYear = '';
        var eneterdMonth = '';
	var titleOfTheEvent = '';
        var eneterdDay = '';
        var start=parseInt(document.getElementById('start').value)-3;
	var from_others=document.getElementById('from_others').value;
        var countryId= document.getElementById('countryId').value;
        var category_id= document.getElementById('categoryId').value;
        var count=3;
	var onClick = document.getElementById('onClick').value;
        var total_events=parseInt(document.getElementById('total_events').value);
	var days='';
        var url = '/events/Events/studyAbroadEvents/1/'+ from_others +'/'+ countryId + '/' + category_id +'/'+ start +'/'+ count;
        var a  = new Ajax.Request (url,{ method:'post', parameters: (from_others), onSuccess: function (xmlHttp) {
                        var ajaxResp = eval(xmlHttp.responseText);
                        var eventsHTML = '';
                        for(var eventCounts =0;eventCounts<ajaxResp.length;eventCounts++ ){
					total_events=ajaxResp[eventCounts].total_events;
                                        eventsHTML+='<li><div class="rw1">';
                                        dateArray = ajaxResp[eventCounts].start_date.split("-");
                                        eneterdYear = dateArray[0];
                                        eneterdMonth = dateArray[1];
                                        eneterdDay = dateArray[2];
                                        enteredDate.setDate(eneterdDay.substr(0,2));
                                        enteredDate.setMonth(eneterdMonth-1);
                                        enteredDate.setYear(eneterdYear);
                                        if(enteredDate>=today){
                                               eventsHTML+='<div class="Fnt10">starts on</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].start_month +'</div><div>'+ ajaxResp[eventCounts].start_day +'</div></div>';
                                        }else{
                                        eventsHTML+='<div class="Fnt10">upto</div><div class="sdtBg"><div class="whiteColor">'+ ajaxResp[eventCounts].end_month +'</div><div>'+ ajaxResp[eventCounts].end_day +'</div></div>';
                                        }
                                        eventsHTML+='</div><input type="hidden" id="start" autocomplete="off" value="'+ ajaxResp[eventCounts].start +'" /><input type="hidden" id="from_others" autocomplete="off" value="'+ ajaxResp[eventCounts].fromOthers +'" /><input type="hidden" id="total_events" autocomplete="off" value="'+ ajaxResp[eventCounts].total_events +'" /><div class="rw2"><div>';
					titleOfTheEvent=(ajaxResp[eventCounts].event_title).replace(new RegExp(' ','g'),'-');
                                        eventsHTML+='<a href="/events/Events/eventDetail/1/'+ ajaxResp[eventCounts].event_id +'/'+ titleOfTheEvent +'" class="">'+ ajaxResp[eventCounts].event_title +'</a>';
					eventsHTML+='</div><div class="drkGry">'+ ajaxResp[eventCounts].city_name +', '+ ajaxResp[eventCounts].country_name;
					if(ajaxResp[eventCounts].fromOthers>3){
					eventsHTML+=', '+ ajaxResp[eventCounts].start_date_format +' - '+ ajaxResp[eventCounts].end_date_format;
					}
					if(onClick!=''){
                                        onClick='calloverlayEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\',\'EVENTS\');return false;';
                                        }
					eventsHTML+='</div><a href="javascript:void(0);" onClick="'+ onClick +' subscribeEvents(\''+ ajaxResp[eventCounts].event_id +'\',\''+ addslashes(ajaxResp[eventCounts].event_title) +'\');return false;" class="vDLink_2">Subscribe</a></div><div class="clear_B"></div></li>';
                                                         }
                        document.getElementById('eventListByType').innerHTML = eventsHTML;
			var showingEventsHTML='';
			var startTemp=start+3;
			var startDisp=start+1;
                        if(total_events>startTemp){
			if(start!=0){
                        showingEventsHTML+='<div class="float_L">Showing '+ startDisp +' - '+ startTemp +' out of '+ total_events +'</div>';
			}else{
			showingEventsHTML+='<div class="float_L">Showing 1 - '+ startTemp +' out of '+ total_events +'</div>';}
                        }else{
			if(start!=0){
                        showingEventsHTML+='<div class="float_L">Showing '+ startDisp +' - '+ total_events +' out of '+ total_events +'</div>';
			}else{
			showingEventsHTML+='<div class="float_L">Showing 1 - '+ total_events +' out of '+ total_events +'</div>';}
                        }
                        document.getElementById('showCount').innerHTML = showingEventsHTML;
			onSALoad(total_events);
                        }});
		        return;
}
<?php if($total_events>0){?>
onSALoad(<?php echo $total_events; ?>);
<?php } ?>
		function onSALoad(total){
		 var start=parseInt('0');
		 if(total>0){
                 start=parseInt(document.getElementById('start').value);
		 }
                 if(start<=0){
			if(total<=start+3 || (start+3)>=30){
			document.getElementById('rightSliders').style.display = 'none';
			}else{
			document.getElementById('rightSliders').style.display = 'block';
			}
			document.getElementById('leftSliders').style.display = 'none';
                        }else{
			document.getElementById('rightSliders').style.display = 'none';
                        document.getElementById('leftSliders').style.display = 'block';  
                        }
                 if(total<=start+3 || (start+3)>=30){
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
		if(start <= 0 || total<=start+3 || start+3>=30){
			document.getElementById('bothSliders').style.display = 'none';	
		}else{
			document.getElementById('bothSliders').style.display = 'block';
			document.getElementById('leftSliders').style.display = 'none';
			document.getElementById('rightSliders').style.display = 'none';
		}
}
</script>
