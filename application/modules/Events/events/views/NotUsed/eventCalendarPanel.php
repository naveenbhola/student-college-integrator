<style>
.raised_calender {background: transparent; } 
.raised_calender .b1, .raised_calender .b2, .raised_calender .b3, .raised_calender .b4, .raised_calender .b1b, .raised_calender .b2b, .raised_calender .b3b, .raised_calender .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_calender .b1, .raised_calender .b2, .raised_calender .b3, .raised_calender .b1b, .raised_calender .b2b, .raised_calender .b3b {height:1px;} 
.raised_calender .b2 {background:#5F99BE; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b3 {background:#B6D7EA; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b4 {background:#B6D7EA; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b4b {background:#DCF0FB; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b3b {background:#DCF0FB; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b2b {background:#DCF0FB; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 
.raised_calender .b1b {margin:0 5px; background:#5F99BE ;}   
.raised_calender .b1 {margin:0 5px; background:#ffffff;} 
.raised_calender .b2, .raised_calender .b2b {margin:0 3px; border-width:0 2px;} 
.raised_calender .b3, .raised_calender .b3b {margin:0 2px;} 
.raised_calender .b4, .raised_calender .b4b {height:2px; margin:0 1px;} 
.raised_calender .boxcontent_calender {display:block; background-color:#DCF0FB; background-position:top; background-repeat:repeat-x; border-left:1px solid #5F99BE; border-right:1px solid #5F99BE;} 

</style>
			<div class="raised_calender">
	            <b class="b1" ></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	            <div class="boxcontent_calender">
	            	<div class="row">	            		
		            	<!-- Month SPAN STARTS -->
			        	<div class="inline-l" style="margin:10px;">
			        	
		       				<div class="float_R" align="left"  >
		        				<img alt="Next Month" src="/public/images/rightArrow.gif"  border="0" onClick="javascript:MonthChanged(1);" style="cursor:pointer;"/> 
		       				</div>
			        	
				        	<div class="inline-l">
				        		<img alt="Previous Month" src="/public/images/leftArrow.gif" border="0" onClick="javascript:MonthChanged(-1);" style="cursor:pointer;"/>
				        	</div>
				        	
				        	<div class="inline-l" align="center" style="width:370px;">
				       			<div id="MonthPlace" class="inline Month" style="width:130px" >&nbsp;</div>
				       			<div id="YearPlace" class="inline Year" style="width:60px;margin-left:10px;">&nbsp;</div>
				       			<div class="inline" style="width:20px;">
				       				<img src="/public/images/dropDownArrow.gif" border="0" onClick="showYears(this);" style="cursor:pointer;"/>
				       			</div>
				       		</div>
				       		
			        	</div>
			        	<!-- Month SPAN ENDS -->
			        	<br clear="left"/>
	            	</div>
					<div class="row">
						<div id='calendarTable' class='inline calendarTable' style='text-align:center;'>
					    	<div style='padding:0px 5px 0px 10px;height:17px;background-color:#5F99BF;'>
				    			<div class='WeekRowHeader'>
					            <span class='WeekDayHeader'>Sun</span>
					            <span class='WeekDayHeader'>Mon</span>
					            <span class='WeekDayHeader'>Tue</span>
					            <span class='WeekDayHeader'>Wed</span>
					            <span class='WeekDayHeader'>Thu</span>
					            <span class='WeekDayHeader'>Fri</span>
					            <span class='WeekDayHeader'>Sat</span>
				        	</div>
		        		</div>
						<div style='padding:0px 5px 0px 10px;'>
						<?php
							for($i = 0; $i < 6; $i++) {
								$rowId = 'WeekTR_' . $i
						?>
							<div class="WeekRow" id="<?php echo $rowId; ?>">
								<?php
									for($j = 0; $j < 7; $j++) {
										$style = '';
										if($i == 6) {
											$style= 'style= "border : solid 1px #acacac;" ';
										}
										$cellId = 'DayTD_'. $i .'_'. $j ;
								?>
								<span onMouseOver="javascript:onDateCell(this);" onMouseOut="javascript:offDateCell(this);" class="WeekDay" id="<?php echo $cellId; ?>" <?php echo $style; ?>>&nbsp;</span>
								<?php
									}
								?><div class="clear_L"></div>
							</div>
							<br/>
						<?php
							}
						?>
					</div>
				</div>
				</div>
	            </div>
	            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b> 
	        </div>
<!--<div class="lineSpace_10">&nbsp;</div>-->
<br clear="left"/>
