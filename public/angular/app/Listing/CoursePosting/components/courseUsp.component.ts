import { Component, OnChanges, Input, Output, EventEmitter, OnDestroy, OnInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray, ValidatorFn } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { My_Custom_Validators } from '../../../reusables/Validators';

@Component({
	selector: 'courseUsp',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseUsp.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})
export class CourseUspComponent extends Posting implements OnChanges, OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group')
    public courseUsp: FormGroup;

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
		this.courseUsp.addControl('usp_list', new FormArray([]));
		this.addUsp();
	}

	addUsp() {
		let group = new FormGroup({
			'usp': new FormControl('',[My_Custom_Validators.validateMinLength(40),My_Custom_Validators.validateMaxLength(200)])
		});

		(<FormArray>this.courseUsp.controls['usp_list']).push(group);
	}

	removeUsp(index) {
		(<FormArray>this.courseUsp.controls['usp_list']).removeAt(index);
		this.courseUsp.controls['usp_list'].markAsDirty();
	}

	fillFormGroupData() {
		if (this.editData.length == 0) {
			return;
		}
		this.editData['usp_list'].forEach((val, index) => {
			if (index > 0) {
				this.addUsp();
			}
			super.fillFormGroup(val, (<FormArray>this.courseUsp.controls['usp_list']).controls[index]);
		})
	}
}