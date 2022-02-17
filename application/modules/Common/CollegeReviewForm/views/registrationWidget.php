        <?php if(!isset($validateuser[0]['displayname'])){ ?>
           <div class="blueBx mbot10px">
             <h2 class="registerText">Interested in <?=$pageFor?> Reviews? We'll keep you posted.</h2>
             <a class="_blueBtn" href="javascript:void(0);" onClick="registerUserNow();">REGISTER NOW</a>
           </div>

            <?php
            $pageNameSource = 'CollegeReviewHomepage';
            if(isset($pageNameRegister) && $pageNameRegister!=''){
              $pageNameSource = $pageNameRegister;
            } ?>
           <script>
            function registerUserNow(){
                data = {};
                data['referer'] = window.location;
                data['trackingKeyId']=192;
                data['source'] = '<?=$pageNameSource?>';
                data['trackingKeyId']= '<?php echo $regTrackingPageKeyId;?>';
		data['callback'] = function(data) {if (data['status'] != 'LayerClosed') { window.location.reload(true); }};
                registrationForm.showRegistrationForm(data);              
            }
            </script>
        <?php } ?>
