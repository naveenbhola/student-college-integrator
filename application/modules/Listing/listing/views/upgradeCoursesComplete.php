<script>
   var formname = "<?php echo $formName; ?>";
</script>
<title>Upgrade/Downgrade Course</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','abroad_cms'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','upgradecourses'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('smart/SmartMis'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<div class="abroad-cms-head">
	   <div class="abroad-breadcrumb">
        <a class="abroad-breadcrumb-link">National Upgraded/Downgraded Courses</a> 
        </div>
        <h1 class="abroad-title">Data has been successfully Saved</h1>
</div>
<div style="margin-top: 250px;"> </div>
<script type="text/javascript">
    // Your application has indicated there's an error
    window.setTimeout(function(){

    // Move to a new location or you can do something else
        window.location.href = "/listing/NationalUpgradeCourses/index";

    }, 2000);

</script>




<?php $this->load->view('enterprise/footer'); ?>
