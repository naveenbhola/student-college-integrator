<div id="listing-admission" style="display:none" >                    
    <ul class="adm-padding">
        <?php foreach ($admissions as $stage => $value) {?>
        <li class="">
            <div class="">
                <h3 class="head-2"><?=$value['admission_name'] ?></h3>
                <p class="para-8"><?=nl2br(makeURLAsHyperlink(htmlentities($value['admission_description'])))?></p>
            </div>
        </li>
        <?php } ?>
    </ul>    
</div>