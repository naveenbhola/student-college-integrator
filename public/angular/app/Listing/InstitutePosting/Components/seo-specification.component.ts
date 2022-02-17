import { Component,Input } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "seo-specification",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('seo_specification.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective]
})
 
export class SeoSpecificationComponent extends Posting{
	@Input() instituteObj;
	@Input() instituteStaticData; 
    @Input('form') form: NgForm;	
}

