<!doctype html>
<html>
<head>
<meta name="viewport" content="device-width, initial-scale=1.0">
<style media="all">
body{margin: 0px;font-family: Arail,sans-serif;}
.pdf--cover {display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  position: absolute;top: 0;bottom: 0px;left: 0;height: 760px;align-items: center;right: 0px;justify-content: center;}
.pdf--cover_data {display: block; position: absolute; left: 0px; right: 0px; margin 0 auto;text-align: center;min-height: 200px;-webkit-transform: translate(0%, -70%); top: 50%;}
.pdf--cover h1 {font-size: 28px;line-height: 34px;margin: 30px auto 10px;width: 500px;text-align: center;}
.pdf--cover_loc {margin: 0px;font-size: 16px;color: #000;line-height: 22px;}
.logo_block{position: absolute;left: 0px;right: 0px;text-align: center;bottom: 20px;height: 100px;text-align: center;}
.logo_block img{width: 160px;margin: 0 auto;display: block;}
.logo_block p{font-size: 16px;line-height: 24px;color: #000;}
.pdf-top-texture{position: absolute;top: 0px;left: 0px;}
.pdf-btm-texture{position: absolute;right: 0px;bottom: -10px; height: 100px;}
.pdf--cover_loc img { position: relative; top: 3px;}
.pdf_conduct {font-size: 16px;color: #666;margin: 0;font-weight: 400;line-height: 18px;}
.pdf_conduc_txt {font-size: 16px;margin: 4px 0px 10px;line-height: 21px;}
.cndct-sec{margin-top: 44px;}
.inline-block{display: inline-block;}


/*    .pdf--cover_data {display: inline-flex;align-items: center;flex-direction: column;}
    .pdf--cover h1 {font-size: 32px;line-height: 38px;margin: 30px 0px 10px;width: 400px;text-align: center;}
    .pdf--cover_loc {margin: 0px;font-size: 18px;color: #000;line-height: 24px;}
    .logo_block{position: absolute;left: 0px;right: 0px;text-align: center;bottom: 30px;}
    .logo_block img{width: 160px;}
    .logo_block p{font-size: 16px;line-height: 24px;color: #000;}
    .pdf-top-texture, .pdf-btm-texture{position: absolute;top: 0px;left: 0px;}
    .pdf-btm-texture{right: 0px;left: auto;top: auto;bottom: 0px;}*/
</style>
</head>
<body contenteditable="false">

<div class="pdf--cover">
  <div class="pdf-top-texture">
    <img src="<?=SHIKSHA_HOME;?>/public/images/texture1.png" alt="shiksha"/>
  </div>
  <div class="pdf--cover_data">
      <?php if(!empty($logoUrl)) { ?>
        <div class="pdf--cover_logo">
           <img src="<?=$logoUrl?>"/>
       </div>
      <?php } 
      if (!empty($fullName)) { ?>
        <h1><?=$fullName?> <span class="inline-block">
        <?php 
        if ($name != $fullName) {
          echo "(".$name.") ".$year;
        }
        else{
          echo " ".$year;
        }
        ?></span></h1>
      <?php }else { ?>
      <h1><?=$name?></h1>

      <?php }
      if (!empty($location)) { ?>
      <p class="pdf--cover_loc">
        <img src="<?=SHIKSHA_HOME;?>/public/images/iconfinder-icon.svg" width="20px" height="20px">
        <?=$location?>
      </p>
      <?php } 
      if(!empty($condunctedBy)){ ?>
        <div class="cndct-sec">
          <h2 class="pdf_conduct"> Conducted by</h2>

          <p class="pdf_conduc_txt"><?=$condunctedBy?></p>
        </div>
      <?php } ?>

 </div>
  <div class="logo_block">
     <p>An exclusive Guide by</p>
      <img src="<?=SHIKSHA_HOME;?>/public/images/nshik_ShikshaLogo1.gif" alt="shiksha"/>
 </div>
   <div class="pdf-btm-texture">
     <img src="<?=SHIKSHA_HOME;?>/public/images/texture2.png" alt="shiksha"/>
   </div>
</div>
</body>
</html>