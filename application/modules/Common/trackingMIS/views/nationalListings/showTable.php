<?php
$class = "cropper-hidden";
if (count($resultsToShow) > 0) {
    $class = "";
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 <?php echo $class; ?>">
    <div class="x_panel">
        <div class="x_title">
            <?php if(false && $metric == "registration"){ ?>
                <h2>Registration Data
                    <?php if(!$pageName){ ?>
                        (Page - Traffic Source -Source Application wise)
                    <?php }else{?>
                        (Action - Widget -Source Application wise)
                    <?php }?>
                </h2>
            <?php }
            else if(($metric == "traffic")){
              ?>
                <h2>Traffic Data
                    (Page - Traffic Source -Source Application wise)
                </h2>

            <?php }
            else if(($metric == "engagement")){?>
                <h2 id = "engagementTableTitle">
                </h2>
            <?php }
            elseif($teamName == 'Study Abroad' && $metric == 'exam_upload')
            {
                ?>
                <h2>Exam Uploaded Docs
                    (Widget -Source Application wise)
                </h2>
                <?php
            }
            else{
                ?>
                <h2>Consolidated <?php echo ucfirst($pivotName); ?></h2>
            <?php }?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="clear">
                <table id="example" class="table table-striped responsive-utilities jambo_table dataTable">
                    <thead>
                    <?php
                    if(($metric == "RMC" || $metric == "response") && $pageName!='Study Abroad'){?>
                        <tr class="headings" role="row">
                            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                style="width: 41px;">
                                <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                     class="tableflat"
                                                                                                     style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                </div>
                            </th>

                            <?php
                                foreach ($dataTable as $key => $value) { ?>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0" aria-controls="example" rowspan="1" colspan="1"><?php echo $value['title'];?>
                                    </th>
                            <?php }
                            ?>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">Count
                            </th>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">%
                            </th>
                        </tr>
                    <?php }
                    else if($metric == "registration"){
                        if(!$pageName){ ?>
                            <tr class="headings" role="row">
                                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                    style="width: 41px;">
                                    <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                         class="tableflat"
                                                                                                         style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper"
                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Page Name
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">%
                                </th>
                            </tr>
                        <?php }else{?>
                            <tr class="headings" role="row">
                                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                    style="width: 41px;">
                                    <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                         class="tableflat"
                                                                                                         style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper"
                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Action
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Widget
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">%
                                </th>
                            </tr
                        <?php }?>
                    <?php }
                    else if($metric == "traffic"){

                        if($teamName == 'Domestic'){ ?>
                            <tr class="headings" role="row">
                                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                    style="width: 41px;">
                                    <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                         class="tableflat"
                                                                                                         style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper"
                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Page Name
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">%
                                </th>
                            </tr>
                            <?php
                        }else if($teamName == 'Study Abroad'){
                            if($pageName){
                                if($pageName == 'categoryPage'){
                                    $pivotName = 'Page URL';
                                }else{
                                    $tempName = str_replace('Page', '', $pageName);
                                    $pivotName = $tempName.' Id';
                                }
                                ?>
                                <tr class="headings" role="row">
                                    <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                        style="width: 41px;">
                                        <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                             class="tableflat"
                                                                                                             style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper"
                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                        </div>
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1"><?php echo $pivotName?>
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Source Application
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Count
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">%
                                    </th>
                                </tr>
                            <?php    }else{ ?>
                                <tr class="headings" role="row">
                                    <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                        style="width: 41px;">
                                        <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                             class="tableflat"
                                                                                                             style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper"
                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                        </div>
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Page Name
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Source Application
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Count
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">%
                                    </th>
                                </tr>
                            <?php    }

                        } ?>

                    <?php }
                    else if($metric == "engagement"){
                        if($teamName == 'Domestic'){ ?>
                            <tr class="headings" role="row">
                                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                    style="width: 41px;">
                                    <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                         class="tableflat"
                                                                                                         style="position: absolute; opacity: 0;">
                                        <ins class="iCheck-helper"
                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                    </div>
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Page Name
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>

                            </tr>
                        <?php }
                        else if($teamName == 'Study Abroad'){
                            if(!$pageName){?>
                                <tr class="headings" role="row">
                                    <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                        style="width: 41px;">
                                        <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                             class="tableflat"
                                                                                                             style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper"
                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                        </div>
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Page Name
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Traffic Source
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Source Application
                                    </th>
                                    <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                        aria-controls="example" rowspan="1" colspan="1">Count
                                    </th>

                                </tr>
                            <?php }?>
                        <?php }?>
                    <?php }
                    else if($teamName == 'Study Abroad' && $metric == 'exam_upload')
                    {
                        ?>
                        <tr class="headings" role="row">
                            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                style="width: 41px;">
                                <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                     class="tableflat"
                                                                                                     style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                </div>
                            </th>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">Widget
                            </th>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">Source Application
                            </th>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">Count
                            </th>
                            <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                aria-controls="example" rowspan="1" colspan="1">%
                            </th>
                        </tr>
                        <?php
                    }
                    else{?>

                        <tr class="headings" role="row">
                            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                                style="width: 41px;">
                                <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox"
                                                                                                     class="tableflat"
                                                                                                     style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper"
                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                </div>
                            </th>
                            <?php
                                if($metric == 'response' && $teamName == "Domestic" && $pageName != ""){ ?>
                                 <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Key Name
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Widget
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">%
                                </th>   
                            <?php    }else if($metric == 'response' && $teamName == "Shiksha"){ ?>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Page Name
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Source Application
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">Count
                                </th>
                                <th class="sorting breather_left_important" role="columnheader" tabindex="0"
                                    aria-controls="example" rowspan="1" colspan="1">%
                                </th>
                            <?php    }
                            ?>
                        </tr>
                    <?php }?>
                    </thead>
                    <tbody id="data-table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="loader_small_overlay" style="display: none;"><img
                src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>
<script type="text/javascript">
    <?php
        if($metric == 'response' && $site == "Domestic" && $pageName != ""){
            $prepareDataForCSV[0] = array('Key Name', 'Widget' ,'Source Application', 'Count', 'Percentage');
        }else{
            $prepareDataForCSV[0] = array('Page Name', 'Source Application', 'Count', 'Percentage');        
        }
    
    ?>

    var dataForCSV = JSON.parse('<?php echo json_encode($prepareDataForCSV);?>');
</script>
