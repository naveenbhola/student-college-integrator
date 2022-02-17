<div class="col-md-12 col-sm-12 col-xs-12 row"  style="background: #f7f7f7 none repeat scroll 0 0;margin: 0px !important;" >
  <?php
  //_p($requestTaskDetails['statusArray']);die;
  $this->load->view('splice/requestTaskDetails/commonTaskDetails');

  $this->load->view('splice/requestTaskDetails/viewTaskDetailHistroy');
  ?>
  
  <div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
  <br><br><br><br><br>
  </div>
  <?php if(count($requestTaskDetails['statusArray']) > 0){ ?>
    <div class="x_title">
      <div class="clearfix"></div>
    </div>
    <!-- Text Area -->
    <div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
      <div class="col-md-2 col-sm-2 col-xs-12" style="margin-right: 25px">
        <div class="col-md-12 col-sm-12 col-xs-12" >
          <b>Comment *</b>
          </div>
      </div>
      <div class="col-md-9 col-sm-9 col-xs-12 " style="margin-left: 0px !important; padding-left: 0px !important;">
      <div class="col-md-12 col-sm-12 col-xs-12" >
        <div class="x_panel">
          <div class="x_content">
            <div id="alerts"></div>
            <div class="btn-toolbar editor" data-role="editor-toolbar-commentTextEditor" data-target="#commentTextEditor">
              <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                <ul class="dropdown-menu">
                <li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li><li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li><li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li><li><a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li><li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li><li><a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li><li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li><li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li><li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li><li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li><li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li><li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li><li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li><li><a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li><li><a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li></ul>
              </div>

              <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li>
                    <a data-edit="fontSize 5">
                      <p style="font-size:17px">Huge</p>
                    </a>
                  </li>
                  <li>
                    <a data-edit="fontSize 3">
                      <p style="font-size:14px">Normal</p>
                    </a>
                  </li>
                  <li>
                    <a data-edit="fontSize 1">
                      <p style="font-size:11px">Small</p>
                    </a>
                  </li>
                </ul>
              </div>

              <div class="btn-group">
                <a class="btn" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                <a class="btn" data-edit="italic" title="" data-original-title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                <a class="btn" data-edit="strikethrough" title="" data-original-title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                <a class="btn" data-edit="underline" title="" data-original-title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
              </div>

              <div class="btn-group">
                <a class="btn" data-edit="insertunorderedlist" title="" data-original-title="Bullet list"><i class="fa fa-list-ul"></i></a>
                <a class="btn" data-edit="insertorderedlist" title="" data-original-title="Number list"><i class="fa fa-list-ol"></i></a>
                <a class="btn" data-edit="outdent" title="" data-original-title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                <a class="btn" data-edit="indent" title="" data-original-title="Indent (Tab)"><i class="fa fa-indent"></i></a>
              </div>

              <div class="btn-group">
                <a class="btn" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                <a class="btn" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                <a class="btn" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                <a class="btn" data-edit="justifyfull" title="" data-original-title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
              </div> 
            </div>

            <div id="commentTextEditor" name="commentTextEditor" class="editor-wrapper placeholderText" contenteditable="true"             validationType='html' required="true" caption=' Comment' maxLength='5000' onblur="formValidation.showErrorMessage($(this).attr('id'))"
            style="overflow:auto;height:200px !important" ></div>
            <div id="commentTextEditor_error" class="errorMsg" style="display: none"></div>
            <textarea name="descr_commentTextEditor" id="descr_commentTextEditor" style="display:none;"></textarea>
          </div>
        </div>
        </div>
      </div>
    </div>

    <!-- attachment -->
    <div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
      <div class="col-md-2 col-sm-2 col-xs-12" style="margin-right: 25px">
        <div class="col-md-12 col-sm-12 col-xs-12" >
        	<b>Attachment</b>
        </div>
      </div>
      <div class="col-md-9 col-sm-9 col-xs-12" style="margin-left: 0px !important; padding-left: 0px !important;">
        
          <input type="file" validationtype="file" caption="application process" id="commentAttachment" name="commentAttachment" class="col-md-6 col-sm-6 col-xs-12">                
     	  	<label class="control-label col-md-9 col-sm-9 col-xs-12"  style="text-align : left">Only pdf, ppt, pptx, doc, docx, xls, xlsx, txt, image,zip files are Allowed</label>
      </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading" >
    <br>
    </div>
    <!-- Status Drop Down and Submit -->
    <div class="col-md-12 col-sm-12 col-xs-12 taskFieldHeading">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-2 col-sm-2 col-xs-12" style="margin-right: 20px">
          <b>Select Action *</b>
        </div>
        <div class="dropdown col-md-2 col-sm-3 col-xs-12">
          <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="taskStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:150px !important"
          caption=" Course" required="true" validationType="select"
          >Select Action
              <span class="caret"></span>
          </button>
          <?php if(count($requestTaskDetails['statusArray']) > 6){
            $class = 'overflow_auto';
          } ?>
          <ul class="dropdown-menu <?php echo $class;?>" aria-labelledby="taskStatus">
            <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                    <a href="javascript:void(0)" title="Choose a popular course" >Select Action</a>
            </li>
            <?php foreach ($requestTaskDetails['statusArray'] as $key => $value) { ?>
                <li data-dropdown="<?php echo htmlentities($value); ?>">
                    <a href="javascript:void(0)" title="<?php echo htmlentities($value); ?>">
                        <?php echo htmlentities($key); ?>
                    </a>
                </li>
            <?php } ?>
          </ul>
      	</div>
      	<input type="hidden" name="taskStatus" value=0 id="hidden_taskStatus"/>
        <div class="dropdown col-md-2 col-sm-3 col-xs-12 cropper-hidden" >
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="userDiv"
                        data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true" >Select User<span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDiv">
                </ul>
                <input type="hidden" name="userDiv" value='0' id="hidden_userDiv" />
        </div>
        <div class="dropdown col-md-2 col-sm-3 col-xs-12 cropper-hidden">
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="changeType"
                        data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true" style="width: 160px !important;">select Change Type<span class="caret"></span>
                </button>              
                <ul class="dropdown-menu overflow_auto" aria-labelledby="changeType">
                  <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                    <a href="javascript:void(0)" title="Select Change Type" >Select Change Type</a>
                  </li>
                  <?php foreach ($requestTaskDetails['changeTypeForMailer'] as $key => $value) { ?>
                      <li data-dropdown="<?php echo htmlentities($key); ?>">
                          <a href="javascript:void(0)" title="<?php echo htmlentities($key); ?>">
                              <?php echo htmlentities($value); ?>
                          </a>
                      </li>
                  <?php } ?>
                </ul>
        </div>
        <input type="hidden" name="changeType" value='0' id="hidden_changeType" />
        <div class="dropdown col-md-2 col-sm-3 col-xs-12 cropper-hidden">
                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="requestedBy"
                        data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true" style="width: 165px !important;">Select Requested By<span class="caret"></span>
                </button>              
                <ul class="dropdown-menu " aria-labelledby="requestedBy">
                <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                    <a href="javascript:void(0)" title="Select Requested By" >Select Requested By</a>
                  </li>

                  <?php foreach ($requestTaskDetails['requestedByForMailer'] as  $value) { ?>
                      <li data-dropdown="<?php echo htmlentities($value); ?>">
                          <a href="javascript:void(0)" title="<?php echo htmlentities($value); ?>">
                              <?php echo htmlentities($value); ?>
                          </a>
                      </li>
                  <?php } ?>
                </ul>
        </div>
        <input type="hidden" name="requestedBy" value='0' id="hidden_requestedBy"/>
        <!-- Submit Button -->
        <div class="col-md-1 col-sm-3 col-xs-12 ">
          <button type="submit" id ="changeStatus" class="btn btn-success" onclick="taskStatus.validateAndsaveUpdatedStatus()">Submit</button>
        </div>
     </div>   
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12" >
    <br><br><br><br><br><br><br><br><br><br>
    </div>
  <?php }?>
</div>
<script>
  $(document).ready(function() {
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
          'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
          'Times New Roman', 'Verdana'
        ],
        fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function(idx, fontName) {
        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
      });
      $('a[title]').tooltip({
        container: 'body'
      });
      $('.dropdown-menu input').click(function() {
          return false;
        })
        .change(function() {
          $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
        })
        .keydown('esc', function() {
          this.value = '';
          $(this).change();
        });

      $('[data-role=magic-overlay]').each(function() {
        var overlay = $(this),
          target = $(overlay.data('target'));
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
    }

    function showErrorAlert(reason, detail) {
      var msg = '';
      if (reason === 'unsupported-file-type') {
        msg = "Unsupported format " + detail;
      } else {
        console.log("error uploading file", reason, detail);
      }
      $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
    }

    initToolbarBootstrapBindings();
    $('#commentTextEditor').wysiwyg({
      fileUploadError: showErrorAlert
    });
    $('#commentTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-commentTextEditor]'} );              
    window.prettyPrint;
    prettyPrint();
  });
</script>
