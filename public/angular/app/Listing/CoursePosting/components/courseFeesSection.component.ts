import { Component, OnInit, Input, OnChanges } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseFeesSection',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseFeesSection.component.html'),
})
export class CourseFeesComponent implements OnInit, OnChanges {

	@Input() courseStaticData;
	@Input('group') courseFeesForm: FormGroup;
	@Input() mode;

	yearArr: number[];
	currenciesOptions;

	ngOnInit() {
		let date = new Date();
		let year = date.getFullYear();
		this.yearArr.push(year - 1);
		this.yearArr.push(year);
		this.yearArr.push(year + 1);

		this.courseFeesForm.addControl('batch', new FormControl('', Validators.required));
		this.courseFeesForm.addControl('fees_unit', new FormControl('INR', Validators.required));
		this.courseFeesForm.addControl('total_fees', new FormArray([]));
		this.courseFeesForm.addControl('tution_fees', new FormArray([]));
		this.courseFeesForm.addControl('hostel_fees', new FormArray([]));
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData' && changes[propName]['currentValue']) {
				this.populateCurrencies();
			}
		}
	}

	populateCurrencies() {

	}
}