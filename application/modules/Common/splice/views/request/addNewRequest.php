
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Add New Request</h2>                    
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <br>
      <div id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
        <!-- Client Name -->
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Client Name *</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text" class="form-control" placeholder="Client Name" validationType='str' required="true" id='clientName' caption='Client Name' maxLength='100' onblur="formValidation.showErrorMessage($(this).attr('id'))">
            <div id="clientName_error" class="errorMsg" style="display: none"></div>
          </div>
        </div>

        <!-- Sales Order No -->
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">Sales Order No. *</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <input type="text" class="form-control" placeholder="Sales Order No." validationType='str' required="true" id='salesOrderNo' caption='Sales Order No.' maxLength='100' onblur="formValidation.showErrorMessage($(this).attr('id'))">
            <div id="salesOrderNo_error" class="errorMsg" style="display: none"></div>
          </div>          
        </div>
      <?php
      if($requestType == 'mailer') {  ?>
      <?php 
        $this->load->view('request/mailerRequest'); ?>
        <div class="form-group">
            <div class="control-label col-md-9 col-sm-9 col-xs-12"></div>
            <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
              <button type="submit" id ="addNewRequestForMailer" class="btn btn-success" onclick="formValidation.validateRequest($(this).attr('id'),'mailer')" >Submit Request</button>
            </div>
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
            $('#mailerTextEditor').wysiwyg({
              fileUploadError: showErrorAlert
            });
            $('#mailerTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-mailerTextEditor]'} );
            window.prettyPrint;
            prettyPrint();
          });
        </script>
      <?php }else{ ?>
          <!-- Campaign Live Date -->
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Campaign Live Date *</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" class="form-control has-feedback-left active" id="campaignLiveDate" placeholder="Campaign Live Date" aria-describedby="inputSuccess2Status" required="true" validationType='campaignDate' caption='Campaign Live Date' onblur="formValidation.showErrorMessage($(this).attr('id'))">
                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                <div id="campaignLiveDate_error" class="errorMsg" style="display: none"></div>
            </div>         
          </div>
        
          <?php
            $this->load->view('request/listingRequest');
            $this->load->view('request/bannerRequest');
            $this->load->view('request/shoshkeleRequest');
            $this->load->view('request/campaignActivationRequest');
            ?>
            <div class="form-group">
            <div class="control-label col-md-9 col-sm-9 col-xs-12"></div>
            <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
              <button type="submit" id ="addNewRequestForOther" class="btn btn-success" onclick="formValidation.validateRequest($(this).attr('id'),'other')">Submit Request</button>
            </div>
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
                $('#shoshkeleTextEditor').wysiwyg({
                  fileUploadError: showErrorAlert
                });
                $('#shoshkeleTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-shoshkeleTextEditor]'} );
                $('#campaignActivationTextEditor').wysiwyg({
                  fileUploadError: showErrorAlert
                });
                $('#campaignActivationTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-campaignActivationTextEditor]'} );
                $('#listingTextEditor').wysiwyg({
                  fileUploadError: showErrorAlert
                });
                $('#listingTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-listingTextEditor]'} );

                $('#bannerTextEditor').wysiwyg({
                  fileUploadError: showErrorAlert
                });
                $('#bannerTextEditor').wysiwyg({ toolbarSelector: '[data-role=editor-toolbar-bannerTextEditor]'} );
                
                window.prettyPrint;
                prettyPrint();
              });
            </script>
          <?php }
          ?>                
      </div>        
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $('#campaignLiveDate').daterangepicker({
          minDate: moment(),
          singleDatePicker: true,
          calender_style: "picker_1"
        }, function(start, end, label) {
          $('#campaignLiveDate').val(end.format('MMMM D, YYYY'));
          formValidation.showErrorMessage('campaignLiveDate');
        }); 
});
</script>
