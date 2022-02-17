<?php
foreach ($featuredCollegesData as $index => $val) {
    $countLoop = 0;
    foreach ($val as $key => $value) {
        $countLoop ++;
        if($index == 'paid'){
            $divId = "paid_$countLoop";
        }
        else{
            $divId = "free_$countLoop";
        }
        $bannerType[$index][] = $countLoop;
        $url = addingDomainNameToUrl(array('url' => $value['image_url'] , 'domainName' =>MEDIA_SERVER));
?>
<li id="<?php echo $divId; ?>">
    <div class = 'sliderDiv'>
        <a class="gafc" lang="en" href="<?php echo $value['target_url']; ?>">
            <span>
                <img title="<?php echo $value['collegeName']; ?>" alt="<?php echo $value['collegeName']; ?>" data-original="<?php echo $url;?>" class="lazy dspyinl" src="<?php echo $url;?>">
            </span>
        </a>
    </div>
</li>
<?php }}
//This is used to save check in js.

//This line of code is never executed. But just a safe check for future.
if(empty($bannerType['paid'])){
    $bannerType['paid'] = '';
}
// This is used when all are paid banners.
if(empty($bannerType['free'])){
    $bannerType['free'] = '';
}
echo "<script> var randomize = '".json_encode($bannerType)."'; </script>";
?>
