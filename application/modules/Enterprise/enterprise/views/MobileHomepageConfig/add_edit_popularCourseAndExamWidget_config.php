<div class="" style="border:1px solid black;margin: 10px 0; padding-left: 10px;">
  <?php
  if($iteration >= $limits['courses']['min'])
  {
  ?>
    <a href="javascript:void(0);" style="margin:10px; display: block; float:right;" onclick='this.parentNode.parentNode.removeChild(this.parentNode); numOfCourseBlocks--; $j("#addMorePopularCourseLink").show();'>Remove course</a>
  <?php
  }
  ?>
  <div>
    <table width="100%" border="0" cellpadding="1" cellspacing="5" style="margin:10px 0;">
      <!-- course drowpdown -->
      <tr>
        <td width="20%">
          Select course
        </td>
        <td>
          <?php
          //echo $iteration;
          //_p($configData['popularCoursesData'][$iteration]);
          $subcatId                              = $configData['popularCoursesData'][$iteration]['subcatId'];
          $allSubCategories                      = $configData['allSubCategories'];
          $catDropdown = '';
          foreach($allSubCategories as $subCat)
          {
            $selected = (($subCat['subCatId']==$subcatId)?'selected="selected"':'');
            $catDropdown .= '<option value="'.$subCat['catId'].'#'.$subCat['subCatId'].'" '.$selected.'>'.$subCat['subCatName'].'</option>';
          }
          ?>
          <select name="catSubCategory[]" class="validateConfig">
            <option value="">Select course</option>
            <?=$catDropdown?>
          </select>
        </td>
      </tr>
      <!-- display name textbox -->
      <tr>
        <td>
          Enter a display name
        </td>
        <td>
          <input type="text" class="validateConfig" value="<?=$configData['popularCoursesData'][$iteration]['name']?>" name="courseDisplayName[]" />
        </td>
      </tr>
      <!-- bgcolor textbox -->
      <tr>
        <td>
          Enter a color code
        </td>
        <td>
          <input type="text" value="<?=$configData['popularCoursesData'][$iteration]['bgcolor']?>" name="courseDisplayColor[]" placeholder="#000000" />
        </td>
      </tr>
      <tr>
        <td valign="top">Right side top links</td>
        <td>
          <?php $this->load->view('MobileHomepageConfig/rightSideTopLinks'); ?>
        </td>
      </tr>
      <tr>
        <td valign="top">Student tools</td>
        <td>
          <?php $this->load->view('MobileHomepageConfig/rightSideToolLinks'); ?>
        </td>
      </tr>
      <tr>
        <td valign="top">Popular Exams</td>
        <td>
          <?php $this->load->view('MobileHomepageConfig/popularExamWidgetLinks'); ?>
        </td>
      </tr>
    </table>
  </div>
</div>