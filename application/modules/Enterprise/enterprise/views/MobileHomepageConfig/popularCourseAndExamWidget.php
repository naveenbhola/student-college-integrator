<?php
$js = array('imageUpload','common','enterprise','ajax-api');
$jsFooter = array();
$js = array_merge(array('footer','lazyload'),$js);
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','articles','common_new','caenterprise'),
'js'	=> $js,
'jsFooter' => $jsFooter,
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<div class="mar_full_10p">
  <?php $this->load->view('enterprise/cmsTabs'); ?>
  <h2>Mobile Homepage Popular Course Widget Config</h2>
  <script>
    var numOfRightLinkBlocks = [];
    var numOfRightToolBlocks = [];
    var numOfExamBlocks = [];
    
    var totalSumOfRightLinksAndTools = [];
  </script>
  <form method="post" action="/enterprise/MobileHomepageConfig/addEditPopularCourseAndExamWidgetConfig" name="mobile_homepage_config" onsubmit="return validateConfig();">
    <div id="courseBlockContainer">  
    <?php
    //_p($configData);
    $loop = $limits['courses']['min'];
    $courseBlockCount = $configData['courseCount'];
    if($courseBlockCount > $limits['courses']['min'] && $courseBlockCount <= $limits['courses']['max'])
    {
      $loop = $courseBlockCount;
    }
    for($i=0; $i<$loop; $i++)
    {
      $this->load->view('MobileHomepageConfig/add_edit_popularCourseAndExamWidget_config', array('iteration'=>$i));
    }
    ?>
    </div>
    <div style="margin:10px 0;">
      <?php
      if($courseBlockCount < $limits['courses']['max'])
      {
      ?>
        <!-- add more course link -->
        <a href="javascript:void(0);" style="margin-bottom:10px;display:block;" id="addMorePopularCourseLink" onclick="addPopularCourseBlock()">Add more course</a>
      <?php
      }
      ?>
      
      <!-- save, preview, publish buttons -->
      <input type="submit" name="configSubmit" value="Submit"/>
      <?php
      //if($showingLiveConfig != 'yes')
      //{
      ?>
        <input type="button" onclick="seeConfigPreview('<?=SHIKSHA_HOME.'/mcommon5/MobileSiteHome/renderHomePage?preview=1'?>');" value="Preview" />
      <?php
      //}
      //if($showingLiveConfig != 'yes' && $previewCookie == 'yes')
      //{
      ?>
        <input type="button" name="configPublish" value="Publish" onclick="publishNewPopularCourses();"/>
      <?php
      //}
      ?>
    </div>
  </form>
  <script>
    <?php
    $allSubCategories = $configData['allSubCategories'];
    $catDropdownOptions = '<select name="catSubCategory[]"><option value="">Select course</option>';
    foreach($allSubCategories as $subCat)
    {
      $catDropdownOptions .= '<option value="'.$subCat['catId'].'#'.$subCat['subCatId'].'" '.$selected.'>'.$subCat['subCatName'].'</option>';
    }
    $catDropdownOptions .= '</select>';
    
    $allExams = $configData['examList'];
    $examDropdownOptions = '<option value="">Select exam</option>';
    foreach($allExams as $exam)
    {
      $examDropdownOptions .= '<option value="'.$exam['name'].'">'.$exam['name'].'</option>';
    }
    //$examDropdownOptions .= '</select>';
    
    ?>
    //console.log(numOfRightLinkBlocks);
    var examDropdown = '<?=$examDropdownOptions?>';
    var catDropdown = '<?=$catDropdownOptions?>';
    var numOfCourseBlocks = '<?=$loop?>';
    <?php
    if($configSaved == 'yes')
    {
    ?>
      alert('Data successfully saved.');
      window.location = '/enterprise/MobileHomepageConfig/popularCourseAndExamWidget';
    <?php
    }
    ?>
  </script>
</div>
<?php $this->load->view('enterprise/footer');?>