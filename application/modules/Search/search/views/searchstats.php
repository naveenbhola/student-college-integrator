<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Shiksha Search Stats</title>
<style type="text/css">

/* General styles */
body { margin: 0; padding: 0; font: 80%/1.5 Arial,Helvetica,sans-serif; color: #111; background-color: #FFF; }
h2 { margin: 0px; padding: 10px; font-family: Georgia, "Times New Roman", Times, serif; font-size: 200%; font-weight: normal; color: #CCC; background-color: !#CCC; border-bottom: #BBB 2px solid; }
p#copyright { margin: 20px 10px; font-size: 90%; color: #999; }
/* Form styles */
div.form-container { margin: 10px; padding: 5px; background-color: #FFF; border: #EEE 1px solid; }
p.legend { margin-bottom: 1em; }
p b { font-family: Georgia, "Times New Roman", Times, serif; font-size: 15px; font-weight: bold; color: #000; }
p.legend em { color: #C00; font-style: normal; }
div.errors { margin: 0 0 10px 0; padding: 5px 10px; border: #FC6 1px solid; background-color: #FFC; }
div.errors p { margin: 0; }
div.errors p em { color: #C00; font-style: normal; font-weight: bold; }
div.form-container form p { margin: 0; }
div.form-container form p.note { margin-left: 170px; font-size: 90%; color: #333; }
div.form-container form fieldset { margin: 10px 0; padding: 10px; border: #DDD 1px solid; }
div.form-container form legend { font-weight: bold; color: #666; }
div.form-container form fieldset div { padding: 0.25em 0; }
div.form-container label, 
div.form-container span.label { margin-right: 10px; width: 75px; display: block; float: left; text-align: right; position: relative; }
div.form-container label.error, 
div.form-container span.error { color: #C00; }
div.form-container label em, 
div.form-container span.label em { position: absolute; right: 0; font-size: 120%; font-style: normal; color: #C00; }
div.form-container input.error { border-color: #C00; background-color: #FEF; }
div.form-container input:focus,
div.form-container input.error:focus, 
div.form-container textarea:focus {	background-color: #FFC; border-color: #FC6; }
div.form-container div.controlset label, 
div.form-container div.controlset input { display: inline; float: none; }
div.form-container div.controlset div { margin-left: 170px; }
div.form-container div.buttonrow { margin-left: 180px; }
.mmp_main_container { margin: 10px; padding: 5px; background-color: #FFF; border: #EEE 1px solid; }
.mmp_message_container { margin: 0 0 10px 0; padding: 5px 10px; border: #FC6 1px solid; background-color: #FFC; }
.mmp_message_container p { margin: 0; }
.mmp_message_container  p em { color: #C00; font-style: normal; font-weight: bold; }
.mmp_success_message_container { margin: 0 0 10px 0; padding: 5px 10px; border: #FC6 1px solid; background-color: #FFC; }
.mmp_success_message_container p { margin: 0; }
.mmp_success_message_container  p em { color: #698B22; font-style: normal; font-weight: bold; }
.mmp_add_new_mmp_form_container { margin: 0 0 10px 0; padding: 5px 10px; border: #EEE 1px solid;}
.mmp_add_new_mmp_form_container p { margin: 0; }
.mmp_add_new_mmp_form_container  p em { color: #C00; font-style: normal; font-weight: bold; }
.mmp_note_text_container { margin: 0px; padding: 5px 10px; border: #C3C3C3 1px solid; background-color: #f5f5f5; }
.mmp_note_text_container p { margin: 0; }
.mmp_listing_table { background-color: #FFFFFF; border: 1px solid #C3C3C3; border-collapse: collapse; width: 100%; }
.mmp_listing_col_heading { background-color: #FFC;border: 1px solid #C3C3C3;padding: 5px;vertical-align: top;}
.mmp_listing_col_style { border: 1px solid #C3C3C3; padding: 3px; vertical-align: top; color : #111111;}
.mmp_main_fieldset { margin: 10px 0; padding: 10px; border: #DDD 1px solid; }
.mmp_main_fieldsetlegend { font-weight: bold; color: #666; }
.mmp_field_container {margin:0px 0px 10px 0px}
.mmp_field_help_note {margin-left: 120px; font-size: 10px; color: #ADACCA;}
.mmp_field_error_note {margin-left: 120px; font-size: 10px; color: #C00;}
.mmp_field_label {margin-right: 10px; padding-right: 10px; width: 100px; display: block; float: left; text-align: right; position: relative; color: #333;}
.mmp_field_label em { position: absolute; right: 0; font-size: 120%; font-style: normal; color: #C00; }
.mmp_form_buttons{margin-left: 180px; margin-top: 20px;}
.new_mmp_button_container {width:100%; text-align:right;}
.add_new_mmp_button_width{width:110px;}

</style>
</head>
<body>
	<?php
	$month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
	$currentDay =  $_REQUEST['from_day'];
	if(empty($currentDay)){
		$currentDay = date('j');
	}
	$currentMonth = $_REQUEST['from_month'];
	if(empty($currentMonth)){
		$currentMonth = date('n');
	}
	$currentYear = $_REQUEST['from_year'];
	if(empty($currentYear)){
		$currentYear = date("Y");
	}
	$year = array(date("Y"), date("Y") - 1, date("Y") - 2);
	$currentToDay =  $_REQUEST['to_day'];
	if(empty($currentToDay)){
		$currentToDay = date('j');
	}
	$currentToMonth = $_REQUEST['to_month'];
	if(empty($currentToMonth)){
		$currentToMonth = date('n');
	}
	$currentToYear = $_REQUEST['to_year'];
	if(empty($currentToYear)){
		$currentToYear = date("Y");
	}
	?>
    <div id="wrapper">
		<div style="float:left;width:98%;padding:10px;border-bottom:2px solid #BBB;">
			<div style="float:left;">
				<span style="font-family: Georgia, 'Times New Roman', Times, serif; font-size: 300%; font-weight: normal; color: #CCC;">Search Stats</span>
			</div>
			<div style="float:right;">
				<img src="/public/images/nshik_ShikshaLogo2.gif"/>
			</div>
			
		</div>
		<div style="padding:0px;clear:both;"></div>
        <div class="form-container">
            <form action="" method="get">
            <fieldset>
                <legend>Select date</legend>
                <div style="float:left;">
                    <div style="float:left;">
						<label for="type" >From:</label>
						<select id="from_day" name="from_day">
							<optgroup label="day">
								<?php
								for($i=1; $i <= 31; $i++){
									$selected = "";
									if($i == $currentDay){
										$selected = 'selected="selected"';
									}
								?>
									<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
								<?php
								}
								?>
							</optgroup>
						</select>
						<select id="from_month" name="from_month">
							<optgroup label="month">
								<?php
								for($i=1; $i <= 12; $i++){
									$selected = "";
									if($i == $currentMonth){
										$selected = 'selected="selected"';
									}
								?>
									<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $month[$i-1];?></option>
								<?php
								}
								?>
							</optgroup>
						</select>
						<select id="from_year" name="from_year">
							<optgroup label="year">
								<?php
								for($i=1; $i <= 3; $i++){
									$selected = "";
									if($year[$i - 1] == $currentYear){
										$selected = 'selected="selected"';
									}
								?>
									<option <?php echo $selected;?> value="<?php echo $year[$i-1];?>"><?php echo $year[$i-1];?></option>
								<?php
								}
								?>
							</optgroup>
						</select>
					</div>
                    <div>
						<label for="type">To:</label>
						<select id="to_day" name="to_day">
								<optgroup label="day">
									<?php
									for($i=1; $i <= 31; $i++){
										$selected = "";
										if($i == $currentToDay){
											$selected = 'selected="selected"';
										}
									?>
										<option <?php echo $selected;?> value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php
									}
									?>
								</optgroup>
							</select>
							<select id="to_month" name="to_month">
								<optgroup label="month">
									<?php
									for($i=1; $i <= 12; $i++){
										$selected = "";
										if($i == $currentToMonth){
											$selected = 'selected="selected"';
										}
									?>
										<option <?php echo $selected;?> value="<?php echo $i;?>"><?php echo $month[$i-1];?></option>
									<?php
									}
									?>
								</optgroup>
							</select>
							<select id="to_year" name="to_year">
								<optgroup label="year">
									<?php
									for($i=1; $i <= 3; $i++){
										$selected = "";
										if($year[$i - 1] == $currentToYear){
											$selected = 'selected="selected"';
										}
									?>
										<option <?php echo $selected; ?> value="<?php echo $year[$i-1];?>"><?php echo $year[$i-1];?></option>
									<?php
									}
									?>
								</optgroup>
							</select>
							<input type="hidden" name="submit" value="submit" />
					</div>
                </div>
				<div style="clear:both;padding:0px;"></div>
				<div class="buttonrow" style="margin-left:85px;">
					<input type="submit" value="Submit" class="button"/>
					<input type="reset" value="Reset" class="button" onclick="window.location='/search/Indexer/stats'"/>
				</div>
            </fieldset>
			<div>
				<?php
				$originalFromDateString = $currentDay . '-'. $currentMonth . '-' . $currentYear;
				$originalToDateString = $currentToDay . '-'. $currentToMonth . '-' . $currentToYear;
				
				if(!empty($data)){
					$fromDateString = $originalFromDateString;
					$toDateString = $originalToDateString;
				?>
					<p>Data fetched for date range from <b><?php echo date('j M Y', strtotime($fromDateString));?></b> to <b><?php echo date('j M Y', strtotime($toDateString));?></b>.</p>
					<div style="margin-top:15px;width:50%;">
						<p><b>Registration Stats:</b></p>
						<div class="mmp_listing">
							<table class="mmp_listing_table">
								<tr>
									<th class="mmp_listing_col_heading" style="background-color:#E5EECC;font-size:12px;" align="left">Date</th>
									<th class="mmp_listing_col_heading" style="background-color:#E5EECC;font-size:12px;" align="left">E-Brochure requested</th>
									<th class="mmp_listing_col_heading" style="background-color:#E5EECC;font-size:12px;" align="left">Registration</th>
								</tr>
								<?php
								$ebrochure 			= $data['ebrochure'];
								$registration 		= $data['registration'];
								$total = array();
								$fromDateString = $originalFromDateString;
								$toDateString = $originalToDateString;
								while (strtotime($fromDateString) <= strtotime($toDateString)) {
									$date = date("Y-m-d", strtotime($fromDateString));
									?>
									<tr>
										<td class="mmp_listing_col_style">
											<?php echo $date;?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$eb = (!empty($ebrochure[$date]) ? $ebrochure[$date] : 0);
											$total['ebrochure'] += $eb;
											echo $eb;
											?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$reg = (!empty($registration[$date]) ? $registration[$date] : 0);
											$total['registration'] += $reg;
											echo $reg;
											?>
										</td>
									</tr>
									<?php
									$fromDateString = date("Y-m-d", strtotime("+1 day", strtotime($fromDateString)));
								}
								?>
								<tr>
									<td class="mmp_listing_col_style" style="font-weight:bold;">Total</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php echo $total['ebrochure']; ?>
									</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php echo $total['registration']; ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<div style="margin-top:15px;width:50%;">
						<p><b>LoggedIn Stats:</b></p>
						<?php
						$resultLoggedStats 		= $data['loggedStats'];
						$distinctKeys = array_keys($resultLoggedStats);
						if(count($distinctKeys) > 0){
							?>
							<div class="mmp_listing">
								<table class="mmp_listing_table">
									<tr>
										<th class="mmp_listing_col_heading" style="background-color:#E6F3FB;font-size:12px;width:80px;" align="left">Date</th>
										<?php
										foreach($distinctKeys as $key){
											if($key == "loggedin"){
												$key = "loggedin<br/>(non ldb user)";
											}
											$key = strtoupper($key);
											?>
											<th class="mmp_listing_col_heading" style="background-color:#E6F3FB;font-size:12px;" align="left"><?php echo $key;?></th>
											<?php
										}
										?>
									</tr>
									<?php
									$total = array();
									$fromDateString = $originalFromDateString;
									$toDateString = $originalToDateString;
									while (strtotime($fromDateString) <= strtotime($toDateString)) {
										$date = date("Y-m-d", strtotime($fromDateString));
										?>
										<tr>
											<td class="mmp_listing_col_style">
												<?php echo $date;?>
											</td>
											<?php
											foreach($distinctKeys as $key){
											?>
											<td class="mmp_listing_col_style">
												<?php
												$v = (!empty($resultLoggedStats[$key][$date]) ? $resultLoggedStats[$key][$date] : 0);
												$total[$key] += $v;
												echo $v;
												?>
											</td>
											<?php
											}
											?>
										</tr>
										<?php
										$fromDateString = date("Y-m-d", strtotime("+1 day", strtotime($fromDateString)));
									}
									?>
									<tr>
										<td class="mmp_listing_col_style" style="font-weight:bold;">Total</td>
										<?php
										foreach($distinctKeys as $key){
										?>
										<td class="mmp_listing_col_style" style="font-weight:bold;">
											<?php
											$v = (!empty($total[$key]) ? $total[$key] : 0);
											echo $v;
											?>
										</td>
										<?php
										}
										?>
									</tr>
								</table>
							</div>
							<?php
						}
						?>
					</div>
					
					
					<div style="margin-top:10px;">
						<p><b>SearchQueries Stats:</b></p>
						<div class="mmp_listing">
							<table class="mmp_listing_table">
								<tr>
									<th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Date</th>
									<th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Total search queries</th>
									<th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Single institute search</th>
									<th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Results clicked</th>
									<th class="mmp_listing_col_heading" style="font-size:12px;" align="left">Pagination used</th>
								</tr>
								<?php
								$searchQueries 		= $data['search_queries'];
								$singleInstitute 	= $data['single_institute'];
								$resultClicked 		= $data['result_clicked'];
								$pagination 		= $data['pagination'];
								$total = array();
								
								$fromDateString = $originalFromDateString;
								$toDateString = $originalToDateString;
								while (strtotime($fromDateString) <= strtotime($toDateString)) {
									$date = date("Y-m-d", strtotime($fromDateString));
									?>
									<tr>
										<td class="mmp_listing_col_style">
											<?php echo $date;?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$tsq = (!empty($searchQueries[$date]) ? $searchQueries[$date] : 0);
											$total['search_queries'] += $tsq;
											echo $tsq;
											?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$sic = (!empty($singleInstitute[$date]) ? $singleInstitute[$date] : 0);
											$total['single_institute'] += $sic;
											echo $sic . ' ('. round( (($sic / $tsq) * 100), 2) .'%)';
											?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$rc = (!empty($resultClicked[$date]) ? $resultClicked[$date] : 0);
											$total['result_clicked'] += $rc;
											echo $rc . ' ('. round( (($rc / $tsq) * 100), 2) .'%)';
											?>
										</td>
										<td class="mmp_listing_col_style">
											<?php
											$pg = (!empty($pagination[$date]) ? $pagination[$date] : 0);
											$total['pagination'] += $pg;
											echo $pg . ' ('. round( (($pg / $tsq) * 100), 2) .'%)';
											?>
										</td>
									</tr>
									<?php
									$fromDateString = date("Y-m-d", strtotime("+1 day", strtotime($fromDateString)));
								}
								?>
								<tr>
									<td class="mmp_listing_col_style" style="font-weight:bold;">Total</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php
											echo $total['search_queries'];
										?>
									</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php echo $total['single_institute'] . ' ('. round( (($total['single_institute'] / $total['search_queries']) * 100), 2) .'%)'; ?>
									</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php echo $total['result_clicked'] . ' ('. round( (($total['result_clicked'] / $total['search_queries']) * 100), 2) .'%)'; ?>
									</td>
									<td class="mmp_listing_col_style" style="font-weight:bold;">
										<?php echo $total['pagination'] . ' ('. round( (($total['pagination'] / $total['search_queries']) * 100), 2) .'%)'; ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<div style="margin-top:15px;">
						<p><b>ResultClick Stats:</b></p>
						<?php
						$resultClickStats 		= $data['result_click_stats'];
						$distinctKeys = array_keys($resultClickStats);
						if(count($distinctKeys) > 0){
							?>
							<div class="mmp_listing">
								<table class="mmp_listing_table">
									<tr>
										<th class="mmp_listing_col_heading" style="background-color:#EEE;font-size:12px;width:80px;" align="left">Date</th>
										<?php
										foreach($distinctKeys as $key){
											?>
											<th class="mmp_listing_col_heading" style="background-color:#EEE;font-size:12px;" align="left"><?php echo strtoupper($key);?></th>
											<?php
										}
										?>
									</tr>
									<?php
									$total = array();
									$fromDateString = $originalFromDateString;
									$toDateString = $originalToDateString;
									while (strtotime($fromDateString) <= strtotime($toDateString)) {
										$date = date("Y-m-d", strtotime($fromDateString));
										?>
										<tr>
											<td class="mmp_listing_col_style">
												<?php echo $date;?>
											</td>
											<?php
											foreach($distinctKeys as $key){
											?>
											<td class="mmp_listing_col_style">
												<?php
												$v = (!empty($resultClickStats[$key][$date]) ? $resultClickStats[$key][$date] : 0);
												$total[$key] += $v;
												echo $v;
												?>
											</td>
											<?php
											}
											?>
										</tr>
										<?php
										$fromDateString = date("Y-m-d", strtotime("+1 day", strtotime($fromDateString)));
									}
									?>
									<tr>
										<td class="mmp_listing_col_style" style="font-weight:bold;">Total</td>
										<?php
										foreach($distinctKeys as $key){
										?>
										<td class="mmp_listing_col_style" style="font-weight:bold;">
											<?php
											$v = (!empty($total[$key]) ? $total[$key] : 0);
											echo $v;
											?>
										</td>
										<?php
										}
										?>
									</tr>
								</table>
							</div>
							<?php
						}
						?>
					</div>
					
					<div style="margin-top:15px;">
						<p><b>Page Stats:</b></p>
						<?php
						$fromPageStats 		= $data['fromPageStats'];
						$distinctKeys = array_keys($fromPageStats);
						if(count($distinctKeys) > 0){
							?>
							<div class="mmp_listing">
								<table class="mmp_listing_table">
									<tr>
										<th class="mmp_listing_col_heading" style="background-color:#FFEBE8;font-size:12px;width:80px;" align="left">Date</th>
										<?php
										foreach($distinctKeys as $key){
											?>
											<th class="mmp_listing_col_heading" style="background-color:#FFEBE8;font-size:12px;" align="left"><?php echo strtoupper($key);?></th>
											<?php
										}
										?>
									</tr>
									<?php
									$total = array();
									$fromDateString = $originalFromDateString;
									$toDateString = $originalToDateString;
									while (strtotime($fromDateString) <= strtotime($toDateString)) {
										$date = date("Y-m-d", strtotime($fromDateString));
										?>
										<tr>
											<td class="mmp_listing_col_style">
												<?php echo $date;?>
											</td>
											<?php
											foreach($distinctKeys as $key){
											?>
											<td class="mmp_listing_col_style">
												<?php
												$v = (!empty($fromPageStats[$key][$date]) ? $fromPageStats[$key][$date] : 0);
												$total[$key] += $v;
												echo $v;
												?>
											</td>
											<?php
											}
											?>
										</tr>
										<?php
										$fromDateString = date("Y-m-d", strtotime("+1 day", strtotime($fromDateString)));
									}
									?>
									<tr>
										<td class="mmp_listing_col_style" style="font-weight:bold;">Total</td>
										<?php
										foreach($distinctKeys as $key){
										?>
										<td class="mmp_listing_col_style" style="font-weight:bold;">
											<?php
											$v = (!empty($total[$key]) ? $total[$key] : 0);
											echo $v;
											?>
										</td>
										<?php
										}
										?>
									</tr>
								</table>
							</div>
							<?php
						}
						?>
					</div>
					
				<?php
				} else {
					?>
					<div>
						<p>No Data available for date range <b><?php echo date('j M Y', strtotime($fromDateString));?></b> to <b><?php echo date('j M Y', strtotime($toDateString));?></b> </p>
					</div>
					<?php
				}
				?>
			</div>
		</div><!-- /form container -->
    </div><!-- /wrapper -->
</body>
</html>