import { Component, OnInit,EventEmitter,OnDestroy,ViewChild,Input, AfterViewInit } from '@angular/core';
import { Router,ActivatedRoute } from '@angular/router';
import { NgForm}    from '@angular/forms';
import { ListingBaseClass, ListingBaseService } from '../../../services/listingbase.service';

import { InstitutePostingService } from '../../../services/institute-posting.service';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import { Institute } from '../Classes/Institute';
import { SidebarService } from '../../../services/sidebar-service';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import {MY_OBJ_VALIDATORS} from '../../ListingReusables/Validators/ObjectValidators';

import { INSTITUTE_POSTING_CHILD_DIRECTIVES } from './institutePostingAutoloader';

import {toolTipComponent} from '../../../reusables/toolTip.component';
import {toolTipInstitutes} from '../Classes/toolTipInstitutes';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
declare var tinyMCE:any;

@Component({
    selector: 'institutePostingCreate',
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('institutePostingCreate.component.html'),
  directives:[ MY_VALIDATORS,INSTITUTE_POSTING_CHILD_DIRECTIVES,MY_OBJ_VALIDATORS,toolTipComponent],
    providers:[InstitutePostingService,ListingBaseService,Institute,InstituteLocationService,toolTipInstitutes],
})

export class InstitutePostingCreateComponent extends ListingBaseClass implements OnInit,OnDestroy, AfterViewInit{

    isModalOpened : boolean= false;
    instituteStaticData;
    mode                  = 'add';
    facilitiesData:any;
    submitPending         = false;    
    activateAcademicStaff = true;
    locationData;
    activateEvents        = true;

    activateForm = false;
    uriParamDummy : any = '';
  	private _sidebarflag = false;
    showLoader=true;
    toolTipMsg : string = "";

    sub;
    instituteId;
    oldInstituteState;
    
    isAllowCancel : boolean = true;
    tooltipPositionX : any = 0;
    tooltipPositionY : any = 0;
    checkedFlag : boolean = true;
    validateLocationSection = true;
    instituteForm: any;
    dbStatusFlag : any;
    aboutCollegeContent : string;


   @Input() postingType: string = 'Institute';
    constructor(private institutePosting: InstitutePostingService, 
                public listingBaseService: ListingBaseService,
                public instituteObj : Institute,public sidebarService:SidebarService,private route: ActivatedRoute, private router: Router,private toolTip:toolTipInstitutes,private instituteLocationService:InstituteLocationService
               ) { 
     super(listingBaseService);
     this.sidebarService.showLinks([]);
    }

    set sidebarflag(val:any){
      this._sidebarflag = val;
      this.instituteObj.is_dummy = val;
      if(val == 'true'){
        if(this.postingType == 'University')
        {
           this.sidebarService.showLinks([{id:'university_parent_mapping_form', value: 'Parent Mapping'},
             {id: 'basic_info_form',value: 'Basic Info'}]);
        }
        else
        {
          this.sidebarService.showLinks([{id:'parent_mapping_form', value: 'Parent Mapping'},
             {id: 'basic_info_form',value: 'Basic Info'}]);
        }
      }
      else{
        this.sidebarService.showLinks([]);
      }
    }

    get sidebarflag(){
      return this._sidebarflag;
    }


    rebindFormControl(str:string){
        str = 'activate' + str.charAt(0).toUpperCase() + str.slice(1);
        this[str] = false;
        setTimeout(() => {this[str] = true;},0);
    }

    setFormForAddMode(){
        this.instituteObj.addMainLocation();
    }

    setFormForEditMode(data){
        this.instituteObj.fillData(data);
        this.facilitiesData = data.facilities;
        this.sidebarflag = data.is_dummy;
        setTimeout(() => {
          this.initTinyMce();
        }, 100);
    }

    ngOnInit() {
      //below line is used for retrieve query parameter value
      this.router.routerState.queryParams.subscribe(data => this.uriParamDummy = data['is_dummy']);
      if(this.uriParamDummy == 'true')
      {
        this.instituteObj.is_dummy = this.uriParamDummy;
        this.sidebarflag = this.instituteObj.is_dummy;
      }
      else if(this.uriParamDummy == 'false')
      {
        this.instituteObj.is_dummy = 'false';
        this.sidebarflag = this.instituteObj.is_dummy;
      }

      

      this.sub = this.route.params.subscribe(params => {
          let id = +params['id'];
          if(id){
              this.mode = 'edit';
              this.instituteId = id;
          }
      });

      if(this.mode == 'edit')
      {
          this.institutePosting.checkUserAllowed(this.instituteId,this.postingType).subscribe(
            data => {
              if(data['isUserAllowEdit'])
              {
                this.initializeForm();
              }
              else{
                  alert('Another Person is in Editing Mode');
                  this.backToHome();
              }
            }
            );
      }
      if(this.mode == 'add')
      {
        this.initializeForm();
      }
      this.activateForm = true;
    }

