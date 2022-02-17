<?php
$headerData['partnerPage'] = 'shiksha';
$headerData['naukriAssoc'] = "false";
//$headerData['js'] = array('header', 'jquery', 'jcarousel', 'easySlider');
$headerData['js'] = array('header.min','jquery.and.plugins.animation');
//$headerData['js'] = array('header');
$headerData['css'] = array('marketing','animation','jcarousel');
$headerHtml = '<div style="width:959px;margin: 0 auto;" align="right"><span><img src="/public/images/naukrilogo_small.gif"/></div>' . $this->load->view('marketing/it_management_headerView', array('TEXT_HEADING' => $config_data_array['TEXT_HEADING']), true);
$headerData['headerHtml'] = $headerHtml;
$headerData['title'] = 'Shiksha Animation';
$this->load->view('common/animation_homepage_simple_1.php', $headerData);
$this->load->view('marketing/marketingSignInOverlay');
?>
<style>
    .cssSprite{background:url(/public/images/crossImg_14_12.gif) no-repeat;}
    .quesAnsBullets{background-image: none;}
</style>
<script>
    var isLogged = '<?php echo $logged; ?>';
    var messageObj;
    var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
    function loadScript(url, callback){
        var script = document.createElement("script")
        script.type = "text/javascript";
        if (script.readyState){  
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
    
    loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>', function(){
        //initialization code
    });
    loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user.min"); ?>', function(){
        //initialization code
    });
    loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api.min"); ?>', function(){
        //initialization code
        messageObj = new DHTML_modalMessage();
        messageObj.setShadowDivVisible(false);
        messageObj.setHardCodeHeight(0);
    });
    loadScript('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("itmarketingpage_1"); ?>', function(){
        //initialization code
    });
