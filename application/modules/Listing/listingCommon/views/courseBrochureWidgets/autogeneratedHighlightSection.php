<?php if(is_array($highlights) && count($highlights)>0) { ?>
       <div class="cmn-card highlights line-t mb2">
          <h2 class="f20 clor3 mb2 f-weight1">Highlights</h2>
          <ul class="flex-ul">
              <?php foreach ($highlights as $usp){ ?>
                      <li>
                      <p class="f12 clor3"><?=htmlentities($usp->getDescription());?></p>
                      </li>
              <?php } ?>
            </ul>
       </div>
<?php } ?>