import { Component, OnInit, OnChanges, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, FormArray, Validators } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { CourseExamsCutOffTable } from './courseExamsCutOffTable';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { My_Custom_Validators } from '../../../reusables/Validators';

@Component({
	selector: 'course-exams-cut-off',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseExamsCutOff.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, MY_VALIDATORS, CourseExamsCutOffTable]
})
export class CourseExamsCutOff extends Posting implements OnInit, OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group')
    public courseExamCutOff: FormArray;
    @Input() course12thCutOff;
    @Input() mainForm;
    @Input() courseStaticData;
    @Input() editData;
    _addMore = [];
    _default = [];
    initialized = false;
    roundsValue;
    refreshView = false;
    yearsValue;
    examLookupArr = {};
    examUnitLookupArr = {};
    locationData;
    processingEdit = false;
	ngOnInit() {
		this.opened = false;
		let year = new Date().getFullYear();
		this.yearsValue = [{ value: year, label: year },
			{ value: year - 1, label: year - 1 },
			{ value: year - 2, label: year - 2 }];
		this.roundsValue = [{ value: 0, label: "Not Aplicable" },
			{ value: 1, label: "Applicable" }
		];
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if(propName == 'courseStaticData' && changes[propName]['currentValue']){
				for (let item of this.courseStaticData['exam_list']) {
					this.examLookupArr[item.value] = item.label;
				}
				this.addExamCutOffTable();
				this.generateFormControls12thCutOff();
				for (let i of this.courseStaticData.categories) {
					if (i.type == 'default') {
						this._default.push(i);
					}
					else {
						this._addMore.push(i);
					}
				}
				this.locationData = this.courseStaticData.locationData;
				this.initialized = true;

				(<FormArray>(<FormGroup>this.courseExamCutOff.root).controls['courseEligibilityForm']).controls['exams_accepted'].valueChanges.subscribe((res) => {
					if(this.processingEdit){
						return;
					}
					let exam_list = [];
					for (let eligibilityExams of res) {
						if(eligibilityExams['exam_name']){
							let found = false;
							for (let examCutOff of this.courseExamCutOff.controls) {
								if (examCutOff.value['exam_id'] == eligibilityExams['exam_name']) {
									found = examCutOff.value['exam_id'];
								}
								if (eligibilityExams['exam_name'] == 'other' && examCutOff.value['exam_id'] == "other$$" + eligibilityExams['custom_exam']) {
									found = examCutOff.value['exam_id'];
								}
							}
							if(found){
								exam_list.push(found);
								for (let examCutOff of this.courseExamCutOff.controls) {
									if(examCutOff.value['exam_id'] == found){
										if (examCutOff.value['exam_unit'] && eligibilityExams['exam_unit'] && (examCutOff.value['exam_unit'] != eligibilityExams['exam_unit'])) {
											// (<FormControl>(<FormGroup>examCutOff).controls['exam_unit']).updateValue(eligibilityExams['exam_unit'],{emitEvent:true});
											// this.courseExamCutOff.markAsDirty();
											// for (let item of (<FormArray>(<FormGroup>examCutOff).controls['cutOffData']).controls) {
											// 	for (let round of (<FormArray>(<FormGroup>item).controls['round_table_arr']).controls) {
											// 		for (let quota in (<FormGroup>round).controls) {
											// 			if (round.value[quota] != '' && round.value[quota]) {
											// 				(<FormGroup>round).controls[quota].updateValueAndValidity();
											// 			}
											// 		}
											// 	}
											// }
										}
									}
								}
							}
						}
					}

					this.courseExamCutOff.value.forEach((val,index)=>{
						if(val['exam_id'] && exam_list.indexOf(val['exam_id']) == -1){
							setTimeout(()=>{this.removeExam(index);},100);
						}
					});

				});
			}
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.opened = typeof this.editData != 'undefined' && (
					(typeof this.editData.course12thCutOff != 'undefined' && this.editData.course12thCutOff.length > 0) ||
					(typeof this.editData.courseExamCutOff != 'undefined' && this.editData.courseExamCutOff.length > 0)
				);
				this.fillFormGroupData();
			}
		}
	}

	generateFormControls12thCutOff() {
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('cutOff12th') }));
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('science') }));
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('commerce') }));
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('humanities') }));
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('pcm') }));
		this.course12thCutOff.push(new FormGroup({ 'type': new FormControl('pmbbt') }));
	}

	addExamCutOffTable() {
		(<FormArray>this.courseExamCutOff).push(
												new FormGroup({
				'exam_id': new FormControl(''),
				'exam_out_of': new FormControl('', My_Custom_Validators.validateWholeNumber(-1)),
				'exam_unit': new FormControl(''),
				'exam_year': new FormControl(''),
				'related_states': new FormControl(),
				'round_applicable': new FormControl(0),
				'cutOffData': new FormArray([this.returnRound()])
			})
		);
		let length = this.courseExamCutOff.controls.length;
		(<FormArray>this.courseExamCutOff.controls[length - 1]).controls['exam_out_of'].valueChanges.subscribe((res) => {
			let length = this.courseExamCutOff.controls.length;
			for (let item of (<FormArray>this.courseExamCutOff.controls[length - 1]).controls['cutOffData'].controls) {
				for (let round of item.controls['round_table_arr'].controls) {
					for (let quota in round.controls) {
						if (round.value[quota] != '' && round.value[quota]) {
							round.controls[quota].updateValueAndValidity();
						}
					}
				}
			}
		})
		return false;
	}

	returnRound() {
		return new FormGroup({
			'round_table_arr': new FormArray([new FormGroup({ 'type': new FormControl('all_india') }),
				new FormGroup({ 'type': new FormControl('home_state') }),
				new FormGroup({ 'type': new FormControl('other_state') }),
				new FormGroup({ 'type': new FormControl('related_states') })
			])
		});
	}

	addRoundCutOffData(index) {
		(<FormArray>(<FormGroup>this.courseExamCutOff.controls[index]).controls['cutOffData']).push(this.returnRound());
		return false;
	}

	updateExamUnitLookUpArr(index) {
		setTimeout(() => {
			if ((<FormGroup>this.courseExamCutOff.controls[index]).value['exam_unit'] == 'percentile' || (<FormGroup>this.courseExamCutOff.controls[index]).value['exam_unit'] == 'percentage') {
				(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['exam_out_of']).updateValue('100',{emitEvent:true});
			}
			else if ((<FormGroup>this.courseExamCutOff.controls[index]).value['exam_unit']) {
				(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['exam_out_of']).updateValue('',{emitEvent:true});
			}
		}, 10);
	}

	handleExamChange(index) {
		setTimeout(() => {
			let examId = this.courseExamCutOff.controls[index].value['exam_id'];
			if (examId != '0' || examId != '') {
				for (let eligibilityExam of this.mainForm.controls['courseEligibilityForm'].controls['exams_accepted'].controls) {
					if (eligibilityExam.value['exam_name'] == examId || eligibilityExam.value['exam_name'] + '$$' + eligibilityExam.value['custom_exam'] == examId) {
						(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['exam_unit']).updateValue(eligibilityExam.value['exam_unit'],{emitEvent:true});
						switch (eligibilityExam.value['exam_unit']) {
							case "percentile":
							case "percentage":
								(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['exam_out_of']).updateValue('100',{emitEvent:true});
								break;
							default:
								(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['exam_out_of']).updateValue('',{emitEvent:true});
								break;
						}
					}
				}
			}
		}, 10);
	}

	removeRoundTable(removedData, round, index) {
		if (removedData.isRemoved) {
			(<FormArray>(<FormGroup>this.courseExamCutOff.controls[index]).controls['cutOffData']).removeAt(round);
			this.courseExamCutOff.markAsDirty();
		}
	}

	removeExam(i) {
		(<FormArray>this.courseExamCutOff).removeAt(i);
		this.courseExamCutOff.markAsDirty();
		return false;
	}

	handleExamOutOfChange(index) {
		setTimeout(() => {
			if (this.courseExamCutOff.controls[index]) {
				(<FormArray>(<FormGroup>this.courseExamCutOff.controls[index]).controls['cutOffData']).updateValueAndValidity();
			}
		}, 100);
	}
	//removing exam rounds keeping only 1
	handleRoundChange(index) {
		setTimeout(() => {
			if ((<FormGroup>this.courseExamCutOff.controls[index]).value['round_applicable'] == 0) {
				let maxLength = (<FormArray>(<FormGroup>this.courseExamCutOff.controls[index]).controls['cutOffData']).controls.length;
				if (maxLength > 1) {
					for (let i = maxLength; i > 0; i--) {
						(<FormArray>(<FormGroup>this.courseExamCutOff.controls[index]).controls['cutOffData']).removeAt(i);
					}
				}
			}
		}, 10);
		return false;
	}

	get enableAddExamButton() {
		let returnFlag = true;
		for (let control of this.courseExamCutOff.controls) {
			if (!control.value['exam_id'] || control.value['exam_id'] == '') {
				returnFlag = false;
				break;
			}
		}
		return returnFlag;
	}

	fillFormGroupData() {
		this.processingEdit = true;
		let cutOffData = this.editData['courseExamCutOff'];
		if (!cutOffData) {
			return false;
		}

		cutOffData.forEach((exam, index) => {
			if (index > 0) {
				this.addExamCutOffTable();
			}

			for (let control in exam) {
				switch (control) {
					case "cutOffData":
						let roundIndex = 0;
						for (let rounds in exam[control]['round_table_arr']) {
							if (roundIndex > 0) {
								this.addRoundCutOffData(index);
							}
							roundIndex++;
						}
						break;
					case "related_states":
						(<FormControl>(<FormGroup>this.courseExamCutOff.controls[index]).controls['related_states']).updateValue(exam[control], { emitEvent: true });
						break;
					default:
						setTimeout(()=>{super.fillFormGroup(exam[control], (<FormGroup>this.courseExamCutOff.controls[index]).controls[control]);},0);
						break;
				}
			}
		});
		setTimeout(()=>{this.processingEdit = false;},2000);
	}
}