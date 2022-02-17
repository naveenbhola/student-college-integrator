<h2 class="titl-main">Application process for this scholarship</h2>
<?php 
if(!empty($docsRequired)){
?>
<h3 class="sub-titl">Application Documents</h3>
<table class="col-table">
        <thead class="thead-default">
            <tr>
                <th width="50%">Type of documents</th>
                <th width="50%">Indicates if this type of document is required to be submitted</th>
            </tr>
        </thead>
        <tbody class="tbody-default">
            <?php 
            foreach ($docsRequired as $key => $value) {
                if($value != '')
                {
                ?>
                <tr>
                    <td><strong><?=$key?></strong></td>
                    <td>Required</td>
                </tr>    
                <?php 
                }
            }
            ?>
        </tbody>
</table>
<?php 
}
echo $scholarshipObj->getApplicationData()->getDocsDescription();
                          
    if ($scholarshipObj->externalApplicationRequired() == 'no') { 
        ?>
       <h3 class="sub-titl f14-fnt mb15">Scholarship Application</h3>
       <p class="mb15"> Students will be auto-considered for this scholarship when they apply for the courses on which this scholarship is applicable. No separate application is needed.</p>
<?php } 
        else if ($scholarshipObj->externalApplicationRequired() == 'yes') { 
        ?>
       <h3 class="sub-titl f14-fnt mb15">Scholarship Application</h3>
       <p class="mb15">This scholarship needs a separate external application.</p>
<?php }