    public editor:any;

    ngAfterViewInit():void {
        this.initTinyMce();
    }

    initTinyMce() {
      tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "remove,shikshaFile,shikshaImage,quote,embed,autolink,youtubeIframe,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,pasteword,|,link,unlink,|,bullist,numlist,|,embed,quote,|,shikshaImage,shikshaFile,|,remove,styleselect,formatselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,image,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,sub,sup,|,fullscreen,|,preview,|,VCMS",
        
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
    editor_selector : "mceEditor",
    schema: 'html5',
    setup: editor => {
      this.editor = editor;
        editor.onKeyUp.add((a, b) => {
          this.instituteObj.about_college = a.getContent();
        })
        // editor.on('keyup', () => {
        //           this.aboutCollegeContent = editor.getContent();
        //           console.log(this.aboutCollegeContent);
        //         })
      },
      editor_deselector : "mceNoEditor",
    content_css : '/public/css/articles.css',
    width:600,
    height:300,
        // Skin options
        skin : "o2k7",
        skin_variant : "silver",
    valid_elements : ""
          +"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
            +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
            +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
          +"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
            +"|height|hspace|id|name|object|style|title|vspace|width],"
          +"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
            +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
            +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
          +"base[href|target],"
          +"basefont[color|face|id|size],"
          +"bdo[class|dir<ltr?rtl|id|lang|style|title],"
          +"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
            +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
            +"|onmouseover|onmouseup|style|title],"
          +"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
          +"br[class|clear<all?left?none?right|id|style|title],"
          +"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
            +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
            +"|value],"
          +"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
            +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
            +"|valign<baseline?bottom?middle?top|width],"
          +"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
            +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
            +"|valign<baseline?bottom?middle?top|width],"
          +"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
          +"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
          +"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
          +"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
            +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
            +"|style|title|target],"
          +"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
            +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
          +"frameset[class|cols|id|onload|onunload|rows|style|title],"
          +"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"head[dir<ltr?rtl|lang|profile],"
          +"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
          +"html[dir<ltr?rtl|lang|version],"
          +"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
            +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
            +"|title|width],"
          +"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
            +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|src|style|title|usemap|vspace|width],"
          +"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
            +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
            +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
            +"|readonly<readonly|size|src|style|tabindex|title"
            +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
            +"|usemap|value],"
          +"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
          +"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
            +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
            +"|onmouseover|onmouseup|style|title],"
          +"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
            +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
            +"|value],"
          +"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
          +"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
          +"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"noscript[class|dir<ltr?rtl|id|lang|style|title],"
          +"object[align<bottom?left?middle?right?top|archive|border|class|classid"
            +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
            +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
            +"|vspace|width],"
          +"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|start|style|title|type],"
          +"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
            +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
            +"|onmouseover|onmouseup|selected<selected|style|title|value],"
          +"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|style|title],"
          +"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
          +"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
            +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
            +"|onmouseover|onmouseup|style|title|width],"
          +"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
          +"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"script[charset|defer|language|src|type],"
          +"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
            +"|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
            +"|tabindex|title],"
          +"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title],"
          +"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"style[dir<ltr?rtl|lang|media|title|type],"
          +"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title],"
          +"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
            +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
            +"|style|summary|title|width],"
          +"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
            +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
            +"|valign<baseline?bottom?middle?top],"
          +"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
            +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
            +"|style|title|valign<baseline?bottom?middle?top|width],"
          +"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
            +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
            +"|readonly<readonly|rows|style|tabindex|title],"
          +"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
            +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
            +"|valign<baseline?bottom?middle?top],"
          +"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
            +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
            +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
            +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
            +"|style|title|valign<baseline?bottom?middle?top|width],"
          +"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
            +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
            +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
            +"|valign<baseline?bottom?middle?top],"
          +"title[dir<ltr?rtl|lang],"
          +"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
            +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title|valign<baseline?bottom?middle?top],"
          +"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
          +"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
            +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
          +"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
            +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
            +"|onmouseup|style|title|type],"
          +"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
            +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
            +"|title]"
});
    }

    initializeForm()
    {
         this.institutePosting.getLocationData().subscribe(
            locationData => { 
              this.locationData = locationData;
              if(this.mode == 'edit'){
                this.showLoader = true;
                this.getDataForEditForm();
              }
            },
             error => {alert('Failed to get location data');}
        );

        if(this.mode == 'add'){
          this.getStaticFormData();
          this.setFormForAddMode();
          this.showLoader = false;
        }
        if(this.postingType == 'University')
        {
          this.instituteObj.postingListingType = this.postingType;
          this.sidebarService.updateLinks({'activeLink':'universityPosting','subLink':'universityPosting_create'});
        }
        else
        {
          this.sidebarService.updateLinks({'activeLink':'institutePosting','subLink':'institutePosting_create'});  
        }
    }

    getDataForEditForm(){

    	this.institutePosting.getInstituteData(this.instituteId,this.instituteObj.is_dummy,this.postingType).subscribe(data => {
                  if(data == 'NO_SUCH_LISTING_FOUND_IN_DB'){
                      alert('No such Institute exists');this.backToHome();
                  }
                  else{
                      this.setFormForEditMode(data);
                      this.rebindFormControl('form');
					  this.showLoader = false;
                      setTimeout(() => {
                      	this.oldInstituteState = JSON.parse(JSON.stringify(this.instituteObj));
                      	this.getStaticFormData();
                      },2000);
                      
                  }
              }
              ,error => {alert('Internal Error');
              this.backToHome();
          });
    }

    getStaticFormData(){

    	this.institutePosting.getInstituteStaticData().subscribe(
            instituteStaticData => { this.instituteStaticData = instituteStaticData['static_data']; 
            this.instituteObj.media_server_domain = this.instituteStaticData['media_server_domain'];
          },
             error => {alert('Failed to get institute static data');}
        );
    }

	ngOnDestroy(){
        this.sub.unsubscribe();
    }

    get diagnostic(){
        return JSON.stringify(this.instituteObj);
    }

    initializeInstituteObj(){
      let temp = this.instituteObj.postingListingType;
      this.instituteObj = new Institute();
      this.instituteObj.postingListingType = temp ? temp : 'Institute';
      this.rebindFormControl('form');
      this.instituteObj.addMainLocation();
    }

    saveInstitute(instituteForm, statusFlag){     

           this.instituteObj.about_college = this.editor.getContent();
          this.instituteForm = instituteForm;
          this.dbStatusFlag = statusFlag;
          if(this.validateLocationSection && this.instituteObj.is_dummy == 'false')
          {
            this.instituteLocationService.pushValidateLocationMedia({});
          }
          else
          {
                 if (this.instituteForm.valid){
                  this.submitPending = true;       
                  this.instituteObj.statusFlag = this.dbStatusFlag;
                  this.instituteObj.savingMode = this.mode;
                  this.institutePosting.saveInstituteData(this.instituteObj, this.oldInstituteState).subscribe(
                     data => { 
                           alert(data['message']);
                           if(data.status == 'success')
                           {
                             this.submitPending = false; 
                             this.backToHome();
                           }
                           else{
                             this.submitPending = false;
                           } 
                         },
                      error => { 
                        alert('Internal error'); 
                      }
                  );
                }
          }

       
    }

    backToHome(){
        if(this.postingType == 'University')
        {
          location.href= "/nationalInstitute/UniversityPosting/viewList";
        }
        else
        {
          location.href= "/nationalInstitute/InstitutePosting/viewList";
        }
        
    }
    toolTipModalShow(val:any)   
    {
      this.isModalOpened = true;
      this.toolTipMsg = this.toolTip.toolTipArray[val['val']];
      this.tooltipPositionX = val['x'];
      this.tooltipPositionY = val['y'];
      this.changeDetected();
    }
    changeDetected()
    {
      this.checkedFlag = false;
      setTimeout(() => {this.checkedFlag = true;},20);
    }
    cancelEdit(id:number,type)
    {
      this.isAllowCancel = false;
      this.institutePosting.cancelEditForm(id,type).subscribe(
                                      data => {
                                        if(data['cancelEdit'])
                                        {
                                          this.backToHome();
                                        }
                                        else{
                                          alert('Internal Error');
                                        }
                                      }
                                      );
    }

    fireSaveInstitute(obj:any)
    {  
      this.validateLocationSection = obj.val;
      if(!obj.val)
        this.saveInstitute(this.instituteForm,this.dbStatusFlag);
      
    }

}