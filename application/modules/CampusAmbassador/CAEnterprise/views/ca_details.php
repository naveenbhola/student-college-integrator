<?php $this->load->view('CAEnterprise/subTabsCA');?>
<div id="caContainer" style="display:block;">
<?php $this->load->view('CAEnterprise/ca_details_innerpart');?>
</div>
<div id="popupBasicBack"></div>
<!--CA edit infomation layer-->
<style>
.rep-form-layer{width:460px; border:2px solid #333; float:left;padding:10px;z-index: 9999;background: #fff;}
.cross-icon2{font-size:30px; line-height:18px; color: #333 !important; text-decoration: none !important}
ul.rep-form{ font-size:12px; margin:20px 0 25px;}
ul.rep-form li{margin-bottom:15px;}
ul.rep-form li label{width:150px; text-align:right; display:inline-block; float: left;}
.errorCA{color: red;display: block;}
</style>
<form action="/CAEnterprise/CampusAmbassadorEnterprise/validateCAProfilePic" accept-charset="utf-8" method="post" enctype="multipart/form-data" id="caEditInfoLayer" name="caEditInfoLayer">
<div style="width:460px;display: none;position: absolute;left: 30%;" class="rep-form-layer" id="ca-inform-layer">
        <input type="hidden" id="ca_id" name="ca_id">  
        <div class="layer-title">
                            <a title="Close" class="flRt cross-icon2" href="javascript:void(0);" onclick="showCAInfoLayer('','close');">&times;</a>
                            <div class="title">Edit Campus Representative Information</div>
                    </div>
        <ul class="rep-form">
            <li>
            <label>Profile Picture :</label>
            <div style="margin-left:160px; overflow: hidden;">
                <input type="file" name="caProfilePic" id="caProfilePic"/>
                <span id="error_image" class="errorCA"></span>
            </div>
        </li>
        <li>
            <label>Institute Id :</label>
            <div style="margin-left:160px;">
                <input type="text" name="instituteCA" id="instituteCA" maxlength="8" autocomplete="off"/>
                <span id="error_inst" class="errorCA"></span>
            </div>
        </li>
         <li>
            <label>Course Id :</label>
            <div style="margin-left:160px;">
                <input type="text" name="courseCA" id="courseCA" maxlength="8" autocomplete="off"/>
                <span id="error_course" class="errorCA"></span>
            </div>    
        </li>
      </ul>
     <a href="javascript:void(0);" class="orange-button" id="caSubmitBtn">Done</a>
     <a href="javascript:void(0);" onclick="showCAInfoLayer('','close');" class="orange-button">Cancel</a>
     <span id="caSuccess" style="float: right;color: green; font-size:12px; margin-right: 145px;" class="errorCA"></span><br>
     <span id="imageSuccess" style="float: right;color: green; font-size:12px; margin-right: 145px;" class="errorCA"></span>
</div>
</form>
<!--end CA edit info layer-->