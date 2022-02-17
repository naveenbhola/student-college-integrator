<div class="collegeReviewTilesOverlayLoader" style="position: fixed; display: none; left: 0; top: 0; height: 100%; width: 100%; opacity: 0.2; background-color: #fff; z-index: 99998;"></div>
<div class="collegeReviewTilesOverlayLoader" style="position: fixed; display: none; left: 50%; top: 40%; z-index: 99999;"><img src="/public/images/loader.gif" /></div>
<div class="col-sm-12">
<h4 class="center-block">Top Tiles</h4>
<table class="table table-bordered">
  <thead>
  <tr class="active">
    <th>Sno</th>
    <th>Title</th>
    <th>Tile order</th>
    <th>Placement</th>
    <th>Action</th>
    <th>Preview</th>
  </tr>
  </thead>
  <tbody id="topTileTable">
  <?php
  foreach($formattedTilesData['top'] as $key=>$tile)
  {
  ?>
    <tr id="tile-<?=$tile['tileId']?>">
      <td><?=($key+1)?></td>
      <td class="col-md-3"><?=$tile['title']?></td>
      <td class="topTileOrder" tileId="<?=$tile['tileId']?>"><?=$tile['tileOrder']?></td>
      <td><?=$tile['tilePlacement']?></td>
      <td id="remove-<?=$tile['tileId']?>"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal" data-tile-id="<?=$tile['tileId']?>" onclick="showNumberingIfEdit('<?=$tile['tileId']?>');">Edit</a> / <a onclick="deleteCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Delete</a> / <?php if($tile['status'] == 'live'){ if($tile['subStatus'] == 'activated'){ ?><a onclick="deactivateCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Deactivate</a></td><?php }else{ ?> <a onclick="activateCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Activate</a></td> <?php } } ?>
      <td><a href="javascript:void(0);" data-toggle="modal" data-target="#previewModal" data-tile-id="<?=$tile['tileId']?>">Click here</a></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>
<h4 class="center-block">Bottom Tiles</h4>
<table class="table table-bordered">
  <thead>
  <tr class="active">
    <th>Sno</th>
    <th>Title</th>
    <th>Tile order</th>
    <th>Placement</th>
    <th>Action</th>
    <th>Preview</th>
  </tr>
  </thead>
  <tbody id="bottomTileTable">
  <?php
  foreach($formattedTilesData['bottom'] as $key=>$tile)
  {
  ?>
    <tr id="tile-<?=$tile['tileId']?>">
      <td><?=($key+1)?></td>
      <td class="col-md-3"><?=$tile['title']?></td>
      <td class="bottomTileOrder" tileId="<?=$tile['tileId']?>"><?=$tile['tileOrder']?></td>
      <td><?=$tile['tilePlacement']?></td>
      <td id="remove-<?=$tile['tileId']?>">
        <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal" data-tile-id="<?=$tile['tileId']?>" onclick="showNumberingIfEdit('<?=$tile['tileId']?>');">Edit</a> / <a onclick="deleteCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Delete</a> / <?php if($tile['status'] == 'live'){ if($tile['subStatus'] == 'activated'){ ?><a onclick="deactivateCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Deactivate</a></td><?php }else{ ?> <a onclick="activateCMSTileData('<?=$tile['tileId']?>')" href="javascript:void(0);">Activate</a></td> <?php } } ?>
      </td>
      <td><a href="javascript:void(0);" data-toggle="modal" data-target="#previewModal" data-tile-id="<?=$tile['tileId']?>">Click here</a></td>
    </tr>
  <?php
  }
  ?>
  </tbody>
</table>
</div>

<script type="text/javascript">
function showNumberingIfEdit(tileId){

setTimeout(function(){ 
    if($('tileType1').checked){ 
      $("courseNumbering").style.display="block";
      if(typeof(tileId) != 'undefined' && displayNumbers[tileId] == 'yes'){
        $('displayNumbers').checked= true;
      }
    }
 }, 300); 
}
</script>
