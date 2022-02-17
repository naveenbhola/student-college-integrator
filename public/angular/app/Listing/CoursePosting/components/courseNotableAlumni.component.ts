import { Component, OnChanges,Input,OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup,FormControl,Validators,FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';

import { UploadMedia } from '../../../reusables/uploadMedia';


@Component({	
	selector: 'notableAlumni',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/courseNotableAlumni.template.html',
	directives:[REACTIVE_FORM_DIRECTIVES,UploadMedia]
})
export class CourseNotableAlumniComponent extends Posting implements OnChanges{
	@Input('group')
    public courseNotableAlumni: FormGroup;
	
	ngOnChanges(changes) {
		this.generateFormControls();

	}

	generateFormControls(){
		this.courseNotableAlumni.addControl('notable_alumni', new FormArray([]));
		this.addNotableAlumni();
	}

	addNotableAlumni() {
		(<FormArray>this.courseNotableAlumni.controls['notable_alumni']).push(
																			new FormGroup({
														 						'alumni_name':new FormControl(''), 
														 						'alumni_description':new FormControl('')
														 						})
																			);
	}

	removeNotableAlumni(index) {
		(<FormArray>this.courseNotableAlumni.controls['notable_alumni']).removeAt(index);
	}


	uploadPhotoCallback(res){

	}
}