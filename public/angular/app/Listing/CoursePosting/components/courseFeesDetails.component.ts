import { Component, OnInit, Input, Output, EventEmitter, OnChanges } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { MapToIterable, SliceArrayPipe } from '../../../pipes/arraypipes.pipe';
import { AddColumnTableComponent } from '../../../reusables/addColumnTableComponent';
import { CourseFeesTable } from './courseFeesTable';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector: 'courseFeesDetails',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseFeesDetails.component.html'),
	pipes: [MapToIterable, SliceArrayPipe],
	directives: [REACTIVE_FORM_DIRECTIVES, AddColumnTableComponent, CourseFeesTable]
})

export class CourseFeesDetailsComponent extends Posting implements OnInit, OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() courseStaticData;
	@Input() editData;
	@Input('group') courseFeesForm: FormGroup;

	yearArr = [];
	// colItemShow = [];
	// addMoreColItem = [];
	_addMore = [];
	_default = [];
	initialized = false;

	ngOnInit() {
		let date = new Date();
		let year = date.getFullYear();
		this.yearArr.push(year - 1);
		this.yearArr.push(year);
		this.yearArr.push(year + 1);

		for (let i of this.courseStaticData.categories) {
			if (i.type == 'default') {
				this._default.push(i);
				//this.colItemShow.push(i);
			} else {
				this._addMore.push(i);
				//this.addMoreColItem.push(i);
			}
		}
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
		this.courseFeesForm.addControl('batch', new FormControl(''));
		this.courseFeesForm.addControl('fees_unit', new FormControl('1'));

		this.courseFeesForm.addControl('total_fees_period', new FormControl('year'));
		this.courseFeesForm.addControl('total_fees', new FormArray([]));
		this.courseFeesForm.addControl('total_fees_total', new FormGroup({}));
		this.courseFeesForm.addControl('total_fees_one_time_payment', new FormGroup({}));
		this.courseFeesForm.addControl('hostel_fees', new FormGroup({}));
		this.courseFeesForm.addControl('total_fees_includes', new FormGroup({
			'Hostel': new FormControl(),
			'Tuition': new FormControl(),
			'Admission': new FormControl(),
			'Library': new FormControl(),
			'Others': new FormControl(),
		}));
		//this.courseFeesForm.addControl('total_fees_includes_others',new FormArray([]));
		this.courseFeesForm.addControl('other_info', new FormControl('',[My_Custom_Validators.validateMinLength(40),My_Custom_Validators.validateMaxLength(1000)]));
		this.courseFeesForm.addControl('fees_disclaimer', new FormControl());
		this.courseFeesForm.controls['total_fees_includes']['controls']['Others'].valueChanges.subscribe((data) => {
			if (data == true || data === 1) {
				(<FormGroup>this.courseFeesForm.controls['total_fees_includes']).addControl('OthersText', new FormArray([]));
				this.addOthersText();
			} else {
				(<FormGroup>this.courseFeesForm.controls['total_fees_includes']).removeControl('OthersText');
			}

		});
	}

	addOthersText() {
		this.courseFeesForm.controls['total_fees_includes'][
			'controls']['OthersText'].push(new FormGroup({ 'other_text': new FormControl('',My_Custom_Validators.validateMaxLength(40)) }));
	}

	removeOthersText(index) {
		(<FormArray>this.courseFeesForm.controls['total_fees_includes'][
			'controls']['OthersText']).removeAt(index);
		this.courseFeesForm.controls['total_fees_includes'].markAsDirty();

	}

	deleteTotalFees(index) {
		//this.courseFeesForm.controls['total_fees'].removeControl(index);
	}


	private fillFormGroupData(): void {
		if (this.editData.length == 0) {
			return;
		}

		for (let key in this.editData) {
			switch (key) {
				case 'batch':
				case 'fees_unit':
				case 'total_fees_period':
				case 'other_info':
				case 'fees_disclaimer':
					setTimeout(() => { (<FormControl>this.courseFeesForm.controls[key]).updateValue(this.editData[key], { emitEvent: true }); }, 0);
					break;
				case 'total_fees_includes':
					if(typeof this.editData[key]['OthersText'] != 'undefined'){
						let othersText = this.editData[key]['OthersText'];
						delete this.editData[key]['OthersText'];
						super.fillFormGroup(this.editData[key], this.courseFeesForm.controls[key]);

						othersText.forEach((value, index) => {
							setTimeout(() => {
								if(index != 0){
									this.addOthersText();
								}
								this.courseFeesForm.controls[key]['controls']['OthersText']['controls'][index]['controls']['other_text'].updateValue(value, {emitEvent: true});
							}, 500);
						});
					} else {
						super.fillFormGroup(this.editData[key], this.courseFeesForm.controls[key]);
					}
					break;
				default:
					break;
			}
		}
	}
}