<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
$hostName = 'http://'.$_SERVER['HTTP_HOST'];
$courses = array();
foreach($course_count as $course) {
	$courses[$course['page_id']][] = $course['count']; 
}
$index=0;
?>
<style>
#tbl_mpcId th{background:#E5EECC; font-weight:bold; text-align:left}
#tbl_mpcId tr.alt-row{background:#f5f7ee}
</style>

<div class="wrapperFxd">
<div class="mlr10">
<?php if(!empty($marketing_list) && is_array($marketing_list)):?>

<div style="float:left">
	<a href="/enterprise/MultipleMarketingPage/addNewMarketingPage"><b> [+] Add New Page</b></a>
</div>

<div style="float:right;"><?php echo $this->pagination->create_links(); ?></div>
<div class="clearFix spacer10"></div>
<table cellpadding="8" cellspacing="0" border="1" bordercolor="#D6DBDF" style="font-size:12px; border:1px solid #D6DBDF; border-collapse:collapse" width="100%" id="tbl_mpcId">
	<tr>
		<th>Name</strong></th>
		<th>Forms</strong></th>
		<th>Page Content</strong></th>
		<th>Attachment</strong></th>
		<th>Landing URL</strong></th>
	</tr>
    
	<?php $counter = 1; foreach ($marketing_list as $list):?>
	<tr <?php if ($counter%2 == 0) {echo 'class="alt-row"';} $counter++; ?>>
		<td>
        <a href="<?php echo $list['page_url']?>"><?php if(empty($list['page_name'])): echo 'None ';?> <?php else: echo wordwrap($list['page_name'],20,"<br/>\n", true)." ";?> <?php endif;?></a>
        </td>
		<td style="color:#b6b6b6">
        <?php if($courses[$list['page_id']][0]>0) { ?>
        <a href="/enterprise/MultipleMarketingPage/viewMMPageCourses/<?php echo $list['page_id']?>"><?php if($courses[$list['page_id']]['0']){echo $courses[$list['page_id']]['0']; if($courses[$list['page_id']][0]>1){echo" Courses";} else {echo " Course";} } else {echo "Add course";}?></a>
        <?php } else { ?>

        <a href="/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/<?php echo $list['page_id']?>"><?php if (!empty($formCustomizationDetails[$list['page_id']])) { echo 'Edit Customization'; } else { echo 'Add course'; } ?></a>
        <?php }?>
                <?php if($courses[$list['page_id']][0]>0) {?>
                &nbsp;|&nbsp; <a href="/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/<?php echo $list['page_id']?>">Customize Form</a></td>
                <?php }?>
        </td>
		<td><a href="/enterprise/MultipleMarketingPage/marketingPageContents/pageID/<?php echo $list['page_id']?>"><?php if(!empty($list['header_text']) || !empty($list['banner_zone_id']) ||!empty($list['banner_text'])|| !empty($list['form_heading'])): echo "Edit Content"; else: echo "Add Content"; endif;?></a>
		</td>
		
		<td>
			<?php if($list['attachment_url']) { ?>
				<a href="/enterprise/MultipleMarketingPage/marketingPageMailer/pageID/<?php echo $list['page_id']?>">Change</a>
			<?php } else { ?>
				<a href="/enterprise/MultipleMarketingPage/marketingPageMailer/pageID/<?php echo $list['page_id']?>">Upload</a>
			<?php } ?>
		</td>
		
		
		<td>
			<?php if(empty($list['destination_url'])):echo 'Default ';?> &nbsp; <a href="/enterprise/MultipleMarketingPage/changeDestinationURL/<?php echo $list['page_id'];?>">[ Edit ]</a>
			<?php else:?><a href="<?php echo $list['destination_url'];?>" target="_blank">Landing URL</a> &nbsp; <a href="#" onclick="document.getElementById('destination_url_form<?php echo $list['page_id']?>').submit()">[ Edit ]</a>
			<form method="post" id="destination_url_form<?php echo $list['page_id']?>" action="/enterprise/MultipleMarketingPage/changeDestinationURL/<?php echo $list['page_id']?>">
			<input type="hidden" name="destination_url" value="<?php echo htmlspecialchars($list['destination_url'])?>"></input>
			</form>
			<?php endif;?>
			<form method="post" id="page_name_form<?php echo $list['page_id']?>" action="/enterprise/MultipleMarketingPage/renameMarketingPage/<?php echo $list['page_id']?>">
			<input type="hidden" name="destination_url" value="<?php echo $list['page_url']?>"></input>
			<input type="hidden" name="page_name" value="<?php echo htmlspecialchars($list['page_name'])?>"></input>
			</form>	
		</td>
	</tr>
<?php $index++;endforeach;?>
</table>
    <?php else : echo "<b>Please add a marketing page, to add a page, click on below link</b>"?>
    <?php endif;?>
   <div class="clearFix spacer15"></div>
    
</div>
</div>
<div class="clearFix"></div>
<?php $this->load->view('common/footer');?>
