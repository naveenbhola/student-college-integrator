        <div class="pf10">
        	<div style="height:350px;overflow:auto">
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='free_laptop' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Free Laptop:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($freeLaptop=='yes')echo "checked='checked'";?> type="radio" name="free_laptop[]" id="free_laptop_yes" value="yes" /> Yes &nbsp; &nbsp; <input <?php if($freeLaptop=='no')echo "checked='checked'";?> type="radio" name="free_laptop[]" id="free_laptop_no" value="no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='foreign_study_tour' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Foreign travel:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($foreignStudy=='yes')echo "checked='checked'";?> type="radio" value="yes" name="foreign_study_tour[]" id="foreign_study_tour_yes" /> Foreign Study Tour &nbsp; &nbsp; <input <?php if($foreignStudy=='no')echo "checked='checked'";?> type="radio" value="no" name="foreign_study_tour[]" id="foreign_study_tour_no" /> Foreign Exchange Program</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <!--div class="wdh100 mb10">
                        <div class="float_L w200"><div class="txt_align_r pr10 lineSpace_17"><strong>Foreign Semester Exchange:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php // if($studyExchange=='yes')echo "checked='checked'";?> type="radio" value="yes" name="foreign_semester_exchange[]" id="foreign_semester_exchange_yes" /> Yes &nbsp; &nbsp; <input <?php // if($studyExchange=='no')echo "checked='checked'";?> type="radio" value="no" name="foreign_semester_exchange[]" id="foreign_semester_exchange_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div-->
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='job_assurance' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Job Assurance:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($jobAssurance=='guarantee')echo "checked='checked'";?> type="radio" value="guarantee" name="job_assurance[]" id="job_guarantee" /> 100% job guarantee<br /><input <?php if($jobAssurance=='record')echo "checked='checked'";?> type="radio" value="record" name="job_assurance[]" id="job_record" /> 100% job track record<br /><input <?php if($jobAssurance=='assurance')echo "checked='checked'";?> type="radio" value="assurance" name="job_assurance[]" id="job_assurance" /> 100% placement assistance</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='dual_degree' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Dual Degree:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($dualDegree=='yes')echo "checked='checked'";?> type="radio" value="yes" name="dual_degree[]" id="dual_degree_yes" /> Yes - MBA + PGDM &nbsp; &nbsp; <input <?php if($dualDegree=='no')echo "checked='checked'";?> type="radio" value="no" name="dual_degree[]" id="dual_degree_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='in_campus_hostel' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>In-campus Hostel:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($hostel=='yes')echo "checked='checked'";?> type="radio" value="yes" name="in_campus_hostel[]" id="in_campus_hostel_yes" /> Yes &nbsp; &nbsp; <input <?php if($hostel=='no')echo "checked='checked'";?> type="radio" value="no" name="in_campus_hostel[]" id="in_campus_hostel_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='transport_facility' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Transport Facility:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($transport=='yes')echo "checked='checked'";?> type="radio" value="yes" name="transport_facility[]" id="transport_facility_yes" /> Yes &nbsp; &nbsp; <input <?php if($transport=='no')echo "checked='checked'";?> type="radio" value="no" name="transport_facility[]" id="transport_facility_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>                        
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='free_training' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Free Training:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($freeTraining=='sap')echo "checked='checked'";?> type="radio" value="sap" name="free_training[]" id="free_training_sap" /> SAP &nbsp; &nbsp; <input <?php if($freeTraining=='english')echo "checked='checked'";?> type="radio" value="english" name="free_training[]" id="free_training_english" /> English &nbsp; &nbsp; <input <?php if($freeTraining=='soft_skills')echo "checked='checked'";?> type="radio" value="soft_skills" name="free_training[]" id="free_training_soft_skills" /> Soft Skills &nbsp; &nbsp; <input <?php if($freeTraining=='foreign_language')echo "checked='checked'";?> type="radio" value="foreign_language" name="free_training[]" id="free_training_foreign_language" /> Foreign Language</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='wifi_campus' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>Wifi Campus:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($wifi=='yes')echo "checked='checked'";?> type="radio" value="yes" name="wifi_campus[]" id="wifi_campus_yes" /> Yes &nbsp; &nbsp; <input <?php if($wifi=='no')echo "checked='checked'";?> type="radio" value="no" name="wifi_campus[]" id="wifi_campus_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
                    <div class="wdh100 mb10">
                    	<div class="float_L w25"><div class="txt_align_r pr10 lineSpace_2"><input type='checkbox' name='reset_salient_feature' value='acampus' /></div></div>
                        <div class="float_L w150"><div class="txt_align_l pr10 lineSpace_22"><strong>AC Campus:</strong></div></div>
                        <div class="float_L  w395"><div><input <?php if($acCampus=='yes')echo "checked='checked'";?> type="radio" value="yes" name="acampus[]" id="ac_campus_yes" /> Yes &nbsp; &nbsp; <input <?php if($acCampus=='no')echo "checked='checked'";?> type="radio" value="no" name="acampus[]" id="ac_campus_no" /> No</div></div>
                        <div class="clear_B">&nbsp;</div>
                    </div>
			</div>
            <div class="lineSpace_20"><a href="javascript:;" onclick="resetFeatures('selected');">Reset selected features</a> &nbsp; | &nbsp;<a href="javascript:;" onclick="resetFeatures('all');">Reset all</a></div>
            <div align="center"><input type="button" value="&nbsp;" class="entr_Allbtn ebtn_6" onclick="saveData();"/> &nbsp; &nbsp; <a href="javascript:void(0)" onclick="hideLoginOverlay()" class="Fnt16"><strong>Cancel</strong></a></div>            
            <div class="lineSpace_10">&nbsp;</div>
        </div>
