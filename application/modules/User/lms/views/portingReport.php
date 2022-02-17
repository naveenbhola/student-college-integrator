<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
    foreach ($headerContentaarray as $content) {
            echo $content;
    }
}

?>

<script src="/public/js/<?php echo getJSWithVersion("porting"); ?>"></script>
<div id="lms-port-wrapper">
<form id="portingReportForm" autocomplete="off" action="/lms/Porting/generatePortingReport" method="POST">

<div class="page-title">
    <h2>Admin Report</h2>
</div>

<div id='lms-port-content'>
    <div class="form-section">
        <ul>
        <li class="caln-mid-txt">
            <label><strong>Duration:</strong></label>
                      
        <div class="form-fields">
            <?php
            $from = $this->session->flashdata('from');
            ?>
            <div class="flLt caln-mid-txt">
                <label style="width:auto">From &nbsp; </label>
                <input type="text" style="width:75px; color:#888a89" placeholder="yyyy-mm-dd" value = "<?=$from?>" readonly="true" name="timerange_from" id="timerange_from">&nbsp;&nbsp; <img style="cursor:pointer;" class="caln-mid-txt" src="/public/images/calen-icn.gif" id="timerange_from_img" onclick="timerangeFrom();">
            </div>
            <?php
            $to = $this->session->flashdata('to');
            ?>
            <div class="flLt caln-mid-txt">
                <label style="width:auto;margin-left:15px;">To &nbsp;</label>
                <input type="text" style="width:75px; color:#888a89" placeholder="yyyy-mm-dd" value = "<?=$to?>" readonly="true" name="timerange_to" id="timerange_to">&nbsp;&nbsp; <img style="cursor:pointer;" class="caln-mid-txt" src="/public/images/calen-icn.gif" id="timerange_to_img" onclick="timerangeTo();">
            </div>
        
        </div>
        </li>
        </ul>

        <input type="button" value="Submit" class="orange-button caln-mid-btn" onClick="generatePortingReport();" />
    </div>
</div>
</form>
</div>
<?php $this->load->view('common/footer');?>

<style type="text/css">
    .caln-mid-txt{ vertical-align: middle;line-height:22px;}
    .caln-mid-btn{margin:10px 0 0 235px;}

</style>

<?php if($showAlert == '1'){ ?>
    <script> alert('No data found..!!');</script>
<?php } ?>