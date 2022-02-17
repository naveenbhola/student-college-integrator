import { Component, OnChanges, Input, OnDestroy, OnInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { UploadMedia } from '../../../reusables/uploadMedia';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseInternship',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseInternship.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, UploadMedia]
})
export class CourseInternshipComponent extends Posting implements OnInit, OnChanges {
	@Input('group')
    public courseInternship: FormGroup;
	yearArr = [];
	@Input() courseStaticData;
	@Input() editData;
	initialized = false;
	showInternshipReport = false;
	showLoader = 0;


    ngOnInit() {
		this.opened = false;
		let date = new Date();
		let year = date.getFullYear();
		this.yearArr.push(year - 1);
		this.yearArr.push(year);
		this.yearArr.push(year + 1);
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData' || propName == 'courseInternship') {
				if (this.courseStaticData && this.courseInternship) {
					this.generateFormControls();
					this.initialized = true;
				}
			}


			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.opened = typeof this.editData != 'undefined' && typeof this.editData['intern_batch'] != 'undefined';
			}
		}
	}

	generateFormControls() {
		this.courseInternship.addControl('intern_batch', new FormControl(''));
		this.courseInternship.addControl('intern_batch_unit', new FormControl('1'));
		this.courseInternship.addControl('intern_min_stipend', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseInternship.addControl('intern_median_stipend', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseInternship.addControl('intern_average_stipend', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseInternship.addControl('intern_max_stipend', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseInternship.addControl('report_url', new FormControl());

		this.courseInternship.setValidators([
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'intern_min_stipend', 'max_control': 'intern_max_stipend', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'intern_min_stipend', 'max_control': 'intern_median_stipend', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'intern_min_stipend', 'max_control': 'intern_average_stipend', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'intern_median_stipend', 'max_control': 'intern_max_stipend', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'intern_average_stipend', 'max_control': 'intern_max_stipend', 'allowequal': true }),
		]);
	}

	uploadInternshipCallback(res) {
		if (res == 'start') {
            this.showLoader = 1;
        }
		if (typeof res === "string") {
			if (res == 'no file') {
                this.clearBrochureData();
            } else if (res != '') {
				(<FormControl>this.courseInternship.controls['report_url']).updateValue(res);
				this.courseInternship.markAsDirty();
			}
			if(res !== 'start'){
				this.showLoader = 0;
			}
		}
		else {
			(<FormControl>this.courseInternship.controls['report_url']).updateValue(null);
			this.showLoader = 0;
		}
	}

	clearBrochureData() {
		this.courseInternship.markAsDirty();
        (<FormControl>this.courseInternship.controls['report_url']).updateValue(null);
        this.showInternshipReport = true;

        setTimeout(() => {
			this.showInternshipReport = false;
        }, 100);

    }
}