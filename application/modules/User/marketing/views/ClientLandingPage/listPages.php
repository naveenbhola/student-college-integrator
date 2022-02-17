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
?>

<style>
#tbl_mpcId th{background:#E5EECC; font-weight:bold; text-align:left}
#tbl_mpcId tr.alt-row{background:#f5f7ee}
</style>

<div style='padding:10px; min-height: 400px;'>
	
	<div style="float:left; margin-top: 3px;">
        <h1 style="font-size: 15px;">Client Landing Page List</h1>
    </div>
	<div class="new_mmp_button_container" id="new_mmp_button_container" style="float: right;">
		<button class="btn-submit5 " onclick="window.location = '/marketing/ClientLandingPage/createPage';">
			<div class="btn-submit5">
				<p class="btn-submit6">Create New Landing Page</p>
			</div>
		</button>
	</div>
	<div class="clearFix"></div>
	
	<?php if($msg == 'pageCreationSuccessful' || $msg == 'pageUpdationSuccessful') { ?>
		<div style='background: #E5F5DF; padding:10px; margin:10px 0; font-size: 13px; color:#148215'>
			<?php echo $msg == 'pageCreationSuccessful' ? 'New landing page has been successfully created.' : 'Landing page has been successfully updated.'; ?>
		</div>
	<?php } else if($msg == 'editPageDoesNotExist') { ?>
		<div style='background: #FFEBEB; padding:10px; margin:10px 0; font-size: 13px; color:red'>
			The page you are trying to edit does not exist.
		</div>
	<?php } ?>
	
    <table cellpadding="8" cellspacing="0" border="1" bordercolor="#D6DBDF" style="font-size:12px; border:1px solid #D6DBDF; border-collapse:collapse; margin-top: 10px;" width="100%" id="tbl_mpcId">
	<tbody><tr>
		<th width='350'>Name</th>
		<th width='150'>Created On</th>
		<th width='400'>Page URL</th>
		<th>Edit</th>
	</tr>
        
    <?php $counter = 1; foreach ($pages as $page):?>
        <tr <?php if ($counter%2 == 0) {echo 'class="alt-row"';} $counter++; ?>>
            <td><?php echo $page['name']; ?></td>
            <td><?php echo date('M j Y H:i:s',strtotime($page['createdOn'])); ?></td>
            <td><a href='/clientLandingPage-<?php echo $page['id']; ?>' target='_blank'><?php echo SHIKSHA_HOME; ?>/clientLandingPage-<?php echo $page['id']; ?></a></td>
			<td><a href='/marketing/ClientLandingPage/EditPage/<?php echo $page['id']; ?>'><img src='/public/images/edit-icn.gif' /></a></td>
        </tr>
    <?php endforeach; ?>    
    </tbody>
    </table>
    
    <br /><br /><br />
</div>    
    

<?php $this->load->view('common/footer');?>

