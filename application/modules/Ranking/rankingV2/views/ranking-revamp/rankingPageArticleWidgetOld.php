<?php
                                                    $count = 0;
                                                        foreach($articleWidgetsData as $val) {
                                                              if(!empty($val['articleTitle']) && $count < 3){
                                                                ?>
                                                    <li style="width:273px;">
                                                                      <a href="<?php echo $val['articleURL'];?>" title="<?=$val['articleTitle'];?>" style="font-size:14px;">
                                                                      <span>
                                                                      <?php
                                                                        echo sanitizeArticleTitle($val['articleTitle'], 52);
                                                                      ?>
                                                                      </span>
                                                                      </a>
                                                                </li>
                                                                <?php
                                                              }
                                                              $count++;
                                                        }
                                                        ?>