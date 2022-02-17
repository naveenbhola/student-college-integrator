<hr style='margin-bottom: 15px'>
<div class='bld fontSize_18p OrgangeFont' style='margin-bottom:15px; margin-left: 25px; font-size: 20px;'>Scheduled Featured Guides</div>
<br>
<?php $i=1; ?>
<table cellspacing = '10'><tr><th>S.no.</th><th>Guide Name</th><th>Delete</th></tr>
<?php foreach($existing_guides as $k=>$v){ ?>
<tr><td><?php echo $i; ?></td>
<td><a href = '<?php echo MEDIA_SERVER.$v['guide_url']; ?>' ><?php echo $v['attachment_name']; ?></a>
</td><td><a onclick='deleteGuide(<?php echo $v['id']; ?>,<?php echo $v['stream_id']; ?>,<?php echo $v['base_course_id']; ?>);' href='javascript:void(0);' >Delete</a></td></tr>
<?php $i++; } ?>
</table>
