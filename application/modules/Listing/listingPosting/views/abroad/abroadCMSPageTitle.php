<?php
/*
 *Example Input : 
 1. $breadCrumb = array(array("text" => "", "url" => ""),
                    array("text" => "", "url" => ""));
 
 2. $pageTitle = "Add City";
 
 3. $lastUpdatedInfo = array("title"    => "Last excel uploaded",
                             "date"     => "20/02/2013",
                             "username" => "romil goel");
*/

$lastUpdatedInfoTitle    = empty($lastUpdatedInfo["title"]) ? "Last updated" : $lastUpdatedInfo["title"];
$lastUpdatedInfoUsername = empty($lastUpdatedInfo["username"]) ? "" : " by ".$lastUpdatedInfo["username"];
?>

<div class="abroad-cms-head">
<div class="abroad-breadcrumb">
    <?php
     $html = array();
     $i = 0;
        foreach($breadCrumb as $key=>$value)
        {
            if(!empty($value['url']))
                $html[$i++] = "<a href=\"".$value['url']."\" class=\"abroad-breadcrumb-link\">".$value['text']."</a> ";
            else
                $html[$i++] = $value['text'];
        }
        
        $html = implode("<span>&rsaquo;</span>",$html);
        echo $html;
    ?>
</div>
<h1 class="abroad-title"><?=$pageTitle ?></h1>
<div class="last-uploaded-detail">
    <?php if($lastUpdatedInfo)
      { ?>
        <p><span><?=$lastUpdatedInfoTitle ?> </span><?=$lastUpdatedInfo["date"].$lastUpdatedInfoUsername ?>
    <?php
      }
      ?>
        <br />
        *Mandatory</p>
</div>

</div>
