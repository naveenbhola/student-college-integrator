import { Component, OnInit, Input, Output, EventEmitter, OnChanges, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { AddColumnTableComponent } from '../../../reusables/addColumnTableComponent';
import { ListingBaseService } from '../../../services/listingbase.service';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { COURSE_LEVEL } from '../classes/CourseConst';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseEligibilityDetails',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseEligibilityDetails.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, AddColumnTableComponent]
})
export class CourseEligibilityComponent extends Posting implements OnChanges, OnDestroy, OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group') courseEligibilityForm: FormGroup;
	@Input() courseStaticData;
	@Input() mode;
	@Input() editData;

	initialized = false;
	_default = [];
	_addMore = [];
	yearArr = [];
	exams_default = [];
	exams_addmore = [];
	exam_cols = [];
	specializationOptions = [];
	postSpecializationOptions = [];
	subjects = [];
	subscribeArray = {};
	outOfFlags = [false, false, false, false];
	hideSectionFlags = [true, true, true, true];
	CLCONST = COURSE_LEVEL;

	constructor(public listingBaseService: ListingBaseService, public dependencyService: CourseDependencyService) {
		super();
	}

	ngOnInit() {
		let date = new Date();
		let year = date.getFullYear();
		this.yearArr.push(year - 1);
		this.yearArr.push(year);
		this.yearArr.push(year + 1);

		this.dependencyService.courseLevelObservable.subscribe((data) => {
			let courseLevel = data['data'];
			let index;
			if (courseLevel) {
				if (courseLevel == this.CLCONST.preug) {
					index = 0;
				}
				else if (courseLevel == this.CLCONST.ug) {
					index = 1;
				}
				else if (courseLevel == this.CLCONST.pg) {
					index = 2;
				}
				else {
					index = 100;
				}
			}
			else {
				index = -1;
			}
			this.showHideSections(index, data);
		});

		this.generateFormControls();
		for (let i of this.courseStaticData.categories) {
			if (i.type == 'default') {
				this._default.push(i);
			}
			else {
				this._addMore.push(i);
			}
		}
		this.exams_default = this._default.filter(() => { return true; });
		this.exams_addmore = this._addMore.filter(() => { return true; });
		this.exam_cols = this._default.filter(() => { return true; });
		this.courseStaticData['eligibility_subjects'].forEach((val) => { this.subjects.push({ 'label': val, 'value': val }) });
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
		this.courseEligibilityForm.addControl('10th_details', new FormGroup({
			'score_type': new FormControl('percentage'),
			'category_wise_cutoff': new FormGroup({ 'outof': new FormControl('100', My_Custom_Validators.validateWholeNumber(0)) }),
			'description': new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)])
		}));
		this.courseEligibilityForm.addControl('12th_details', new FormGroup({
			'score_type': new FormControl('percentage'),
			'category_wise_cutoff': new FormGroup({ 'outof': new FormControl('100', My_Custom_Validators.validateWholeNumber(0)) }),
			'description': new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)])
		}));
		this.courseEligibilityForm.addControl('postgraduation_details', new FormGroup({
			'score_type': new FormControl('percentage'),
			'category_wise_cutoff': new FormGroup({ 'outof': new FormControl('100', My_Custom_Validators.validateWholeNumber(0)) }),
			'description': new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)]),
			'entityMapping': new FormArray([])
		}));
		this.courseEligibilityForm.addControl('graduation_details', new FormGroup({
			'score_type': new FormControl('percentage'),
			'category_wise_cutoff': new FormGroup({ 'outof': new FormControl('100', My_Custom_Validators.validateWholeNumber(0)) }),
			'description': new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)]),
			'entityMapping': new FormArray([])
		}));

		this.courseEligibilityForm.addControl('exams_accepted', new FormArray([]));

		let controls = ['10th_details', '12th_details', 'graduation_details', 'postgraduation_details'];
		controls.forEach((val, index) => {
			(<FormGroup>this.courseEligibilityForm.controls[val]).controls['score_type'].valueChanges.subscribe((data) => {
				let value;
				if (data == 'percentage') {
					this.outOfFlags[index] = false;
					value = '100';
				}
				else {
					this.outOfFlags[index] = true;
					value = '';
				}
				this.resetFormGroupControls(<FormGroup>(<FormGroup>this.courseEligibilityForm.controls[val]).controls['category_wise_cutoff']);
				(<FormControl>(<FormGroup>(<FormGroup>this.courseEligibilityForm.controls[val]).controls['category_wise_cutoff']).controls['outof']).updateValue(value, { emitEvent: true });
			});
		});

		this.courseEligibilityForm.addControl('subjects', new FormControl());
		this.courseEligibilityForm.addControl('other_subjects', new FormArray([]));
		this.addSubject();
		this.addEntityMapping('graduation_details');
		this.addEntityMapping('postgraduation_details');
		if (this.mode == 'add') {
			setTimeout(() => { this.addExamsAcceptedRow(); }, 0);
		}
		this.courseEligibilityForm.addControl('work-ex_min', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseEligibilityForm.addControl('work-ex_max', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseEligibilityForm.addControl('work-ex_unit', new FormControl('months'));
		this.courseEligibilityForm.addControl('age_min', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseEligibilityForm.addControl('age_max', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseEligibilityForm.addControl('international_description', new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)]));
		this.courseEligibilityForm.addControl('description', new FormControl('', [My_Custom_Validators.validateMaxLength(1000),My_Custom_Validators.validateMinLength(40)]));
		this.courseEligibilityForm.addControl('batch', new FormControl(''));

		this.courseEligibilityForm.setValidators([My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'work-ex_min', 'max_control': 'work-ex_max' }), My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'age_min', 'max_control': 'age_max' })]);
	}

	showHideSections(index, data) {
		let controls = ['10th_details', '12th_details', 'graduation_details', 'postgraduation_details'];
		this.hideSectionFlags.forEach((val, i) => {
			if (i <= index) {
				this.hideSectionFlags[i] = false;
				let group = (<FormGroup>this.courseEligibilityForm.controls[controls[i]]);
				if (group.contains('entityMapping') && (<FormArray>group.controls['entityMapping']).length < 1) {
					this.addEntityMapping(<'graduation_details' | 'postgraduation_details'>controls[i]);
				}
			}
			else {
				this.hideSectionFlags[i] = true;
				let group = (<FormGroup>this.courseEligibilityForm.controls[controls[i]]);
				if (data['reason'] != 'edit') {
					if (group.contains('entityMapping')) {
						this.emptyFormArray((<FormArray>group.controls['entityMapping']));
						this.addEntityMapping(<'graduation_details' | 'postgraduation_details'>controls[i]);
					}
					if (group.contains('description')) {
						(<FormControl>group.controls['description']).updateValue('', { emitEvent: true });
					}
					if (group.contains('score_type')) {
						(<FormControl>group.controls['score_type']).updateValue('percentage', { emitEvent: true });
					}
				}
			}
		});
	}

	resetFormGroupControls(group: FormGroup) {
		for (let control in group.controls) {
			if (control !== 'outof') {
				(<FormControl>group.controls[control]).updateValue('', { emitEvent: true });
			}
		}
	}

	addEntityMapping(type: 'graduation_details' | 'postgraduation_details') {
		let group = new FormGroup({
			'base_course': new FormControl(""),
			'specialization': new FormControl("0")
		});
		let length = (<FormArray>(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']).length;
		group.controls['base_course'].valueChanges.subscribe((data) => { this.populateSpecializations(data, length, type); });

		(<FormArray>(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']).push(group);
	}

	removeEntityMapping(index, type: 'graduation_details' | 'postgraduation_details') {
		setTimeout(() => {
			(<FormArray>(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']).removeAt(index);
			if (type == 'graduation_details') {
				this.specializationOptions.splice(index, 1);
			}
			else if (type == 'postgraduation_details') {
				this.postSpecializationOptions.splice(index, 1);
			}
		}, 0);
		(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping'].markAsDirty();
	}

	populateSpecializations(baseCourseId, index, type: 'graduation_details' | 'postgraduation_details') {
		if ((<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']
			&& (<FormGroup>(<FormArray>(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']).controls[index])) {
			(<FormControl>(<FormGroup>(<FormArray>(<FormGroup>this.courseEligibilityForm.controls[type]).controls['entityMapping']).controls[index]).controls['specialization']).updateValue("0", { emitEvent: true });
		}
		let options;
		if (type == 'graduation_details') {
			options = this.specializationOptions;
		}
		else {
			options = this.postSpecializationOptions;
		}
		options[index] = [];
		if (baseCourseId) {
			this.listingBaseService.getSpecializationByBaseCourses([+baseCourseId]).subscribe((data) => { options[index] = data; });
		}
	}

	addExamsAcceptedRow() {
		let group = new FormGroup(
			{ 'exam_name': new FormControl(''), 'exam_unit': new FormControl(''), 'custom_exam': new FormControl('',My_Custom_Validators.validateMaxLength(40)) }
		);

		group.controls['exam_unit'].valueChanges.subscribe((data) => {
			let columns = [];
			this.exam_cols.forEach((val) => {
				columns.push(val['value']);
			});

			let names = ['exam_name'];
			if(group.controls['exam_name'].value == 'other'){
				names = ['exam_name','custom_exam'];
			}
			if(data == 'rank'){
				group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] })]);
			}
			else{
				group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] }), My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
			}
			for (let control in group.controls) {
				if (['exam_name', 'outof', 'custom_exam', 'exam_unit'].indexOf(control) == -1) {
					if (data == 'percentage' || data == 'percentile') {
						group.controls[control].setValidators(My_Custom_Validators.validateNumber(0));
					}
					else if (data == 'score/marks' || data == 'rank') {
						group.controls[control].setValidators(My_Custom_Validators.validateWholeNumber(0));
					}
					group.controls[control].updateValueAndValidity();
				}
			}
		});

		group.controls['exam_name'].valueChanges.subscribe((data) => {
			let columns = [];
			this.exam_cols.forEach((val) => {
				columns.push(val['value']);
			});

			let names;

			if (data !== 'other') {
				names = ['exam_name'];
				(<FormControl>group.controls['custom_exam']).updateValue('', { emitEvent: true });
			}
			else {
				names = ['exam_name', 'custom_exam'];
			}
			let outoftype = group.controls['exam_unit'].value;
			if(outoftype == 'rank'){
				group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] })]);
			}
			else{
				group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] }), My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
			}
		});

		for (let i of this.exam_cols) {
			group.addControl(i.value, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		}

		group.addControl('outof', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		group.controls['exam_unit'].valueChanges.subscribe((data) => {
			if (data == 'percentage' || data == 'percentile') {
				(<FormControl>group.controls['outof']).updateValue('100', { emitEvent: true });
			}
			else if (data == 'score/marks' || data == 'rank') {
				(<FormControl>group.controls['outof']).updateValue('', { emitEvent: true });
			}
		});

		let columns = [];
		this.exam_cols.forEach((val) => {
			columns.push(val['value']);
		});

		let names = ['exam_name'];
		if(group.controls['exam_name'].value == 'other'){
			names = ['exam_name','custom_exam'];
		}

		let outoftype = group.controls['exam_unit'].value;
		if(outoftype == 'rank'){
			group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] })]);
		}
		else{
			group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] }), My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
		}

		(<FormArray>this.courseEligibilityForm.controls['exams_accepted']).push(group);
	}

	removeExamsAcceptedRow(index) {
		(<FormArray>this.courseEligibilityForm.controls['exams_accepted']).removeAt(index);
		this.courseEligibilityForm.controls['exams_accepted'].markAsDirty();
	}

	addColumn(item) {
		setTimeout(() => {
			if (item) {
				let removeIndex: number;
				this.exams_addmore.forEach((val, index) => {
					if (val['value'] == item) {
						this.exam_cols.push(val);
						for (let control in (<FormArray>this.courseEligibilityForm.controls['exams_accepted']).controls) {

							let group = (<FormGroup>(<FormArray>this.courseEligibilityForm.controls['exams_accepted']).controls[control]);

							if (group.controls['exam_unit'].value == 'percentile' || group.controls['exam_unit'].value == 'percentage') {
								group.addControl(val['value'], new FormControl('', My_Custom_Validators.validateNumber(0)));
							}
							else {
								group.addControl(val['value'], new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
							}

							let columns = [];
							this.exam_cols.forEach((val) => {
								columns.push(val['value']);
							});

							let data = group.controls['exam_name'].value;
							let names;
							if (data !== 'other') {
								names = ['exam_name'];
								(<FormControl>group.controls['custom_exam']).updateValue('', { emitEvent: true });
							}
							else {
								names = ['exam_name', 'custom_exam'];
							}

							setTimeout(() => {
								let outoftype = group.controls['exam_unit'].value;
								if(outoftype == 'rank'){
									group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] })]);
								}
								else{
									group.setValidators([My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['exam_unit'], 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': columns }), My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': names, 'myoptional': ['exam_unit'] }), My_Custom_Validators.ValidateOutOfInFormGroup({ 'fieldlist': columns })]);
								}
							}, 0);
						}
						removeIndex = index;
						this.exams_addmore.splice(removeIndex, 1);
					}
				});
			}
		}, 0);
	}

	addSubject(value = null) {
		(<FormArray>this.courseEligibilityForm.controls['other_subjects']).push(new FormControl(value,My_Custom_Validators.validateMaxLength(40)));
	}

	removeSubject(index) {
		(<FormArray>this.courseEligibilityForm.controls['other_subjects']).removeAt(index);
		this.courseEligibilityForm.controls['other_subjects'].markAsDirty();
	}

	fillFormGroupData() {
		for (let key in this.editData) {
			switch (key) {
				case 'other_subjects':
					if (this.editData[key].length) {
						this.editData[key].forEach((val,index)=>{
							if(!index){
								(<FormControl>(<FormArray>this.courseEligibilityForm.controls[key]).controls[0]).updateValue(val,{emitEvent:true});
							}
							else{
								this.addSubject(val);
							}
						});
					}
					break;
				case '10th_details':
				case '12th_details':
				case 'postgraduation_details':
				case 'graduation_details':
					for (let subkey in this.editData[key]) {
						switch (subkey) {
							case 'category_wise_cutoff': break;
							case 'entityMapping':
								// let i = 0;
								this.editData[key][subkey].forEach((mapping,index)=>{
									if(index){
										this.addEntityMapping(<'graduation_details' | 'postgraduation_details'>key);
									}
									super.fillFormGroup(this.editData[key][subkey][index], (<FormArray>(<FormGroup>this.courseEligibilityForm.controls[key]).controls[subkey]).controls[index]);
								});
								// for (let mapping of this.editData[key][subkey]) {
								// 	if (this.editData[key][subkey].length) {
										
										
								// 		i++;
								// 	}
								// 	else {
								// 		this.addEntityMapping(<'graduation_details' | 'postgraduation_details'>key);
								// 	}
								// }
								break;
							default: super.fillFormGroup(this.editData[key][subkey], (<FormGroup>this.courseEligibilityForm.controls[key]).controls[subkey]); break;
						}
					}
					setTimeout(()=>{this.editData[key] = null;},2500);
					break;
				case 'subjects':
					setTimeout(() => { (<FormControl>this.courseEligibilityForm.controls[key]).updateValue(this.editData[key], { emitEvent: true }); }, 0);
					break;
				case 'exams_accepted':
					this.emptyFormArray(<FormArray>this.courseEligibilityForm.controls[key]);
					this.editData[key].forEach((val, index) => {
						this.addExamsAcceptedRow();
						for (let key in val) {
							for (let key1 of this.exams_addmore) {
								if (key1['value'] == key) {
									this.addColumn(key);
								}
							}
						}
						super.fillFormGroup(this.editData[key][index], (<FormArray>this.courseEligibilityForm.controls[key]).controls[index]);
					});
					if (!this.editData[key].length) {
						this.addExamsAcceptedRow();
					}
					break;
				default:
					super.fillFormGroup(this.editData[key], this.courseEligibilityForm.controls[key]);
					break;
			}
		}
	}

	ngOnDestroy() {
		for (let name in this.subscribeArray) {
			this.subscribeArray[name].unsubscribe();
		}
	}
}