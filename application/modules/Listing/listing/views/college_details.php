<div class="row">
		<div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">University/ Institute Details</span></div>
		<div class="lineSpace_5">&nbsp;</div>
		<div class="grayLine"></div>
</div>
	<div class="lineSpace_25">&nbsp;</div>
	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1 bld">&nbsp;</div>
			<div class="r2">All field marked with <span class="redcolor">*</span> are compulsory to fill in</div>
		</div>
	</div>
	<div class="lineSpace_25">&nbsp;</div>
<div class="row">
		<div class="row">
			<div>
				<div>
					<div class="r1 bld">University/ Institute Name:<span class="redcolor">*</span>&nbsp;</div>
					<div class="r2">
						<input type="text" name="c_institute_name" id="c_institute_name" validate="validateStr" required="true" maxlength="100" minlength="10" class="w62_per" tip="college_name" caption="University/Institute Name" />
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="c_institute_name_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_11">&nbsp;</div>

                <?php $this->load->view('enterprise/collegeLogoAndPanel'); ?>

		<div class="row">
			<div>
				<div>
					<div class="r1 bld">University Description:&nbsp;</div>
					<div class="r2">
					   <textarea name="c_institute_desc" id="c_institute_desc" validate="validateStr" minlength="0" maxlength="5000" class="w62_per mceEditor" style="height:130px;" caption="Description" ></textarea>
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="c_institute_desc_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_11">&nbsp;</div>

	<div id="location_main">
	<div id="location" >
		<?php $this->load->view('listing/country_city'); ?>
	</div>
	</div>


		<div class="row">
			<div>
				<div>
					<div class="r1 bld">Affiliated to:&nbsp;</div>
					<div class="r2">
						<input type="text" name="affiliated_to" id="affiliated_to" style="width:200px" tip="college_affil" validate="validateStr" maxlength="200" minlength="5" caption="Affilated to"/>
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="affiliated_to_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_11">&nbsp;</div>

		<div class="row">
			<div>
				<div>
					<div class="r1 bld">Year of Establishment:&nbsp;</div>
					<div class="r2">
						<input type="text" name="i_establish_year" id="i_establish_year" style="width:200px" validate="validateInteger" minlength="4" maxlength="4" tip="college_year" caption="Year Of Establishment" />
						<!--<select id="i_establish_year" name="i_establish_year">
							<option>Decade</option>
							<option></option>
							<option></option>
						</select>
						<select>
							<option>Year</option>
							<option></option>
							<option></option>
						</select>-->
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="i_establish_year_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_11">&nbsp;</div>

		<div class="row">
			<div>
				<div>
					<div class="r1 bld">No. of Students:&nbsp;</div>
					<div class="r2">
						<input type="text" name="i_no_of_students" id="i_no_of_students" style="width:200px" validate="validateInteger" minlength="0" maxlength="6" tip="college_no_stu" />
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="i_no_of_students_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_11">&nbsp;</div>

		<div class="row">
			<div>
				<div>
					<div class="r1 bld">No. of International Students:&nbsp;</div>
					<div class="r2">
						<input type="text" name="i_no_of_i_students" id="i_no_of_i_students" style="width:200px" validate="validateInteger" minlength="0" maxlength="6" tip="college_no_i_stu" />
					</div>
					<div class="clear_L"></div>
				</div>
				<div class="row errorPlace pd_top_1">
					<div class="r1">&nbsp;</div>
					<div class="r2 errorMsg" id="i_no_of_i_students_error"/></div>
					<div class="clear_L"></div>
				</div>
			</div>
		</div>
		<div class="lineSpace_20">&nbsp;</div>

<?php if ($usergroup == "cms"){ ?>
        <div class="row">
           <div>
              <div>
        	 <div class="r1 bld">Tags For Institute:&nbsp;</div>
        	 <div class="r2">
        	    <input type="text" name="i_tags" id="i_tags" validate="validateStr" maxlength="200" minlength="2" tip="institute_tags" class="w62_per" caption="Institute Tags"/>
        	 </div>
        	 <div class="clear_L"></div>
              </div>
              <div class="row errorPlace">
        	 <div class="r1">&nbsp;</div>
        	 <div class="r2 errorMsg" id="i_tags_error" ></div>
        	 <div class="clear_L"></div>
              </div>
           </div>
        </div>
        <div class="lineSpace_13">&nbsp;</div>

<?php } ?>
</div>
