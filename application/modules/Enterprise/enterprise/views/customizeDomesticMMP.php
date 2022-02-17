<title>Edit Courses (PageID=<?php echo $page_id;?>)</title>
<style>
.rowWDFcms {
  width: 988px!important;
}
.main {
  _margin-left: 150px!important;
}
.homeShik_footerBg {
   _position: relative;
   _left: 150px!important;
   _width: 1013px!important;
}
.nav{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;padding-left:0;margin-bottom:0;list-style:none}.nav-pills .nav-link{border-radius:.25rem}.nav-pills .nav-item.show .nav-link,.nav-pills .nav-link.active{color:#fff;cursor:default;background-color:#0275d8}.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#337ab7}.nav-pills>li{float:left}.nav>li{position:relative;display:block}.nav-link{padding:10px;text-decoration:none !important}nav-link:hover{text-decoration:none !important}.h3,h3{font-size:1.25rem !important}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}li.p-1, .field-title, .rght-sid{display: none;}.overview ul{padding: 7px !important; }

button, html [type=button], [type=reset], [type=submit] {
    -webkit-appearance: button;
}
.btn-primary {
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
}
.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
button, select {
    text-transform: none;
}
button, input {
    overflow: visible;
}
button, input, select, textarea {
    font: inherit;
    margin: 0;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.mt15{
  margin-top: 15px;
}
.ml15{
  margin-left: 15px;
}
.cr-srDiv input[type="text"]{
  width: 85% !important;
}

.text-muted {
    color: #636c72!important;
}
.form-text {
    display: block;
    margin-top: .25rem;
}
.mb10{
  margin-bottom: 10px;
}
.fr{float: right;}
.fl{float: left;}
.customField {font-size: 15px; width: 300px;}
.customField label{width: 100px !important; font-weight: 200;}
.customField input{margin:15px 10px 8px 35px;}
.box{
    padding: 10px;
    font-size: 14px;
}
</style>
<script type="text/javascript">
var original_page_type = '<?php echo $original_page_type?>';
var page_type = '<?php echo $page_type?>';
var count_courses = <?php if(!empty($count_courses)): echo $count_courses; else: echo 0; endif;?>;
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
</script>
<div class="main">
<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'userProfile', 'userRegistrationDesktop'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('enterprise/cmsTabs',$headerTabs);

?>

<div style="width:430px;padding-left:25px;font-size:13px;padding-bottom: 40px;">

    <ul class="nav nav-pills" style="margin: 19px 0px 15px; visibility: visible;">
         <?php if(empty($customization_fields)){ ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage">Study Abroad </a>
            </li> 
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Domestic</a>
            </li>
        <?php }else{ ?>

             <li class="nav-item">
              <a class="nav-link active" href="javascript:void(0);">Domestic</a>
            </li>
        <?php } ?>
    </ul>

        <div class="frm-bdy screen ih" id="firstScreen">
          <div class="form-text text-muted mb10">Set values of education interest fields.</div>
          <form class="prf-form" id="registrationForm_<?=$regFormId?>" regFormId="<?php echo $regFormId; ?>"  method="post" action="#">
            <input type='hidden' id='pageId' name='pageId' value='<?=$page_id?>' />
            <div id="customForm" class="edu">
                <a class="btn_orngT1" href="javascript:void(0);" onclick="shikshaUserProfileForm['Escaww'].submitForm('save'); ">
                Save
              </a>
            </div>

            <button type="button" class="mt15 btn btn-default fl" id="resetB">Reset</button>
            <button type="button" class="mt15 btn btn-primary fr" id="goToSecond" onclick="">Continue</button>
          </form>
          <p class="clr">
          </p>
    </div>

    <div id="secondScreen" class="frm-bdy ih screen">
        <form id="secondScreenForm" method="post" action="/customizedmmp/mmp/saveNationalFormCustomization">
        </form>
        <br/>
        <button type="button" class="mt15 btn btn-default fl" id="backB">Back</button>

        <button type="button" class="mt15 ml15 btn btn-primary fr" id="submitB">Save</button>
    </div>
</div>

<?php $this->load->view('common/footer');?>
<?php echo includeJSFiles("userProfileDesktop"); ?>
<?php echo includeJSFiles("userProfileAsync"); ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('customizedMMP'); ?>"></script>

<script type="text/javascript">
    customizedMMPForm.initMMPCustomizationDomestic('<?php echo $regFormId; ?>', '<?php echo $customization_fields; ?>');
</script>