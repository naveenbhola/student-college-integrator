<form method="post" action="/enterprise/ReviewHomepageTiles/reviewHomepageTiles" name="" id="addEditTile" onsubmit="" class="form-horizontal" enctype="multipart/form-data"><!--data-toggle="validator" role="form"-->
  <input type="hidden" name="cmsAction" id="cmsAction" value="addTile" />
  <input type="hidden" name="tileId" id="tileId" value="" />
  <input type="hidden" name="tileStream" id="tileStream" value=<?php echo $defaultSelectedStr; ?>>
  <input type="hidden" name="tileBaseCourse" id="tileBaseCourse" value=<?php echo $defaultSelectedBaseCourse; ?>>
  <input type="hidden" name="tileSubstream" id="tileSubstream" value=<?php echo $substream; ?>>
  <input type="hidden" name="tileEducationType" id="tileEducationType" value=<?php echo $educationType; ?>>
  <div class="form-group">
    <label for="tileTitle" class="col-sm-3 control-label">Title</label>
    <div class="col-sm-8">
      <input type="text" name="tileTitle" id="tileTitle" class="form-control" data-minlength="3"  maxlength="250" required="true" />
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tileDescription" class="col-sm-3 control-label">Description</label>
    <div class="col-sm-8">
      <textarea class="form-control" name="tileDescription" id="tileDescription" rows="3" data-minlength="3"  maxlength="1000"></textarea>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="mobilePhoto" class="col-sm-3 control-label">Mobile Photo</label>
    <div class="col-sm-8">
      <input type="file" name="mobileTileImage[]" id="mobilePhoto" />
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="desktopPhoto" class="col-sm-3 control-label">Desktop Photo</label>
    <div class="col-sm-8">
      <input type="file" name="desktopTileImage[]" id="desktopPhoto" required="true" />
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tilePlacement" class="col-sm-3 control-label">Tile placement</label>
    <div class="col-sm-8">
      <select class="form-control" name="tilePlacement" id="tilePlacement" required="true">
        <option value="">Choose placement</option>
        <option value="top">Top</option>
        <option value="bottom">Bottom</option>
      </select>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tilePosition" name="tilePosition" class="col-sm-3 control-label">Tile position</label>
    <div class="col-sm-8">
      <select class="form-control" id="tilePosition" name="tilePosition" required="true">
        <option value="">Choose position</option>
        <?php
        for($i=1; $i<=30; $i++)
        {
        ?>
          <option value="<?=$i?>"><?=$i?></option>
        <?php
        }
        ?>
      </select>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tileType1" class="col-sm-3 control-label">Tile type</label>
    <div class="col-sm-8">
      <label class="radio-inline" >
        <input type="radio" onclick="displayNumbering(this.value);" name="tileType" id="tileType1" value="courseList" required="true"> Course IDs
      </label>
      <label class="radio-inline">
        <input type="radio" onclick="displayNumbering(this.value);" name="tileType" id="tileType2" value="url" required="true"> URL
      </label>
      <div style="margin-top:8px;"></div>
      <input type="text" name="tileTypeText" id="tileTypeText" class="form-control" required="true" />
      <div class="help-block with-errors"></div>
    </div>        
  </div>

  <div class="form-group" id="courseNumbering" style="display:none;">
    <label for="displayNumbers" class="col-sm-3 control-label" style="padding-top:2px">Display Numbers</label>
    <div class="col-sm-8">
      <input type="checkbox" id="displayNumbers" name="displayNumbers" value="yes" <?php if($displayNumbers == 'yes'){echo "checked";}?> />
    </div>        
  </div>
  
  <div class="form-group">
    <label for="tileSEOURL" class="col-sm-3 control-label">SEO URL</label>
    <div class="col-sm-8">
      <input type="url" name="tileSeoUrl" id="tileSEOURL" class="form-control" />
      <div class="help-block with-errors"></div>
    </div>
    <div class="col-sm-8 col-sm-offset-3">
      <p class="text-muted" style="margin-top: 5px;">Sample URL : https://www.shiksha.com/< title >-crpage</p>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tileSEOTitle" class="col-sm-3 control-label">SEO Title</label>
    <div class="col-sm-8">
      <input type="text" name="tileSeoTitle" id="tileSEOTitle" class="form-control" />
      <div class="help-block with-errors"></div>
    </div>
  </div>
  
  <div class="form-group">
    <label for="tileSEODescription" class="col-sm-3 control-label">SEO Description</label>
    <div class="col-sm-8">
      <textarea class="form-control" name="tileSeoDescription" id="tileSEODescription" rows="3"></textarea>
      <div class="help-block with-errors"></div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-8 col-sm-offset-3">
      <button type="submit" class="btn btn-primary" id="addEditSubmitButton">Submit</button>
    </div>
  </div>
</form>

<script type="text/javascript">
var displayNumbers = JSON.parse('<?php echo $displayNumbers; ?>');
function displayNumbering(value){
  if(value=="courseList"){
            $j("#courseNumbering").show();
        }else{
        $j("#courseNumbering").hide();
      }
}
    </script>