<script language="javascript">

function resetFeatures(action)
{
	/*
	* This is a map between radio button names used above
	* and corresponding values in preserveness 
	* e.g. for "Free Laptop" salient feature - radio button name is free_laptop
	* and value in preserveness is c_freeLaptop=yes or c_freeLaptop=no
	*/
	var field_map = {
						'free_laptop':'c_freeLaptop',
						'foreign_study_tour':'c_foreignStudy',
						'job_assurance':'c_jobAssurance',
						'dual_degree':'c_dualDegree',
						'in_campus_hostel':'c_hostel',
						'transport_facility':'c_transport',
						'free_training':'c_freeTraining',
						'wifi_campus':'c_wifi',
						'acampus':'c_acCampus'
					};	

	/*
	* We will keep track of all the keys we reset
	*/
	var reset_keys = new Array();
	var valid_reset = false;
	
	var reset_checkboxes = document.getElementsByName('reset_salient_feature');
	if(reset_checkboxes.length)
	{
		for(var i=0;i<reset_checkboxes.length;i++)
		{
			if(reset_checkboxes[i].checked || action == 'all')
			{
				sf_value = reset_checkboxes[i].value;

				var sf_radiobuttons = document.getElementsByName(sf_value+'[]');

				if(sf_radiobuttons.length)
				{
					for(var j=0;j<sf_radiobuttons.length;j++)
					{
						sf_radiobuttons[j].checked = false
					}
				}	

				/*
				* Store key in reset keys array
				* e.g. free_laptop
				*/
				reset_keys.push(sf_value);

				valid_reset = true;

				reset_checkboxes[i].checked = false;
			}
		}
	}

	if(valid_reset)
	{	
		/*
		* Now remove reset keys from preserveness
		* preserveness will be like: c_freeLaptop=yes&c_foreignStudy=no&c_jobAssurance=guarantee...
		*/
	
		var preserveness_var = $('c_salientFeatures_preserveness_var').value;
		
	    var preserveness_array = preserveness_var.split("&");
	
	    var preserveness_new_array = new Array();
	
	    for(var i=0;i<preserveness_array.length;i++)
	    {
	        if(preserveness_array[i])
	        {
	            /*
	            * You will get a value like c_freeLaptop=yes
	            * Then split it with = 
	            */	
	            var key_value_pair = preserveness_array[i].split("=");
	
	            /*
	            * Match key in reset_keys mapped using field_map
	            */
	            
	            var flag = 1;
	            for(j=0;j<reset_keys.length;j++)
	            {
	                if(field_map[reset_keys[j]] == key_value_pair[0])
	                {
	                    flag = 0;
	                    break;
	                }
	            }
	
	            /*
	            * If not matched in reset keys array, store in new preserveness array
	            */
				if(flag == 1)
				{
					preserveness_new_array.push(preserveness_array[i]);
				}
				else
				{
					preserveness_new_array.push(key_value_pair[0]+"=-1");
				}           
	    	}
	    }
		
	    $('c_salientFeatures_preserveness_var').value = preserveness_new_array.join("&");
	}
	else
	{
		alert("Please select at least one feature to reset");
	}
}


