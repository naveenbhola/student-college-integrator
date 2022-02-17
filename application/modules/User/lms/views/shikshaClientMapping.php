<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
    foreach ($headerContentaarray as $content) {
            echo $content;
    }
}

?>

<script src="/public/js/<?php echo getJSWithVersion("porting"); ?>"></script>
<div id="lms-port-wrapper">
<form id="uploadShikshaClientMap" accept-charset="utf-8" method="post" enctype="multipart/form-data" action="/lms/Porting/uploadShikshaClientMap" >

    <div class="page-title">
        <h2>Upload Functionality</h2>
    </div>

    <div id='lms-port-content'>
        <div class="form-section">
            <style type="text/css">
                .inline-flex{
                    display: inline-flex;
                    justify-content: flex-start;
                    align-items: center;
                }
                .form-section ul li.inline-flex{
                    float: none;
                }
                .form-section{
                    position: relative;
                }
                .dowload-msg{
                    position: absolute;
                    right: 10px;
                    top: 0px;
                }
            </style>
                <input type="text" name="downloadCSVFlag" id ="downloadCSVFlag" value = "0" style="display: none" >
                <a class="dowload-msg" onclick="setDownloadCSVFlag();">Download template for mapping</a>
            <ul>
            <li class="inline-flex">
                <span><strong>Upload File here : </strong>&nbsp;&nbsp;</span>
                <input type="file" name="fileToUpload[]" id="fileToUpload" required >
                <input type="button" value="Submit" class="orange-button" onClick="getDataFromCSV();" />
            </li>
            <li class="inline-flex">
                 <div id='error'>
                     <?php 
                        if (!empty($error)){
                            ?>
                            <div>
                                <h2>&nbsp;&nbsp;&nbsp;&nbsp;Upload Result :&nbsp;&nbsp;</h2>
                               <?php 
                                    echo $error; 
                               ?> 
                            </div>
                            <?php
                        }
                     ?>
                 </div>
            </li>
            </ul>

        </div>
    </div>


</form>
</div>
<?php $this->load->view('common/footer');?>

<style type="text/css">
    .caln-mid-txt{ vertical-align: middle;line-height:22px;}
    .caln-mid-btn{margin:10px 0 0 235px;}

</style>
