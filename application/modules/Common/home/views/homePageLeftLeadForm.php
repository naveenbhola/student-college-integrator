<div id="marketingLocationLayer_ajax">
<?php 
$formPostUrl = '/user/Userregistration/homepageUserRegistration/seccodehome';
?>
<h2 style='*height:0.01%'>Find the best college for yourself</h2>
<p style='*height:0.01%'>
<?php if(is_array($Validatelogged)): ?>
	We need a few details from you to suggest you relevant colleges.
<?php else: ?>
	We need a few details from you to suggest you relevant colleges &amp; create your free Shiksha account.
<?php endif; ?>
</p>
<div class="spacer15 clearFix"></div>
<div id ="text102">
	<ul class="find-inst-form">
		<!--li class="pref-row">Study Preference: 
        	<input id ="prefindia_786" name="abroad" type="radio" checked="checked" onclick="loadForm(this.id);" /> India &nbsp;
        	<input id ="prefabroad_786" name="abroad"  onclick="loadForm(this.id);" type="radio"/> Abroad
        </li-->
    </ul>
	<div id="mainForm" style="width:270px;">
		<?php echo Modules::run('registration/Forms/LDB',NULL,'findInstitute',array('registrationSource' => 'MARKETING_FORM','referrer' => 'http://shiksha.com#home')); ?>
	</div>
	<!--div id="mainFormAbroad" style="display:none; width:270px;">
	 <?php //echo Modules::run('registration/Forms/LDB','studyAbroad'); ?>
	</div-->
</div>                      
<script>
//if($('subm'))
  //  $('subm').disabled = true;
//if($('prefindia_786'))
	//$('prefindia_786').disabled = true;
//if($('prefabroad_786'))
	//$('prefabroad_786').disabled = true;	
function setDimHeight() {
document.getElementById('dim_bg').style.height = "1500px";
	}
function loadScript(url, callback){
    var script = document.createElement("script")
    script.type = "text/javascript";
    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
} 	  
    //loadAllScripts();
    //RenderInit();
</script>