function saveData(){
        // alert("Orig value = "+$('c_salientFeatures').value);
    	$("c_salientFeatures_preserveness_var").value = "";

	var data = '';	
 	if($('free_laptop_yes').checked == true){
 	 	data += "c_freeLaptop=yes&";
 	}
 	else if($('free_laptop_no').checked == true){
	 	data += "c_freeLaptop=no&";
	}
 	else{
	 	data += "c_freeLaptop=-1&";
	}
	

	if($('foreign_study_tour_yes').checked == true){
		data += "c_foreignStudy=yes&";
	}
	else if($('foreign_study_tour_no').checked == true){
		data += "c_foreignStudy=no&";
	}
	else{
		data += "c_foreignStudy=-1&";
	}
    /*
	if($('foreign_semester_exchange_yes').checked == true){
		data += "c_studyExchange=yes&";
	}
	if($('foreign_semester_exchange_no').checked == true){
		data += "c_studyExchange=no&";
	}
    */
	if($('job_guarantee').checked == true){
		data += "c_jobAssurance=guarantee&";
	}
	else if($('job_assurance').checked == true){
		data += "c_jobAssurance=assurance&";
	}
	else if($('job_record').checked == true){
		data += "c_jobAssurance=record&";
	}
	else{
		data += "c_jobAssurance=-1&";
	}

	if($('dual_degree_yes').checked == true){
		data += "c_dualDegree=yes&";
	}
	else if($('dual_degree_no').checked == true){
		data += "c_dualDegree=no&";
	}
	else{
		data += "c_dualDegree=-1&";
	}

	if($('in_campus_hostel_yes').checked == true){
		data += "c_hostel=yes&";
	}
	else if($('in_campus_hostel_no').checked == true){
		data += "c_hostel=no&";
	}
	else{
		data += "c_hostel=-1&";
	}

	if($('transport_facility_yes').checked == true){
		data += "c_transport=yes&";
	}
	else if($('transport_facility_no').checked == true){
		data += "c_transport=no&";
	}
	else{
		data += "c_transport=-1&";
	}
	
	if($('free_training_sap').checked == true){
		data += "c_freeTraining=sap&";
	}
	else if($('free_training_english').checked == true){
		data += "c_freeTraining=english&";
	}	
	else if($('free_training_soft_skills').checked == true){
		data += "c_freeTraining=soft_skills&";
	}	
	else if($('free_training_foreign_language').checked == true){
		data += "c_freeTraining=foreign_language&";
	}	
	else{
		data += "c_freeTraining=-1&";
	}

	
	if($('wifi_campus_yes').checked == true){
		data += "c_wifi=yes&";
	}	
	else if($('wifi_campus_no').checked == true){
		data += "c_wifi=no&";
	}
	else{
		data += "c_wifi=-1&";
	}
	
	if($('ac_campus_yes').checked == true){
		data += "c_acCampus=yes&";
	}
	else if($('ac_campus_no').checked == true){
		data += "c_acCampus=no&";
	}
	else{
		data += "c_acCampus=-1&";
	}
	
	data = data.substr(0,data.length-1);
        
    // alert(data);
    $('c_salientFeatures_preserveness_var').value = data;

	var data_c_salientFeatures_array = new Array();
	var data_array = data.split("&");
	for(var i=0;i<data_array.length;i++)
	{
		if(data_array[i])
		{
			var key_value_pair = data_array[i].split("=");
			if(key_value_pair[1] != '-1')
			{
				data_c_salientFeatures_array.push(data_array[i]);
			}
			
		}
	}

	$('c_salientFeatures').value = base64_encode(data_c_salientFeatures_array.join("&"));
	
	//$('c_salientFeatures').value = data_c_salientFeatures_array.join("&");
	
	hideLoginOverlay();
}


