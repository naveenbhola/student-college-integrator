   <!--Amity Network-->
<?php
$data = array(
				'successurl'=>$thisUrl ,
				'successfunction'=>'',
				'id'=>'',
				'redirect'=> 1,
				
			    );	

$this->load->view('user/userlogin',$data);
$this->load->view('network/mailOverlay',$data);



$url = array(
				'successurl'=> '',
				'collegeId'=> $collegeNetworkCount[0]['instituteId'],
				'successfunction'=>'',
				'id'=>'add',
				'redirect'=> 0,
                'callbackAfterJoin'=>'confirmJoin(request.responseText);',
			    );	
$this->load->view('network/joinNetworkOverlay',$url);
?>

<script>
function confirmJoin(str){
    document.getElementById('joinNetworkOverlay').style.display = 'none';
    if(str != -1)
    {
        alert('Added to the network');
    }
    else
        alert("You are already a member of this institute Network.");

}
</script>
<div>
<?php 

for($collegeCounter = 0; $collegeCounter < count($collegeNetworkCount) ; $collegeCounter++) {
    if($collegeNetworkCount[$collegeCounter]['count'] <=0){
        $collegeNetworkCount[$collegeCounter]['count'] = 'No';
    }
if(!isset($userId)){
$joinNetworkLinkOnClick = "javascript:showuserOverlay(this,'join')";
}
else{

$joinNetworkLinkOnClick = "javascript:document.getElementById('collegeId').value =".$collegeNetworkCount[$collegeCounter]['instituteId']."; showNetworkOverlay('join',$userId);";
}
?>
<div>
   <div class="raised_sky"> 
       <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
        <div class="boxcontent_sky">
            <div class="h33 normaltxt_11p_blk bld bgcolor_div_sky">
               <div class="float_R" style="margin-right:5px">
                    <a href="/network/network/collegeNetwork/<?php echo $collegeNetworkCount[$collegeCounter]['instituteId']; ?>">
                        <?php echo $collegeNetworkCount[$collegeCounter]['count']; ?> User
                    </a>
                </div>
               <div class="float_L"><img src="/public/images/usernetwork.jpg" width="25" height="22" align="absmiddle" class="mar_left_10p" /></div>
               <div style="margin-left:45px; margin-right:35px">
                    <?php echo $collegeNetworkCount[$collegeCounter]['name']; ?> 
                </div>
               <div class="clear_L"></div>
            </div>

            <div class="lineSpace_10">&nbsp;</div>
            <div style="margin-left:40px">
                <div class="buttr2">
                <button class="btn-submit11 w21" value="" type="button" onclick="<?php echo $joinNetworkLinkOnClick; ?>">
                    <div class="btn-submit11"><p class="btn-submit12">Join Network</p></div>
                    </button>
                </div>
                <div class="clear_L"></div>
                <div class="lineSpace_10">&nbsp;</div>
            </div>
            <div class="lineSpace_1">&nbsp;</div>
       <?php 
            $thisCollegeUsers = 0;
            if((count($collegeNetwork) >= 0) &&  ($collegeNetworkCount[$collegeCounter]['count'] !='No')) {
                for($i = 0; $i < count($collegeNetwork) && $thisCollegeUsers < 2; $i++){
               if($collegeNetwork[$i]['collegeId'] != $collegeNetworkCount[$collegeCounter]['instituteId'] ){
                   continue;
               }
               else {
                    $thisCollegeUsers++;
               }
       ?>
            <div class="mar_left_5p mar_right_10p">														
            <div class="mar_left_5p normaltxt_11p_blk">
               <img src="<?php echo $collegeNetwork[$i]['avtarimageurl']; ?>" width="58" height="52" align="left" class="mar_right_5p" />
               <a href="<?php  echo '/getUserProfile/1/'.$collegeNetwork[$i]['userId'];?>">
                   <?php echo $collegeNetwork[$i]['displayname']; ?>
               </a>
               <a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none">
                   <?php echo $collegeNetwork[$i]['level']; ?>
               </a>
               <br/>
               <div class="lineSpace_5">&nbsp;</div>
               <span>
                   <?php echo $collegeNetwork[$i]['userStatus']; ?>                          </span>
            </div>
                <div class="clear_L"></div>
                <?php
                $toUserId = $collegeNetwork[$i]['userId'];
                $toDisplayName = $collegeNetwork[$i]['displayname'];
if(!isset($userId)){
$mailLinkOnClick = "javascript:showMailOverlay(0,'0',$toUserId,'$toDisplayName')";
$addToNetworkLinkOnClick = "javascript:sendRequest(0,$toUserId,'$toDisplayName')";

}
else{

$mailLinkOnClick = "javascript:showMailOverlay($userId,'',$toUserId,'$toDisplayName')";
$addToNetworkLinkOnClick = "javascript:sendRequest($userId,$toUserId,'$toDisplayName')";
}



                ?>
                <div class="mar_left_5p">
                   <img src="/public/images/greenDot.gif" width="17" height="19" />
                   <img src="/public/images/mail.gif" width="22" height="19" onclick = "<?php echo $mailLinkOnClick;?>"/>
                   <img src="/public/images/plus.gif" width="19" height="19" onclick="<?php echo $addToNetworkLinkOnClick; ?>" />
               </div>
                <div class="lineSpace_5">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <?php } ?> 
            
            <div class="txt_align_r">
                    <a href="/network/network/collegeNetwork/<?php echo $collegeNetworkCount[$collegeCounter]['instituteId']; ?>">View
            <?php echo $collegeNetworkCount[$collegeCounter]['count']; ?> User</a>&nbsp; &nbsp;</div>	
            <?php } ?>
        </div>
       <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
   </div>
</div>
    <div class="lineSpace_5" >&nbsp;</div>
<?php } ?>
</div>
         <!--End_Amity Network-->


