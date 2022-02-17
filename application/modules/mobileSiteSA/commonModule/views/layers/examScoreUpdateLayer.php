<a id="examScoreUpdateLayerLink" href="#examScoreUpdateLayer" data-rel="dialog" data-transition="slide">&nbsp;</a>
<div id="examScoreUpdateLayer" data-role= "page" data-enhance="false">
	<div class="src-cont">
		<div class="src-head">
				<p class="src-title">Enter your Exam score</p>
				<a href="#examScoreUpdateLayer" id="exmBack" data-rel="back" class="exm-back">&nbsp;</a>
        </div>
        <div class="src-pCont">
        	<!-- First Layer -->
			<div class="wht-bg clearfix" id="examPopupLayer1">
				<div class="exam-choice">
				    <p class="scr-hd">Have you given any study abroad exam?</p>
				    <div class="customInputs">
				       <div class="rd-dv">
				           <input type="radio" id="popup_yes" name="examStatus" class="examStatus" value="yes" />
				            <label for="popup_yes">
				                <span class="sprite"></span>
				                <strong>Yes</strong>
				            </label>
				        </div>
				        <div class="rd-dv">
				           <input type="radio" id="popup_no" name="examStatus" class="examStatus" value="no" />
				            <label for="popup_no">
				                <span class="sprite"></span>
				                <strong>No</strong>
				            </label>
				        </div>
				        <div class="rd-dv">
				           <input type="radio" id="popup_booked" name="examStatus" class="examStatus" value="booked" />
				            <label for="popup_booked">
				                <span class="sprite"></span>
				                <strong>Booked exam date</strong>
				            </label>
				        </div>
				    </div>
				    <div class="errMsg" style="display:none;">Please select whether you have given any exam</div>
				</div>
				<div class="btn-fxDiv">
				    <a href="javascript:void(0);" id="examStatusContinue" class="sbmt">Submit</a>
				    <a href="javascript:void(0);" class="up-ltr update_later">I will update later</a>
				</div>
			</div>
			<!-- Second Layer -->
			<div class="wht-bg clearfix" id="examPopupLayer2">
	            <div class="exam-choice">
	                <p class="scr-hd">Enter exam score to get relevant recommendations</p>
	                <p class="form-label">Select & Enter your exam score</p>
	                <div class="customInputs" id="examListDiv"></div>
	                <div class="errMsg" style="display:none;"></div>
	            </div>
	            <div class="btn-fxDiv">
	                <a href="javascript:void(0);" id="examStatusSubmit" class="sbmt">Submit</a>
	                <a href="javascript:void(0);" class="up-ltr update_later">I will update later</a>
	            </div>
            </div>
		</div>
	</div>
</div>