// This function is defined by Amit Kuksal on 28th Dec 2010 against Bug Id: 40577
function preserveElementsSelection() {
        if($('c_salientFeatures_preserveness_var').value != "") {

            var preserveness_var = $('c_salientFeatures_preserveness_var').value;
            var preserveness_array = preserveness_var.split("&");
            // alert(preserveness_array.length);
            var array_length = preserveness_array.length;

            for (i = 0; i< array_length; i++) {
                
                // alert(preserveness_array[i]);
                if(preserveness_array[i] == "c_freeLaptop=yes"){
                        $('free_laptop_yes').checked = true;
                } else if(preserveness_array[i] == "c_freeLaptop=no"){
                        $('free_laptop_no').checked = true;                        
                }
                else if(preserveness_array[i] == "c_freeLaptop=-1"){
                	$('free_laptop_yes').checked = false;
                	$('free_laptop_no').checked = false;
                }

                if(preserveness_array[i] == "c_foreignStudy=yes"){                
                       $('foreign_study_tour_yes').checked = true;
                } else if(preserveness_array[i] == "c_foreignStudy=no"){
                       $('foreign_study_tour_no').checked = true
                } else  if(preserveness_array[i] == "c_foreignStudy=-1"){
                		$('foreign_study_tour_yes').checked = false;	
                    	$('foreign_study_tour_no').checked = false;
                }
                /*
                if(preserveness_array[i] == "c_studyExchange=yes"){
                        $('foreign_semester_exchange_yes').checked = true;                        
                } else if(preserveness_array[i] == "c_studyExchange=no"){
                        $('foreign_semester_exchange_no').checked = true;                        
                }
                */
                if(preserveness_array[i] == "c_jobAssurance=guarantee"){
                       $('job_guarantee').checked = true;
                } else if(preserveness_array[i] == "c_jobAssurance=assurance"){
                        $('job_assurance').checked = true;
                }  else if(preserveness_array[i] == "c_jobAssurance=record"){      
                       $('job_record').checked = true;
                } else  if(preserveness_array[i] == "c_jobAssurance=-1"){
                		$('job_guarantee').checked = false;	   
                		$('job_assurance').checked = false;   
                       	$('job_record').checked = false;
                }
                
                if(preserveness_array[i] == "c_dualDegree=yes"){
                        $('dual_degree_yes').checked = true;
                } else if(preserveness_array[i] == "c_dualDegree=no"){
                        $('dual_degree_no').checked = true;
                }
                else if(preserveness_array[i] == "c_dualDegree=-1"){
                	$('dual_degree_yes').checked = false;	
                    $('dual_degree_no').checked = false;
            	}

                if(preserveness_array[i] == "c_hostel=yes"){
                        $('in_campus_hostel_yes').checked = true
                } else if(preserveness_array[i] == "c_hostel=no"){
                        $('in_campus_hostel_no').checked = true
                }
                else if(preserveness_array[i] == "c_hostel=-1"){
                	$('in_campus_hostel_yes').checked = false;
                    $('in_campus_hostel_no').checked = false;
            	}

                if(preserveness_array[i] == "c_transport=yes"){
                        $('transport_facility_yes').checked = true;
                } else if(preserveness_array[i] == "c_transport=no"){
                        $('transport_facility_no').checked = true
                }
                else  if(preserveness_array[i] == "c_transport=-1"){
                	$('transport_facility_yes').checked = false;
                    $('transport_facility_no').checked = false;
            	}

                if(preserveness_array[i] == "c_freeTraining=sap"){
                        $('free_training_sap').checked = true;
                } else if(preserveness_array[i] == "c_freeTraining=english"){
                        $('free_training_english').checked = true;
                } else if(preserveness_array[i] == "c_freeTraining=soft_skills"){
                        $('free_training_soft_skills').checked = true;
                } else if(preserveness_array[i] == "c_freeTraining=foreign_language"){
                        $('free_training_foreign_language').checked = true;
                }
                else  if(preserveness_array[i] == "c_freeTraining=-1"){
                	 $('free_training_sap').checked = false;	
                	 $('free_training_english').checked = false;
                	 $('free_training_soft_skills').checked = false;
                     $('free_training_foreign_language').checked = false;
            	}

                if(preserveness_array[i] == "c_wifi=yes"){
                        $('wifi_campus_yes').checked = true;
                } else if(preserveness_array[i] == "c_wifi=no"){
                        $('wifi_campus_no').checked = true;
                }
                else  if(preserveness_array[i] == "c_wifi=-1"){
                	$('wifi_campus_yes').checked = false;
                    $('wifi_campus_no').checked = false;
            	}
                
                if(preserveness_array[i] == "c_acCampus=yes"){
                        $('ac_campus_yes').checked = true;
                } else if(preserveness_array[i] == "c_acCampus=no"){
                        $('ac_campus_no').checked = true;
                } 
                else  if(preserveness_array[i] == "c_acCampus=-1"){
                	$('ac_campus_yes').checked = false;
                    $('ac_campus_no').checked = false;
            	}                               
               
            }   // End of for (i = 0; i< array_length; i++).

        }   // End of if($('c_salientFeatures_preserveness_var').value != "").
}
</script>
