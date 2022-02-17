            <div class="formGreyBox">
                
                <div class="greyBoxCols">
                    <label>First Name:</label>
                    <input type="text" class="textboxLarge" />
                </div>
                <div class="greyBoxCols">
                <label>Middle Name:</label>
                <input type="text" class="textboxLarge" />
                </div>
                <div class="greyBoxRtCols">
                <label>Last Name:</label>
                <input type="text" class="textboxLarge" />
                </div>
            <div class="clearFix"></div>
         </div>
     
     	<div class="formChildWrapper">
     		<ul>
            	<li>
                	<label>Gender:</label>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" checked="checked" name="genders" /> <span>Male</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="genders" /> <span>Female</span></span>
                </li>
                
                <li>
                	<label>Date of Birth:</label>
                    <span>
                    	<select class="selectDay"><option>-Day-</option></select>
                        <select class="selectMonth"><option>-Month-</option></select>
                        <select class="selectYear"><option>-Year-</option></select>
                    </span>
                </li>
                
                <li>
                	<label>Email Address:</label>
                    <span>
                    	<input type="text" class="textboxLarge" />
                    </span>
                    
                    <label class="labelAuto">Alternate Email:</label>
                    <span>
                    	<input type="text" class="textboxLarge" />
                    </span>
                </li>
                
                <li>
                	<label>Course Applied For:</label>
                    <span>
                    	Course Name: <input type="text" class="textboxLarge" />
                    </span>
                    
                    <span class="labelSpacer">
                    	Code: <input type="text" class="textboxLarge" />
                    </span>
                </li>
                
                <li>
                	<label>Marital Status:</label>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" checked="checked" name="mstatus" /> <span>Single</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="mstatus" /> <span>Married</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="mstatus" /> <span>Separated</span> </span>
                </li>
                
                <li>
                	<label class="appsCateLabel">Application Category:</label>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" checked="checked" name="appCate" /> <span>SC</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="appCate" /> <span>ST</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="appCate" /> <span>OBC</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="appCate" /> <span>Defence</span> </span>
                    <span class="inputRadioSpacer"><input class="radio" type="radio" name="appCate" /> <span>Handicapped</span> </span>
                </li>
                
                <li>
                	<label>Nationality:</label>
                    <span>
                    	<select class="selectMedium"><option>Select</option></select>
					</span>
                </li>
                
                <li>
                	<label>Religion:</label>
                    <span>
                    	<input type="text" class="textboxMedium" />
					</span>
                </li>
                
                <li>
                	<label>Add photo:</label>
                    <span>
                    	<input type="file" size="30" onKeyDown="this.blur()" onContextMenu="return false;" />
					</span>
                    <div class="cloudBox">
                    	<span></span>
                        <div>Recent color photograph on white backgorund</div>
                    </div>
                </li>
            </ul>
     	</div>
