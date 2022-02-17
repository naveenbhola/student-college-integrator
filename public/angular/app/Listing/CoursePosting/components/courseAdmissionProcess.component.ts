import { Component, OnChanges, Input, Output, EventEmitter, OnDestroy, OnInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray, ValidatorFn } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector: 'admissionProcess',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseAdmissionProcess.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})
export class CourseAdmissionProcessComponent extends Posting implements OnChanges, OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group')
    public courseAdmissionProcess: FormGroup;

    @Input() courseStaticData;
    @Input() editData;

    initialized = false;

    ngOnInit() {
		this.generateFormControls();
		this.initialized = true;
    }

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData();
			}
		}
	}

	generateFormControls() {
		this.courseAdmissionProcess.addControl('admission_process', new FormArray([]));
		this.addAdmissionProcess();
	}

	addAdmissionProcess() {
		let group = new FormGroup({
			'admission_name': new FormControl(''),
			'admission_description': new FormControl('', [My_Custom_Validators.validateMaxLength(1500),My_Custom_Validators.validateMinLength(40)])
		}
								);
		(<FormArray>this.courseAdmissionProcess.controls['admission_process']).push(group);
		group.setValidators(My_Custom_Validators.ValidateOneOrNoneFormGroup(
			{ 'mymust': ['admission_name', 'admission_description'], 'myoptional': [] }
		)
		);

	}

	addControlForOthers(index, data) {
		if (data == 'Others') {
			(<FormGroup>(<FormArray>this.courseAdmissionProcess.controls['admission_process']).controls[index]).addControl('admission_name_others', new FormControl('', [Validators.required,My_Custom_Validators.validateMaxLength(40)]));
		} else {
			if ((<FormGroup>(<FormArray>this.courseAdmissionProcess.controls['admission_process']).controls[index]).contains('admission_name_others')) {
				(<FormGroup>(<FormArray>this.courseAdmissionProcess.controls['admission_process']).controls[index]).removeControl('admission_name_others');
			}
		}
	}

	removeAdmissionProcess(index) {
		(<FormArray>this.courseAdmissionProcess.controls['admission_process']).removeAt(index);
		this.courseAdmissionProcess.controls['admission_process'].markAsDirty();
	}

	fillFormGroupData() {
		this.editData['admission_process'].forEach((val, index) => {
			if (index > 0) {
				this.addAdmissionProcess();
			}
			if (val['admission_name'] == 'Others') {
				setTimeout(() => { this.addControlForOthers(index, val['admission_name']); }, 0);
			}
			super.fillFormGroup(val, (<FormArray>this.courseAdmissionProcess.controls['admission_process']).controls[index]);
		})
	}
}