<?php
$headerComponents = array(
                          'css'   => array(
                                            'header',
                                          'mainStyle',
                                          'raised_all',
                                          'footer',
                                          //'cal_style'
                                          ),
                          'js'    => array(
                                          'common',
                                          'user',
                                          'cityList'
                                           ));
$this->load->view('common/jsCssLoader',$headerComponents);
                                           ?>
<?php error_log("hurray5");?>
<div class="raised_pink" id="reqInfoContainersContainer"> 
						 <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						 <div id="reqInfoContainer" class="boxcontent_pink">
						 <?php
                                 if(!isset($validateuser[0]) || (isset($validateuser[0]) && $registerText['paid'] == "yes" )){
                                     $this->load->view("listing/requestInfo_microsite"); 
                                 }
							?>

<script>
function showuserLoginIFrame(redirUrl){
   window.location= "/listing/Listing/getSignInForm/"+redirUrl; 
}
function showLeadSubmitStatus(responseText)
{
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
        document.getElementById('reqInfoEmail_error').innerHTML = 'Email Already exists !!';
        document.getElementById('reqInfoEmail_error').parentNode.style.display = 'inline';
    }
    else{
        if(document.getElementById('reqinfoCaptacha')){
            reloadCaptcha('reqinfoCaptacha','seccode2');	
            if(trim(responseText) == 'code')
            {
                var securityCodeErrorPlace = 'securityCode1_error';
                document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
                document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
            }
            else
            {
                if(document.getElementById('queryContent')){
                    document.getElementById('queryContent').value="";
                }
                if(document.getElementById('securityCode1')){
                    document.getElementById('securityCode1').value="";
                }
                var divX = document.body.offsetWidth/2 - 150;
                var   divY = screen.height/2 - 200;
                var  h = document.documentElement.scrollTop;
                divY = divY + h;

                document.getElementById('reqInfoContainer').innerHTML = responseText;
                return true;
                 var Message = '';
                 if(document.getElementById('reqInfoPassword')){
                     if(document.getElementById('queryContent')){
                             Message = "Congratulations you have successfully registered on Shiksha.com & requested more information.";
                             showConfirmation(divX,divY,Message);
                     }
                     else{
                         Message = "Congratulations you have successfully registered on Shiksha.com";
                         showConfirmation(divX,divY,Message);
                     }
                 }else{
                     Message = "You have requested more information. Our representative will get back to you shortly.";
                     commonShowConfirmMessage(Message);
                 }
            }
        }else{
            if(document.getElementById('isPaid').value=='no'){
                document.getElementById('reqInfoContainersContainer').innerHTML ='';
            }
            else{
                document.getElementById('reqInfoContainer').innerHTML = responseText;
            }
        }
    }
}

</script>
						 </div>
						 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
					 <div class="lineSpace_10">&nbsp;</div>

