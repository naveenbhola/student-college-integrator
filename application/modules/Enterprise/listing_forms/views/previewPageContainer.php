<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>
<?php if($_REQUEST['transactionAlert'] == 1) { ?>
<br />
<div style='background: #FAF2F2; border:1px solid #e74c3c; padding:15px; font:bold 16px arial; margin-bottom: 20px; line-height: 150%;'>
    Publish could not be completed as one on the listings is already being processed in a transaction. Please try again after sometime (~10 seconds).
</div>
<?php } ?>

<?php
$this->load->view('listing_forms/listingsList');
?>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<div id="previewPage">
           <img src="/public/images/space.gif" width="115" height="10" />
</div>
<!-- 
<iframe id="previewFrame" style="width:965px" scrolling="yes" horizontalscrolling="no" verticalscrolling="yes" height="1800px" frameborder="0" src="">
 -->
</iframe>
<script>
    function fetchPreview(field_id,type,typeId){
            for(var i=1; i<$('totalListings').value; i++) {
                    var co_Id = document.getElementById('selectedListingId_'+i);
                    if(co_Id.id == field_id) {
                            co_Id.className = 'bgselected';
                        } else {
                            co_Id.className = 'bgunselected';
                    }
            }

            var url = '/enterprise/ShowForms/fetchPreviewPage/'+type+'/'+typeId;
/*            new Ajax.Request (url,{ method:'get', onBeforeAjax:beforeAjax(), onSuccess:function (response) {
                        try{
                                if(response.responseText.length > 0){
                                        hideDataLoader($('previewPage'));
                                        document.getElementById('previewPage').innerHTML = response.responseText;
                                }
                        } catch (e) {} 
            }});*/
            document.getElementById('previewFrame').src=url;

    }

function beforeAjax(){
    $('previewPage').innerHTML = "&nbsp;"; 
    showDataLoader($('previewPage'));
}
</script>
<?php
/*if(isset($defaultPreview)){
        echo '<script> fetchPreview($(\'selectedListingId_'.$defaultPreview['num'].'\').id,"'.$defaultPreview['type'].'","'.$defaultPreview['typeId'].'");</script>';
}
else{
    if(isset($listings[0])){
        echo '<script> fetchPreview($(\'selectedListingId_1\').id,"'.$listings[0]['type'].'","'.$listings[0]['typeId'].'");</script>';
    }
    else{
        if(isset($otherListings[0])){
            echo '<script> fetchPreview($(\'selectedListingId_1\').id,"'.$otherListings[0]['type'].'","'.$otherListings[0]['typeId'].'");</script>';
        }
    }
}*/
?>
