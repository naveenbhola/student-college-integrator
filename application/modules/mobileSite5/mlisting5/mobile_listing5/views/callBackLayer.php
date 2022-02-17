<script>
	
	function makeCall(){
		
		var mobile = $('#changeNumber').val();
		var fromNumber = $('#callbackinstituteNo').val();
		if (mobile == "" || mobile == null) {
			alert("Please enter a number to get a call on different number");
			return;
		} else if (validateMobileInteger(mobile, "", 10, 10, true) !== true) {
		alert("Please enter a valid mobile number");
		return;
	}
		
		$.ajax({url:'/UserFlowRedirection/callUserPhone/'+mobile+'/'+fromNumber+"/"+"true",
			success:function(result){
				$('#call_back_popup').hide('slow');
				if (result == 'SUCCESS') {
					alert("Number changed successfully : You will soon get a call on your new number");
				} else {
					alert("Unable to call on your new number :(");
				}
				
				$('#callbackpopup').hide('slow');
				var url = document.URL;
				url = url.replace("callback=1",'');
				window.location.href = url;
		}
		
	 });

	}
</script>
<div id="wrapper"  data-enhance="false" style="position: relative; left: -5px;">
	<div class="popup-layer">
        	<div class="layer-head">       	
            	<p>Connecting your call to <?php echo $institute_name; ?></p>
		
                <a href="javascript:void(0);" class="close-box" onclick="$('#callbackpopup').hide('slow');var url = document.URL; url = url.replace('callback=1',''); window.location.href = url;">&times;</a>
		</div>
		
		<div class="layer-content">
		<p class="number-info" id="call_back_on_nuber">
				<?php if($call_back_message == 'SUCCESS'):?>
				Please wait you will shortly receive a call on your number <strong><?php echo $toNumber; ?></strong> from <?php echo $institute_name; ?>
			<?php else:?>
				        We are unable to call on your number <?php echo $toNumber; ?>, Please change your number and try again to receive a call on your new number.
				<?php endif;?>
		</p>
		<p class="contact-title">Get call on different number</p>
			<div class="contact-area">
			<i class="sprite phone-icon"></i>
			<div style="margin-left:23px;">
			<input type="hidden" id="callbackinstituteNo" value="<?php echo $fromNumber;?>"/>
			<input type="text" class="phone-txtfield" style="width:30%; margin-right:8px;" name="changeNumber" id="changeNumber" maxlength="10"/>
			</div>
			</div>
		<input type="button" value="Submit" onclick="makeCall()" class="button yellow small contact-submit-btn"/>
		</div>
        </div>
</div>

<style>
.popup-layer{background:#fff; border-radius:8px; padding:0; /*position:absolute;*/ width:100%;}
.layer-head{border-radius:8px 8px 0 0;background:#6db6e3; color:#fff; font-weight:bold; display:table; width:100%;}
.layer-head p{padding:10px 10px 8px;display:table-cell;}
.layer-content{padding:15px; color:#555;}
a.close-box{border-radius:0px 8px 0 0;background:#387ea9; padding:0 12px; color:#fff; font-size:32px; font-weight:normal; display:table-cell;width:20px;vertical-align:middle;}
.layer-details{margin-top:20px;}
.layer-details ul{margin:10px 0 0 15px; color:#aeaeae;}
.layer-details ul li{list-style:disc;}
.layer-details ul li p{margin:5px 0;}
/*.icon-layer-close{background-position:-432px -131px; width:30px; height:30px;}*/
.contact-area{margin:10px 0 0;}
.contact-info{color:#121212; font-size:14px; margin-bottom:10px; line-height:22px;}
.contact-title{color:#727272; font-size:14px; }
.phone-icon{background-position:-432px -130px; width:13px; height:23px; float:left; top:2px;}
.phone-txtfield{border:1px solid #dbdbdb; -moz-box-shadow:0 1px 3px #e0e0e0 inset; -webkit-box-shadow:0 1px 3px #e0e0e0 inset; box-shadow:0 1px 3px #e0e0e0 inset; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; padding:5px;-moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box;width:100%;}
.contact-submit-btn{width:100%; box-sizing:padding-box; color:#000; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; margin-top:20px; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; box-sizing:border-box; }
</style>
