<?php $this->load->view('header');?>
<style>
.pageNotFound{background-image:url(public/mobile/images/error-icn.gif); background-position:left 0px; background-repeat:no-repeat; padding-left:50px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:100%; height:200px}
.pageNotFound h3{font-size:24px; font-weight:normal; margin-bottom:10px; display:block}
.pageNotFound p{font-size:14px}
.pageNotFound p a{font-size:18px}
</style>
<div id="head-sep"></div>
    <div class="inst-box" style="border:0 none">    
        <div class="pageNotFound">
            <h3>Page Not Found</h3>
            <p>Sorry, the page that you are trying to access is not available or has been moved.<br /><br />
            Go back to <a href="<?php echo SHIKSHA_HOME; ?>">www.shiksha.com</a> </p>
        </div>
        <div class="clearFix"></div>
    </div>

<?php 
$data['beaconTrackData'] = array('pageIdentifier'=> '404Page');
$this->load->view('footer',$data);  ?>
