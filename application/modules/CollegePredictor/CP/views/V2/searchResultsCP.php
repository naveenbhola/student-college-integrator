<?php 
$i=0;
// _p($examName);die;
// echo $count; 
// echo $start;
$brochureAction = 'RankPredictor';
$trackingPageKeyId=186;
$comparetrackingPageKeyId = 187;
$sortOrderForFilter = 'desc';
$maxRoundNumbers = count($roundData);
if($maxRoundNumbers == 1) {
    $tupleMainClass = 'tpl-sec tpl-wd100 tpl-tbl';
}
else {
    $tupleMainClass = 'tpl-sec';
}
?>
<input type="hidden" id="sortOrder" value="<?php echo $sortOrder;?>" />
<input type="hidden" id="total" value="<?php echo $total;?>" />
<input type="hidden" id="count" value="<?php echo $count;?>" />

<?php 
if($total > 0) { 
    ?>
    <div class="rsltDet-div">
        <div class="rslt-info">
	<ul> 
	    <?php if($examName=='jee-mains'){?>
                <li><p>For IITs, NITs and IIITs the data for General Category is updated as per JoSAA <?php echo $examYear;?> Counselling.</p></li>
            <?php } ?>
            <?php if($maxRoundNumbers > 1) { ?>
            <li><p>Rounds that you may crack are denoted by a <span class="tick-mrk"></span> The earliest cleared round is denoted by a <span class="thumb-mrkBlk"></span></p></li>
            <?php } ?>
	    <li><p>Colleges that come under home state quota are denoted by <label class="nblink">Home State</label></p></li>
	    <?php if($examName!='jee-mains'){?>
	    <li><p>Download application details, read reviews and compare colleges for courses you like.</p></li>
	    <?php } ?>
        </ul>
        </div>

    <div class="rslt-tpl" id ='mainresultDiv'>
        <?php if($maxRoundNumbers > 1) { ?>
        <div class="rslt-head">
            <p>Institute &amp; Branch (Course) </p>
            <table>
                <tbody>
                 <tr>
                    <th>Rounds</th>
                    <th>Closing Rank</th>
                    <th>Your Chances</th>
                 </tr>
                </tbody>
            </table>
        </div>
        <?php } 
        $this->load->view('CP/V2/collegePredictorInner');
        ?>
    </div>
</div>
<?php } else { ?>
<div class="noRslt-Dv">
    <p class="noRslt-txt"> No results found. Your inputs did not match any college cutoff <?php echo (!empty($invertLogic) && $invertLogic==1)?"score":"rank";?>.</p>
    <p> Suggestions: </p>
    <p>Try Relevent <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank";?> input by clicking "Modify Search" button.</p>  
</div>
<script>var zrpFlag=1;</script>
<?php } ?>
<script>
    var headingText = "<?=$text?>";
    var totalResults = '<?=$total;?>';
</script>
