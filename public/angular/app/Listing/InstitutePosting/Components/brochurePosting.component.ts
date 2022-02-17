import { Component,OnInit,Input,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { FileUploadService } from '../../../services/file-upload.service';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "brochure-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('brochurePosting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective],
	providers:[FileUploadService]
})

export class BrochurePostingComponent extends Posting implements OnInit{

	@Input() instituteObj; 
  @Input('form') form: NgForm;
  showLoader=0;

  @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
  
	ngOnInit(){
    if(this.brochureYearOptions.length == 0) {
        let date = new Date();
        let year = date.getFullYear();
        this.brochureYearOptions.push(year-1);
        this.brochureYearOptions.push(year);
        this.brochureYearOptions.push(year+1);
    }
	}
	constructor(public fileUploadService: FileUploadService){
		super();
		this.fileUploadService.progress$.subscribe(
	      data => {
	        this.uploadProgress = data;
	      });
	}

  instituteBrochure = [];
  instituteBrochureErrMsg:string = '';
  brochureYearOptions:any = [];
  uploadProgress: number = 0;
  showProgressBar:boolean = false;


    fileChange(input) {
      this.uploadProgress = 0;      
      this.showProgressBar=false;
      this.instituteBrochure = [];
      this.instituteBrochureErrMsg = '';
      this.instituteObj.brochure_url = '';
      this.instituteObj.brochure_size = '';
      if(input.files.length == 0) {
          return false;
      }
      this.showLoader = 1;
      this.showProgressBar = true;      
      let FileList: FileList = input.files;
       for (let i = 0, length = FileList.length; i < length; i++) {
        this.instituteBrochure.push(FileList.item(i));
       }
       this.fileUploadService.upload('nationalInstitute/InstitutePosting/uploadInstituteBrochure', this.instituteBrochure)
       .then(this.handlePromise.bind(this))
       .catch(function(err) {
           alert('Unable to upload brochure due to network issue');
       });  
    }
    
    handlePromise(response) {
           if(response.brochure_size) {
               var url = response.brochure_url;
               this.instituteObj.brochure_url = url;
               this.instituteObj.brochure_size = response.brochure_size;
               this.resetBrochureYear();
               if(this.brochureYearOptions.length == 0) {
                   let date = new Date();
                   let year = date.getFullYear();
                   this.brochureYearOptions.push(year-1);
                   this.brochureYearOptions.push(year);
                   this.brochureYearOptions.push(year+1);
               }
           }
           else {
               this.instituteBrochureErrMsg = response.error.msg;
           }
          this.showLoader = 0;
    }

    clearBrochureData(brochureYear) {
        this.instituteObj.brochure_url = '';
        this.instituteObj.brochure_size = '';
        this.showProgressBar = false;
        this.resetBrochureYear();
    }

    resetBrochureYear() {
      let year = new Date().getFullYear();
      let control = <FormControl>this.form.form.controls['brochureYear'];
      if(control){
        control.updateValue(year,{emitEvent:true});
      }

      this.instituteObj.brochure_year = year;
    }
}
    