                <div class="article-header">
                    <div class="float_R">
                        <div id="articleDeleteMsg" class="bld fontSize_16p" style="display:none"></div>
                        <div id="articleOperations">
			    <?php if(isset($blogURL) && $blogURL!=''){ ?>
                            <button  class="btn-submit5 w3" type="button" onclick="window.open('<?=$blogURL?>');">
                                <div class="btn-submit5"><p class="btn-submit6">View Article</p></div>
                            </button>
			    <?php } ?>

                            <?php if(is_array($validateuser[0]) && ($blogUserId == $validateuser[0]['userid'] || $validateuser[0]['usergroup'] == 'cms')){ ?>
                            <button  class="btn-submit13 w3" type="Submit" onclick="location.replace('/enterprise/Enterprise/addBlogCMS/<?=$blogId.'/'.$blogStatus?>');">
                                <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Edit Article</p></div>
                            </button>
                            &nbsp;
                            <button  class="btn-submit5 w3" type="button" onclick="deleteArticle('<?=$blogId?>');">
                                <div class="btn-submit5"><p class="btn-submit6">Delete Article</p></div>
                            </button>
                            <?php } ?>
                        </div>
                    </div>
               </div>                