</script>
<div id="w_999">
    <!-- Start Top header -->
    <div class="T_header"><div><a href="https://www.shiksha.com" target="_blank" title="Shiksha.com"><img src="//<?php echo CSSURL; ?>/public/images/logo_1.gif" align="left" border="0" /></a></div></div>
    <!-- End Top header -->
    <div class="w_782">
        <div class="lft_Img"><img src="/public/images/lft_charcter.png" width="187" height="282" /></div>
        <div class="rht_Img"><img src="/public/images/rht_charcter.png" width="189" height="204" /></div>
        <div id="Container" >
            <!-- Start Left Coloumn -->
            <div id="leftCol" style="line-height:18px;">
                <div align="right" class="txt_11 pd_A10">Fields marked <span style="color:red">*</span> are compulsory</div>
                <div class="frm_container" style="font-size:11px;font-family: Tahoma, Geneva, sans-serif;color:#2A2A2A;">
                    <!--Start_Form_here-->
                    <?php $this->load->view('common/animation_form'); ?>
                </div>
            </div>
            <!-- End Left Coloumn -->
            <!-- Start Right Coloumn -->
            <div id="rightCol">
                <div style="overflow:hidden;">
                    <div id="slider">
                        <ul>				
                            <li><img src="/public/images/1.jpg" width="350" height="218" /></li>
                            <li><img src="/public/images/2.gif" width="350" height="218" /></li>
                            <li><img src="/public/images/3.jpg" width="350" height="218" /></li>
                            <li><img src="/public/images/4.gif" width="350" height="218" /></li>
                            <li><img src="/public/images/5.gif" width="350" height="218" /></li>			
                        </ul>
                    </div>
                </div>
                <div style="float:left;width:100%;">
                    <div class="box1 fl_lft"><!-- img --></div>
                    <div class="fl_lft headTxT">Choose Shiksha.com to</div>
                </div>
                <div class="box_container" style="float:left">
                    <div class="box1hnd fl_lft"><!-- img --></div>
                    <div class="pd_LT10">
                        <div class="tick fl_lft"><!-- bullet --></div>
                        <div class="fl_lft pd_T2"><strong>Find Top animation</strong> courses and institutes across various cities.</div>
                        <div class="ht_5 clr"><!-- height --></div>
                        <div class="tick fl_lft"><!-- bullet --></div>
                        <div class="fl_lft pd_T2"><strong>Get information</strong> on course structure, fees and faculties.</div>
                        <div class="ht_5 clr"><!-- height --></div>
                        <div class="tick fl_lft"><!-- bullet --></div>
                        <div class="fl_lft pd_T2"><strong>Read Alumni</strong> reviews on Campus life & Placements.</div>
                        <div class="ht_5 clr"><!-- height --></div>
                        <div class="tick fl_lft"><!-- bullet --></div>
                        <div class="fl_lft pd_T2"><strong>Get your queries</strong> resolved by our panel of Experts & Counselors.</div>
                        <div class="clr"><!-- height --></div>
                    </div>
                </div>
                <div class="mar_T10" style="float:left;width:100%;padding-top: 10px;">
                    <div class="box2 fl_lft"><!-- img --></div>
                    <div class="fl_lft headTxT">Top Animation Institutes</div>
                </div>
                <div class="box_container1" style="display:block; width:100%;clear:left;">
                    <div class="topInstList">
                        <ul id="mycarousel" class="jcarousel-skin-tango">
                            <li><img src="//<?php echo CSSURL; ?>/public/images/anitoons.gif" alt="anitoons" /></li>
                            <li><img src="//<?php echo CSSURL; ?>/public/images/frameboxx.gif" alt="frameboxx" /></li>
                            <li><img src="//<?php echo CSSURL; ?>/public/images/maac.gif" alt="maac" /></li>
                            <li><img src="//<?php echo CSSURL; ?>/public/images/arena.gif" alt="Arena multimedia" /></li>
                            <li><img src="//<?php echo CSSURL; ?>/public/images/tgc.gif" alt="TGC animation & Multimedia" /></li>
                            <li><img src="//<?php echo CSSURL; ?>/public/images/picasso.gif" alt="Picasso Animation college" /></li>
                        </ul>
                    </div>
                </div>
                <div class="mar_T10"  style="float:left">
                    <div class="box3 fl_lft"><!-- img --></div>
                    <div class="fl_lft headTxT">Get answer to your questions</div>
                </div>
                <div class="box_container"  style="float:left">
                    <div class="faqContBot">
                        <ul id="mycarouse2" class="jcarousel-skin-tango2">
                            <li>
 <h5>Q1. What are the career opportunities available after animation?</h5>

 <p>Animation is an interesting and secure career option to pursue given the increasing use of the technology in movies, media, advertising, television ... 
 </li>

 <li>
 <h5>Q2. What is CGI</h5>
 <p>Computer-generated imagery ( CGI) is the application of the field of computer graphics or, more specifically, 3D computer graphics to special effects in art, video ... </p>
 </li>

 <li>

 <h5>Q3. Which a better degree B.A Animation from MAAC or B.Sc in animation from PTU?</h5>
 <p>Both the courses are good and are offered through recognized universities. You can opt for either of the two depending on your preferences like... </p>
 </li>

 <li>
 <h5>Q4. Doing animation course from a university or college is better or doing it from the private institutes?</h5>
 <p>If you do your animation course from private institute you will be generally awarded a degree in a field like B.Sc, BA etc.... </p>

 </li>

 <li>
 <h5>Q5. Plz suggest me the job oriented course in animation?</h5>
 <p>Like all other courses, job prospects after a course in animation depend on the institute you do your course from. It also depends on ... </p>
 </li>

 <li>
 <h5>Q6. What is the qualification required for animation course?</h5>

 <p>There is no such stringent education qualifications required to pursue Animation as a career and you can do it even after your class 10th. However ... </p>
 </li>

 <li>
 <h5>Q7. What is the min. salary of 2D animation artist(Fresher) in India?</h5>
 <p>If you're talking of classical animation and of someone who has very good skills, he should start as a trainee around 8-12K ...</p>
 </li>

 <li>
 <h5>Q8. Do I need some particular background to do animation courses???</h5>
 <p>Generally any particular background is not required. However, if you have a good understanding of creative art it will be an advantage.... </p>
 </li>

                        </ul>
                    </div>
                </div>

            </div>
            <!-- End Right Coloumn -->
            <!-- Start Footer -->
            <div class="clr txt_11" id="footer" align="center">Copyright &copy; 2012 Shiksha.com. All rights reserved.</div>
        </div>
    </div>
</div>
<!-- End Footer -->
<script id="action_after_loading_ajax_html_form">
    /* Need to add possible DDs */
    fillCombo();
</script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("trackingCode"); ?>">
</script>

