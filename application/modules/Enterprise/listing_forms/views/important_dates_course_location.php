						<div class="row" id="imp_date_layer">
                        <div class="row1"><b>Important Dates</b></div>
                        <div class="row2">
                            <div>
                                <div class="float_L" style="width:100px;margin-right:15px">Form Submission</div>
                                <div class="float_L" style="width:130px;margin-right:15px">Declaration of Results</div>
                                <div class="float_L" style="width:140px">Course Commencement</div>
                                <div style="line-height:1px;clear:both">&nbsp;</div>
                            </div>
                            <div style="height:35px">
                                <div class="float_L" style="width:110px;margin-right:10px">
                                    <input readonly profanity="true" name="date_form_submission" value="<?php if( isset($date_form_submission) && ($date_form_submission !== '0000-00-00 00:00:00') && (stripos($date_form_submission,'1970') === false)) { echo date("d-m-Y",strtotime($date_form_submission)) ; } ?>" type="text" class="inputBorderGray" id="date_form_submission1" type="text" caption = "form submission" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_form_submission" caption="form submission date" leftPosition="300" onClick="cal.select($('date_form_submission1'),'dfs','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647'" style="width:70px" /> <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="dfs" onClick="cal.select($('date_form_submission1'),'dfs','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647'" />
                                </div>
                                <div class="float_L" style="width:130px;margin-right:10px">
                                    <input  readonly profanity="true" name="date_result_declare" value="<?php if( isset($date_result_declaration) && ($date_result_declaration !== '0000-00-00 00:00:00') && (stripos($date_result_declaration,'1970') === false)) { echo date("d-m-Y",strtotime($date_result_declaration)) ; } ?>" type="text" class="inputBorderGray" id="date_result_declare1" type="text" caption = "results declaration" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_result_declare" caption="result declaration date" leftPosition="250" onClick="cal.select($('date_result_declare1'),'drc','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647'" style="width:70px" />
                                    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="drc" onClick="cal.select($('date_result_declare1'),'drc','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647';" />
                                </div>
                                <div class="float_L" style="width:140px">
                                    <input  readonly profanity="true" name="date_course_commence" value="<?php if( isset($date_course_comencement) && ($date_course_comencement !== '0000-00-00 00:00:00') && (stripos($date_course_comencement,'1970') === false)) { echo date("d-m-Y",strtotime($date_course_comencement)) ; } ?>" type="text" class="inputBorderGray" id="date_course_commence1" type="text" caption = "course commencement" validate="validateDateCourse"  maxlength="10" minlength="0" tip="date_course_commence" caption="course commence date" leftPosition="100" topposition = "50" onClick="cal.select($('date_course_commence1'),'dcc','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647'" style="width:70px" />
                                    <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="dcc" onClick="cal.select($('date_course_commence1'),'dcc','dd-MM-yyyy');$('calendarDiv').style.zIndex ='2147483647';" />
                                </div>
                                <div style="line-height:1px;clear:both">&nbsp;</div>
                            </div>
                            <div style="display:none"><div class="errorMsg" id="date_form_submission1_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="date_result_declare1_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="date_course_commence1_error"></div></div>
                            <div style="display:none"><div class="errorMsg" id="common_date1_error"></div></div>
                        </div>
                        <div class="row2"><input type="button" value="Save" onclick="updateImportantDate();$('imp_date_layer').style.display='none';"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="hideOverlayAnA();$('imp_date_layer').style.display='none';"/></div>
                    </div>
