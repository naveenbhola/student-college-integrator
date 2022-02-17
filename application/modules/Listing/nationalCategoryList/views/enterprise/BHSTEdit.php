<?php

$js = array('common', 'ranking_cms_new');
	$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'common_new', 'bhst_ctp'),
        'js'    =>  $js,
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'metaKeywords'  =>'',
        'isOldTinyMceNotRequired' => 1
        );
	
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
<?php if($idForm || $wrongId) { ?>
    <?php if ($wrongId) {?> <p>Wrong Page Id <?php echo $pageId ?></p> <?php } ?>
    <div class="space--box align">
        <p><b>Category Page Id :</b> <input id = "ctpId" type = "text" class="cms--input_field"/>
        <button class="cms--orangebtn" onclick = "onSubmitClick('<?php echo site_url() ?>')" id = "submit-ctpId" >Submit</button>
    </div>
    <script >
    function onSubmitClick($siteUrl) {
        var ctpId = $j('#ctpId') . val();
        window . location . href = $siteUrl + 'nationalCategoryList/CategoryPageSeoEnterprise/bhstEdit/' + ctpId;
    }
    </script >
<?php } else {?>
     <div class="space--box">
        <p><b>URL :</b> <?php echo $ctpUrl ?></p>
        <p><b>Heading :</b> <?php echo $headingCTP ?></p>
         <div class="cms--tinymc">
        <textarea id="disclaimer" class="tinymce-textarea" style="width:660px"><?php echo htmlentities($bhstData); ?></textarea>
         </div>
        <button class="cms--orangebtn" onclick = "onBhstSubmitClick(<?php echo $pageId ?>,
        <?php echo $userid ?>)" id = "submit-bhst" >Submit</button>
     </div>
<?php } ?>
<?php
	$this->load->view('common/footerNew');
?>
<?php if(!$idForm && !$wrongId) { ?>
<script type="text/javascript">
    initiateTinYMCE('', false);
    function onBhstSubmitClick(pageId, userId){
        var confirmAction = window.confirm("Are you sure you want to save the changes?");
        if(!confirmAction){
            return false;
        }
        var bhstData = $j("#disclaimer").val();
        var data = {};
        data.bhstData = bhstData;
        data.pageId = pageId;
        data.userId = userId;
        $j.ajax({
            url: '/nationalCategoryList/CategoryPageSeoEnterprise/bhstSubmit',
            type: 'POST',
            data: data,
            success : function(response){
                response = JSON.parse(response);
                if(response.status === "error"){
                    alert(response.errorMessage);
                    window.location.href = "/" + "nationalCategoryList/CategoryPageSeoEnterprise/bhstEdit/" + pageId;
                }
                if(response.status === "success"){
                    alert("success");
                    window.location.href = "/" + "nationalCategoryList/CategoryPageSeoEnterprise/bhstEdit/" + pageId;
                }
            }
        });
    }
</script>
<?php } ?>