<script>
    var TRACKING_CUSTOM_VAR_MARKETING_FORM = "marketingpage";
    if(typeof(setCustomizedVariableForTheWidget) == "function") {
        if (window.addEventListener){
            window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
        } else if (window.attachEvent){
            document.attachEvent('onclick', setCustomizedVariableForTheWidget);
        }
    }
</script>			<!--End_Form_here-->
<script>
    // js var for google event tracking
    function removetip(){
    var other= document.getElementById('mobile').value;
    var objErr = document.getElementById('mobile_error');
    msg = validateMobileInteger(other,'mobile number',10,10,1);
    if(msg!==true)
    {
	objErr.innerHTML = msg;
	objErr.parentNode.style.display = 'inline';
	return false;
    }
    else
    {
	objErr.innerHTML = '';
	objErr.parentNode.style.display = 'none';
	return true;
    }
}
    var currentPageName = 'animation';
    var pageTracker = null;
</script>
<div id="marketingLocationLayer_ajax"></div>
<div id="marketingusersign_ajax"></div>
<div id="emptyDiv" style="display:none;"></div>
<script id="galleryDiv_script_validate">
    function RenderInit() {
        addOnBlurValidate(document.getElementById('frm1'));
    }
    window.onload = function () {
        try{
	    RenderInit();
            publishBanners();
	    ajax_loadContent('marketingLocationLayer_ajax','/marketing/Marketing/ajaxform_mba/mr_page');
        } catch (e) {
            //alert(e);
        }
    }
</script>
<?php $this->load->view('common/ga'); ?>
<script type="text/javascript">
    function trackEventByGA(eventAction,eventLabel) {
        if(typeof(pageTracker)!='undefined' && currentPageName!=null) {
            pageTracker._trackEvent(currentPageName, eventAction, eventLabel);
        }
        return true;
    }
</script>
<script>
    try{    var prevWindowOnload = window.onload;
        window.onload = function(){
            try{
                if(typeof(prevWindowOnload) == "function") {
                    prevWindowOnload.call();
                }
            }catch(e){
            }       		 jsb9TrackTime();    	}
        var prevWindowOnunload = window.onbeforeunload;
        window.onbeforeunload = function(){
            try{
                if(typeof(prevWindowOnunload) == "function") {
                    prevWindowOnunload.call();
                }
                jsb9onUnloadTracking();
            }catch(e){  }
        }

        function jsb9onUnloadTracking() {                                                  
            jsb9eraseCookie("jsb9Track");                                                  
            var date = new Date();                                  
            var presentTime = date.getTime();                                              
            var presentUrl = window.location.href;                                  
            jsb9createCookie("jsb9Track",presentTime+"|"+presentUrl,5);                    
        }      
        var date = new Date();
        jsb9TrackEndTime = date.getTime(); 
        function jsb9TrackTime() {

            var jsb9date = new Date();
            jsb9TrackFinalLoad=jsb9date.getTime(); 
            //console.debug(jsb9TrackVal);
            if(typeof(jsb9TrackVal) == "string") {
                var cookieArr = jsb9TrackVal.split('|');
                var prevTime = cookieArr[0];
                var refererUrl = cookieArr[1];
                var jsb9Iframe = document.createElement('div');
                jsb9Iframe.id = 'jsb9Div';
                var style = 'border:0;width:0;height:0;display:none';
                var jsb9ServerTime = 327.48794555664;
                var presentUrl = window.location.href;
                var customTrack = ""; 
                for(var i in jsb9recordTimes) {
                    customTrack += "|"+i+":"+jsb9recordTimes[i];
                }
                var data = presentUrl+"|"+refererUrl+"|"+prevTime+"|"+jsb9TrackStartTime+"|"+jsb9TrackEndTime+"|"+jsb9TrackFinalLoad+"|"+jsb9ServerTime+customTrack;
                jsb9Iframe.innerHTML = '<iframe border="0" height=0 widht=0 style="visibility: hidden" src="https://track.99acres.com/images/zero.gif?data='+data+'"></iframe>'
                document.body.appendChild(jsb9Iframe);
            }
        }
    }catch(e){ }
</script></div>
<!-- Begin comScore Tag -->
<script>
    var _comscore = _comscore || [];
    _comscore.push({ c1: "2", c2: "6035313" });
    (function() {
        var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
        s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
        el.parentNode.insertBefore(s, el);
    })();
</script>
<noscript>
<img src="https://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->
</body>
</html>
