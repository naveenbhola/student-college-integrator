<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Enterprise View Responses</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer','modal-message'),
        'js'    =>  array('common','enterprise','ajax-api','EREnterprise'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
<div style="width:100%">
    <div>
        <div style="margin:0 0px">
        <div style="width:100%">
            <div id="studentresolutionSet_800">
            <script>
                if(document.body.offsetWidth<900){
                    document.getElementById('studentresolutionSet_800').style.width='994px';
                }
            </script>
<?php
    $this->load->view('enterprise/examResponsesListView');
    $this->load->view('enterprise/searchExamResultViewContactDetails'); 
?>
        </div>
    </div>
</div>
<div style="margin-top:40px">
<?php
$this->load->view('enterprise/footer');
?>
