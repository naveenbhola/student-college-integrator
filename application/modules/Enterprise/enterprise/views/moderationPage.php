<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <?php if($usergroup == "cms"){ ?>
        <title>CMS Control Page</title>
        <?php } ?>
        <?php if($usergroup == "enterprise"){ ?>
        <title>Enterprise Control Page</title>
        <?php } ?>

        <?php
            $headerComponents = array(
                'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
                'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                'tabName'	=>	'',
                'taburl' => site_url('enterprise/Enterprise'),
                'metaKeywords'	=>''
            );
            $this->load->view('enterprise/headerCMS', $headerComponents);
            $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
        ?>
    </head>
    <body>

        <div id="dataLoaderPanel" style="position:absolute;display:none">
            <img src="/public/images/loader.gif"/>
        </div>
        <input type="hidden" id="startOffSet1" value="0"/>
        <input type="hidden" id="countOffset" value="10"/>

        <div style="float:left; width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div id="replacePage" name="replacePage" class="boxcontent_lgraynoBG">
                    <div class="row">

                        <div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px; display:none;" >
                            <div class="bld" style="margin-left:20px;padding:5px 0;" id="searchBar">
                                Institute Name <input type="text" id="instituteName" name="instituteName" style="width:200px" onkeypress="return enterKeySearch(event);"/>
                                Location <input type="text" id="location" style="width:100px" name="location" />
                                Client Email <input type="text" id="clientEmail" style="width:100px" name="clientEmail" />
                                Client Userid <input type="text" id="clientUserid" style="width:100px" name="clientUserid" />
                               
                               <select id="searchSubType" name="categoryId" style="width:200px;">
                                   <option value="">Select Category</option>
                                   <?php foreach ($searchCategories as $key=>$val) { ?>
                                   <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                   <?php } ?>
                               </select>

                                <input type="hidden" id="searchType" value="<?php echo $prodId; ?>" />
                                <input type="hidden" id="startlucene" />
                                <button class="btn-submit7" type="button" onClick="$('paginataionPlace2').innerHTML='';$('startOffSet1').value='0';searchLuceneCMS();" style="width:70px">
                                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
                                </button>
                            </div>
                            <div class="clear_L"></div>
                        </div>
                        <div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
                        <div class="lineSpace_10">&nbsp;</div>

                        <div class="mar_full_10p">
                            <div class="float_R" style="padding:5px">
                                <div class="pagingID" id="paginataionPlace2"></div>
                                <div class="pagingID" id="paginataionPlace3"></div>
                            </div>
                            <div class="clear_L"></div>
                        </div>
                        <div class="lineSpace_5">&nbsp;</div>


                        <form method="POST" id="frmSelectTransact" action="">
                            <div id="moderateList" class="row normaltxt_11p_blk">
                                <img src="/public/images/space.gif" width="115" height="100" />
                            </div>
                        </form>
                        

                    </div>
                    <div class="lineSpace_10">&nbsp;</div>
                </div> <!-- End div id=replacePage -->
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
    </body>
</html>
<script>
    function fetchModerationList(){
    var subsId = '<?php echo $subscriptionId; ?>';
    var data = "SubscriptionId="+subsId;
    new Ajax.Updater('moderateList','/enterprise/Enterprise/moderationList/',{onBeforeAjax:beforeAjax(),parameters:(data),onComplete:function(){
            hideDataLoader($('moderateList'));
            }}); return false;
}

function beforeAjax(){
    $('moderateList').innerHTML = "&nbsp;"; 
    showDataLoader($('moderateList'));
}

function selectAllKeyIds(){
    for(var i=1; i <= $('totalUserCount').value; i++) {
        var keyId = i;
        document.getElementById('userNo_'+keyId+'').checked = document.getElementById('selectAll').checked;
    }
}

function selectAllCheck(){
    if($('selectAll').checked == true){
        $('selectAll').checked = false;
    }
}

function checkIfInstiQueued(){
        for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
        {
                if(document.getElementById('userNo_'+i+'').checked == true){
                        var num = $(''+i+'_marker').value;
                        if($(num+'_inst')!=undefined){
                                if($('userNo_'+num).checked == false){
                                        document.getElementById('insti_unselect_error').innerHTML = 'Please choose to approve institute belonging to course named \"'+$(''+i+'_listTitle').value+'\"';
                                        document.getElementById('insti_unselect_error').style.display = 'inline';
                                        return false;
                                }
                        }
                }
        }
        document.getElementById('insti_unselect_error').innerHTML = "";
        document.getElementById('insti_unselect_error').style.display = 'none';
        return true;

    }

function validateFormModeration(){
        var checkBoxSelChk = validateCheckBox();
        var instSelChk = true;
        var action = $('approvalAction').value
        if(action=='APPROVE'){
                instSelChk = checkIfInstiQueued();
        }
        if(checkBoxSelChk && instSelChk){
                var con = confirm('This action will '+action+' all of the above selected listing(s).Are you sure to continue?');
                if(con){
                        $('frmSelectTransact').submit();
                }
    }
}

function validateCheckBox()
{
    var checkedFlag = false;
    for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
    {
        if(document.getElementById('userNo_'+i+'').checked == true){
            document.getElementById('checkbox_unselect_error').innerHTML = "";
            document.getElementById('checkbox_unselect_error').style.display = 'none';
            checkedFlag = true;
        }else{
            continue;
        }
    }
    if(!checkedFlag){
            document.getElementById('checkbox_unselect_error').innerHTML = "Please select a listing to continue !!";
            document.getElementById('checkbox_unselect_error').style.display = 'inline';
            return false;
        }else{
            return true;
    }
}

function showHideCancelComment(type,action){
    if(type=='show'){
            $('cancelDiv').style.display = 'inline';
        if(action=='disapprove'){
            $('disapproveButton').style.display='inline';
            $('deleteButton').style.display='none';
            return;
        }
        if(action=='delete'){
            $('disapproveButton').style.display='none';
            $('deleteButton').style.display='inline';
            return;
        }
    }else if(type=='hide'){
        $('CancelComments').value = '';
        $('cancelDiv').style.display = 'none';
        $('checkbox_unselect_error').innerHTML='';
        return;
    }
}
    fetchModerationList();
</script>
