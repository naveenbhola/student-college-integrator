<?php
$js = array('imageUpload','common','enterprise','ajax-api');
$jsFooter = array('footer');
$js = array_merge(array('lazyload'),$js);
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','articles','common_new','caenterprise', 'bootstrap'),
'js'	=> $js,
'jsFooter' => $jsFooter,
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>''
);
global $managementStreamMR;
global $engineeringtStreamMR;
global $mbaBaseCourse;
global $btechBaseCourse;

$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<div class="mar_full_10p">
  <?php $this->load->view('enterprise/cmsTabs'); ?>
  <h5 class="normal">College Reviews Homepage Tiles</h5>
  <hr/>
  <div class="col-sm-12">
      <div class="form-group">
        <label for="subCategoryName" class="col-sm-2 control-label">Select a course</label>
        <div class="col-sm-6">
          <select class="form-control" id="subCategoryName" required="true" onchange="changeSubcat()">
            <!--option value="">Choose sub-category</option-->
            <option value="23" stream="<?php echo $managementStreamMR; ?>" baseCourse="<?php echo $mbaBaseCourse; ?>" educationType="<?php echo $educationType; ?>" substream="<?php echo $substream; ?>" <?php if($defaultSelectedStr == $managementStreamMR) echo "selected"; ?>>Full Time MBA</option>
            <option value="56" stream="<?php echo $engineeringtStreamMR; ?>" baseCourse="<?php echo $btechBaseCourse; ?>" educationType="<?php echo $educationType; ?>" substream="<?php echo $substream; ?>" <?php if($defaultSelectedStr == $engineeringtStreamMR) echo "selected"; ?>>BE/B.Tech</option>
          </select>
        </div>
        <div class="col-sm-offset-2 col-sm-2">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="$j('#courseNumbering').hide();">Add a new Tile</button>
        </div>
      </div>
      <hr/><hr/>
      
      <!-- modal - start -->
      <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Add New Tile</h4>
            </div>
            <div class="modal-body">
              <?php $this->load->view('ReviewHomepageTiles/addEditTile'); ?>
            </div>
          </div>
        </div>
      </div>
      <!-- modal - end -->
      
      <!-- modal - start -->
      <div class="modal" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="viewModalLabel">Image Preview</h4>
            </div>
            <div class="modal-body">
              <div id="imagePreview" style="text-align: center;">
                <h5>Desktop Tile preview</h5>
                <div id="desktopImagePreview" style="margin: 10px auto; background-repeat:no-repeat; max-width:293px; min-height:120px; max-height: 125px; border: 1px solid #222; background: url('../images/col-revw2-2.png') no-repeat scroll 0 0 / cover  #FFFFFF"></div>
                <h5>Mobile Tile preview (Screen width:320px)</h5>
                <div class="mobileImagePreview" style="margin: 10px auto; background-repeat:no-repeat; max-width:320px; min-height:120px; max-height: 125px; border: 1px solid #222; background: url('../images/col-revw2-2.png') no-repeat scroll 0 0 / cover  #FFFFFF"></div>
                <h5>Mobile Tile preview (Screen width:480px)</h5>
                <div class="mobileImagePreview" style="margin: 10px auto; background-repeat:no-repeat; max-width:480px; min-height:120px; max-height: 125px; border: 1px solid #222; background: url('../images/col-revw2-2.png') no-repeat scroll 0 0 / cover  #FFFFFF"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- modal - end -->
      <?php $this->load->view('ReviewHomepageTiles/allTilesList'); ?>
  </div>
</div>
<?php $this->load->view('enterprise/footer');?>
<form action="reviewHomepageTiles" id="categoryForm" method="post">
<input type="hidden" name="stream" id="stream">
<input type="hidden" name="baseCourse" id="baseCourse">
<input type="hidden" name="educationType" id="educationType">
<input type="hidden" name="substream" id="substream">
</form>
<script>
function changeSubcat(){

  $stream = $j('#subCategoryName').find('option:selected').attr('stream');
  $baseCourse = $j('#subCategoryName').find('option:selected').attr('baseCourse'); 
  $educationType = $j('#subCategoryName').find('option:selected').attr('educationType'); 
  $substream = $j('#subCategoryName').find('option:selected').attr('substream'); 

  $("stream").value = $stream;
  $("baseCourse").value = $baseCourse;
  $("educationType").value = $educationType;
  $("substream").value = $substream;
  $("categoryForm").submit();
  
}
</script>