import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { DELIVERY_METHOD, EDUCATION_TYPE } from '../classes/CourseConst';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { ShikshaInstituteAutosuggestorComponent } from '../../ListingReusables/components/shikshaInstituteAutosuggestor.component';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseBasicInfo',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseBasicInfoSection.component.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, ShikshaInstituteAutosuggestorComponent]
})
export class CourseBasicInfoSectionComponent extends Posting implements OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() courseStaticData;
	@Input('group')
    public courseBasicInfoForm: FormGroup;
    currentYear;
	invalidIdError: string = '';
	ngOnInit() {
		let d = new Date();
        this.currentYear = d.getFullYear();
        let splitYear = this.currentYear.toString().split("");
		this.courseBasicInfoForm.addControl('course_name', new FormControl('', Validators.compose([Validators.required, Validators.maxLength(200)])));
		this.courseBasicInfoForm.addControl('duration_value', new FormControl('', Validators.compose([Validators.required, My_Custom_Validators.validateWholeNumber(0)])));
		this.courseBasicInfoForm.addControl('duration_unit', new FormControl('years'));
		this.courseBasicInfoForm.addControl('duration_disclaimer', new FormControl());
		this.courseBasicInfoForm.addControl('recognition', new FormControl(''));
		this.courseBasicInfoForm.addControl('instruction_medium', new FormControl(''));
		this.courseBasicInfoForm.addControl('difficulty_level', new FormControl(''));
		this.courseBasicInfoForm.addControl('affiliated_university_scope', new FormControl(''));
		this.courseBasicInfoForm.addControl('affiliated_university_id', new FormControl(''));
		this.courseBasicInfoForm.addControl('affiliated_university_name', new FormControl('',My_Custom_Validators.validateMaxLength(200)));
		this.courseBasicInfoForm.addControl('affiliated_university_year', new FormControl(''));
		this.courseBasicInfoForm.controls['affiliated_university_scope'].valueChanges.subscribe((data) => {
			this.handleAffiliationChange(data);
		});
	}

	handleAffiliationChange(data) {
		(<FormControl>this.courseBasicInfoForm.controls['affiliated_university_id']).updateValue('');
		(<FormControl>this.courseBasicInfoForm.controls['affiliated_university_name']).updateValue('');
		(<FormControl>this.courseBasicInfoForm.controls['affiliated_university_year']).updateValue('');
	}

	// setSelectedCourseRecognition(res){
	// 	(<FormControl>this.courseBasicInfoForm.controls['recognition']).updateValue(res.selected);		
	// }

	selectedAffiliationInstitute(res) {
		if (typeof res['selectedValue']['error'] != 'undefined') {
			this.invalidIdError = res['selectedValue']['error'];
		} else {
			this.invalidIdError = '';
			(<FormControl>this.courseBasicInfoForm.controls['affiliated_university_id']).updateValue(res['selectedValue']['id']);
			(<FormControl>this.courseBasicInfoForm.controls['affiliated_university_name']).updateValue(res['selectedValue']['name']);
			this.courseBasicInfoForm.controls['affiliated_university_id'].markAsDirty();
		}
	}

	// setInstructionMedium(res){
	// 	(<FormControl>this.courseBasicInfoForm.controls['instruction_medium']).updateValue(res.selected);		
	// }
}