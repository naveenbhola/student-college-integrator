import { Component, OnInit, Input, Output, EventEmitter, OnChanges, ViewChild, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { CoursePostingService } from '../services/coursePosting.service';
import { MODAL_DIRECTIVES, BS_VIEW_PROVIDERS } from 'ng2-bootstrap/ng2-bootstrap';
import { ModalDirective } from '../../../Common/Components/modal/modal.component';
import { AddColumnTableComponent } from '../../../reusables/addColumnTableComponent';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseLocations',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/' + getHtmlVersion('courseLocations.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, MODAL_DIRECTIVES, AddColumnTableComponent],
	providers: [CoursePostingService, BS_VIEW_PROVIDERS]
})

export class CourseLocations extends Posting implements OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group') courseLocations: FormGroup;
	@Input() courseStaticData;
	@Input() editData;

	cLTemp: FormGroup;

	initializedForm = false;
	_addMore = [];
	_default = [];
	initialized = false;
	processingEdit = false;
	locationContactsUpdated = {};
	locationFeesUpdated = {};

	locationData = [];
	locationSelectedIds = [];
	feesEditTempData;
	constructor(
		private coursePosting: CoursePostingService
	) {
		super();
	}
	ngOnInit() {
		this.cLTemp = new FormGroup({});
		this.initializeFormData();
		(<FormArray>(<FormGroup>this.courseLocations.root).controls['hierarchyForm']).controls['primary_course_hierarchy'].valueChanges
			.distinctUntilChanged()
			.subscribe((res) => {
				if (res) {
					let instituteString = res.split('_');
					let instituteId = instituteString[1];
					if (typeof instituteId == 'undefined') {
						this.resetLocations();
						return false;
					}
					this.coursePosting.getCourseLocation(instituteId).subscribe(data => {
						if (data.length == 0) {
							console.log('Location not exist in institute');
							this.resetLocations();
							this.locationSelectedIds = [];
							return false;
						}
						this.initializedForm = true;
						(<FormControl>this.courseLocations.controls['locationsMain']).updateValue("");
						this.locations = data;
						this.updateLocationData(data);
						if (this.editData && this.locations) {
							this.processingEdit = true;
							for (let locObj of this.editData['locations']) {
								if (this.locationSelectedIds.indexOf(locObj['locationId']) == -1) {
									this.leftSelectedValues.push(locObj['locationId']);
								}
							}
							this.addItem();
							this.fillFormGroupData();
							setTimeout(() => { this.editData = null }, 1000);
						}
					});
				}
			});
		this.courseLocations.addControl('locations', new FormArray([]));
		this.courseLocations.addControl('locationsMain', new FormControl('', Validators.required));
		this.cLTemp.addControl('contact_details_temp', new FormGroup({
			'locationAddress': new FormControl('', Validators.maxLength(300)),
			'locationWebsite': new FormControl('', Validators.compose([Validators.maxLength(500), My_Custom_Validators.ValidateString('link')])),
			'locationCoordinatesLat': new FormControl('', Validators.compose([Validators.maxLength(10), My_Custom_Validators.ValidateString('geo_coordinates')])),
			'locationCoordinatesLong': new FormControl('', Validators.compose([Validators.maxLength(10), My_Custom_Validators.ValidateString('geo_coordinates')])),
			'locationAdmissionContactNumber': new FormControl('', Validators.compose([Validators.maxLength(20), My_Custom_Validators.ValidateString('phone_number')])),
			'locationGenericContactNumber': new FormControl('', Validators.compose([Validators.maxLength(20), My_Custom_Validators.ValidateString('phone_number')])),
			'locationAdmissionEmail': new FormControl('', Validators.compose([Validators.maxLength(150), My_Custom_Validators.ValidateString('email')])),
			'locationGenericEmail': new FormControl('', Validators.compose([Validators.maxLength(150), My_Custom_Validators.ValidateString('email')]))
			//			'contact_info': new FormControl()
		}));
		this.cLTemp.controls['contact_details_temp'].setValidators(My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['locationCoordinatesLat', 'locationCoordinatesLong'], 'myoptional': [] }));
	}

	initializeFormData() {
		for (let i of this.courseStaticData.categories) {
			if (i.type == 'default') {
				this._default.push(i);
			} else {
				this._addMore.push(i);
			}
		}
		this.cLTemp.addControl('fees_temp', new FormGroup({}));
		this.cLTemp.addControl('fees_disclaimer_temp', new FormControl());
		this.initialized = true;
	}

	leftSelectedValues = [];
	rightSelectedValues = [];
	rightBox = [];
	leftBox = [];
	locations;
	currentLocationObjectIndex;

	fillFormGroupData() {
		for (let key in this.editData) {
			switch (key) {
				case "locationsMain":
					super.fillFormGroup(this.editData[key], this.courseLocations.controls[key]);
					break;
				case "locations":
					// for (let loc in this.editData.locations) {
					// 	this.leftSelectedValues.push(this.editData.locations[loc].locationId);
					// }
					setTimeout(() => {
						for (let loc in this.editData.locations) {
							let locGrpIndex = this.getLocationIndex(this.editData.locations[loc].locationId);
							super.fillFormGroup(this.editData[key][loc]['contact_details'], (<FormArray>this.courseLocations.controls[key]).controls[locGrpIndex].controls['contact_details']);

							if (typeof this.editData[key][loc]['fees'] != 'undefined') {
								let feesGro = (<FormGroup>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[locGrpIndex]).controls['fees']);
								for (let feesObj in this.editData[key][loc]['fees']) {
									feesGro.addControl(feesObj, new FormControl(this.editData[key][loc]['fees'][feesObj]));
								}
							}

							super.fillFormGroup(this.editData[key][loc]['fees_disclaimer'], (<FormArray>this.courseLocations.controls[key]).controls[locGrpIndex].controls['fees_disclaimer']);
						}
						setTimeout(()=>{this.updateFlagsForTextChangeOnPageLoad()},1000);
						this.processingEdit = false;
					}, 500);
				default:
					// code...
					break;
			}
		}
	}

	updateFlagsForTextChangeOnPageLoad(){
		(<FormArray>this.courseLocations.controls['locations']).controls.forEach((control,index)=>{
			this.currentLocationObjectIndex = index;
			this.updateFlagsForTextChange('fees');
			this.updateFlagsForTextChange('contact_details');
		});
	}

	updateLocationData(locations) {
		this.leftBox = [];
		this.rightBox = [];
		this.locationData = [];
		this.locationSelectedIds = [];
		this.locationContactsUpdated = {};
		this.locationFeesUpdated ={};

		this.emptyFormArray((<FormArray>this.courseLocations.controls['locations']));

		for (let loc in locations) {
			let temp = [];
			temp['city_name'] = locations[loc]['city_name'];
			temp['locationId'] = loc;
			temp['isMain'] = locations[loc]['is_main'];
			this.locationData.push(loc);
			this.leftBox.push(temp);
		}
		//if (this.locationData.length == 1 && this.processingEdit) {
		setTimeout(() => {
			if (this.locationData.length == 1) {
				if (this.locationSelectedIds.indexOf(this.locationData[0]) == -1) {
					this.leftSelectedValues.push(this.locationData[0]);
				}
				this.addItem();
			}
		}, 1500);
	}

	changeLeft(options) {
		this.leftSelectedValues = Array.apply(null, options)
			.filter(option => option.selected)
			.map(option => option.value);
	}

	changeRight(options) {
		this.rightSelectedValues = Array.apply(null, options)
			.filter(option => option.selected)
			.map(option => option.value);
	}

	addItem() {
		if(+((<FormGroup>this.courseLocations.root).controls['courseId'].value)){
			let packType = +((<FormGroup>this.courseLocations.root).controls['packType'].value);
			if(packType == 1 && this.leftSelectedValues.length+(<FormArray>this.courseLocations.controls['locations']).length > 1){
				alert('Cannot add more than one location for single gold listing');
				return;
			}
		}
		for (let item of this.leftSelectedValues) {
			let temp = [];
			this.locationSelectedIds.push(item);
			temp['city_name'] = this.locations[item]['city_name'];
			temp['locationId'] = item;
			temp['isMain'] = this.locations[item]['is_main'];
			this.rightBox.push(temp);

			let locationGroup = new FormGroup({});
			locationGroup.addControl('locationId', new FormControl(item));
			if (this.locationData.length == 1) {
				(<FormControl>this.courseLocations.controls['locationsMain']).updateValue(item);
			} else {
				if (!(<FormControl>this.courseLocations.controls['locationsMain']).value) {
					if (this.locations[item]['is_main'] == true) {
						(<FormControl>this.courseLocations.controls['locationsMain']).updateValue(item);
					}
				}
			}

			locationGroup.addControl('contact_details', new FormGroup({
				'locationAddress': new FormControl(),
				'locationWebsite': new FormControl(),
				'locationCoordinatesLat': new FormControl(),
				'locationCoordinatesLong': new FormControl(),
				'locationAdmissionContactNumber': new FormControl(),
				'locationGenericContactNumber': new FormControl(),
				'locationAdmissionEmail': new FormControl(),
				'locationGenericEmail': new FormControl()
			}));
			locationGroup.addControl('fees', new FormGroup({}));
			locationGroup.addControl('fees_disclaimer', new FormControl());
			(<FormArray>this.courseLocations.controls['locations']).push(locationGroup);

			if (!this.processingEdit) {
				this.courseLocations.markAsDirty();
			}

			for (let x in this.leftBox) {
				if (this.leftBox[x]['locationId'] == item) {
					this.leftBox.splice(+x, 1);
				}
			}
		}

		this.leftSelectedValues = [];
	}

	removeItem() {
		for (let item of this.rightSelectedValues) {
			let temp = [];
			temp['city_name'] = this.locations[item]['city_name'];
			temp['locationId'] = item;
			temp['isMain'] = this.locations[item]['is_main'];
			this.leftBox.push(temp);

			let index;
			for (let x in (<FormArray>this.courseLocations.controls['locations']).controls) {
				if ((<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[x]).controls['locationId'].value == item) {
					index = x;
				}
			}

			if (index) {
				this.locationContactsUpdated[(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[index]).controls['locationId'].value] = false;
				this.locationFeesUpdated[(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[index]).controls['locationId'].value] = false;
				(<FormArray>this.courseLocations.controls['locations']).removeAt(index);
			}

			// removing HeadOffice
			if (this.courseLocations.controls['locationsMain'].value == item) {
				(<FormControl>this.courseLocations.controls['locationsMain']).updateValue('');
			}

			for (let x in this.rightBox) {
				if (this.rightBox[x]['locationId'] == item) {
					this.rightBox.splice(+x, 1);
				}
			}

			let locationSelectedIdIndex = this.locationSelectedIds.indexOf(item);
			if (locationSelectedIdIndex > -1) {
				this.locationSelectedIds.splice(locationSelectedIdIndex, 1);
			}
		}
		if (!this.processingEdit) {
			this.courseLocations.markAsDirty();
		}
		this.rightSelectedValues = [];
	}

	removeSelectedItem(item, index) {
		this.rightSelectedValues = [];
		this.rightSelectedValues.push(item);
		this.removeItem();
	}

	getLocationIndex(locationId) {
		for (let x in (<FormArray>this.courseLocations.controls['locations']).controls) {
			if ((<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[x]).controls['locationId'].value == locationId) {
				return x;
			}
		}
	}


	@ViewChild('courseLocModal') public courseLocModal: ModalDirective;
	public showCourseLocationModal(): void {
		this.courseLocModal.show();
	}

	public hideCourseLocationModal(): void {
		this.courseLocModal.hide();
		for (var name in (<FormGroup>this.cLTemp.controls['contact_details_temp']).controls) {
			let tempControl = (<FormGroup>this.cLTemp.controls['contact_details_temp']).controls[name];
			(<FormControl>tempControl).updateValue(null);
			(<FormControl>tempControl).setErrors(null);
		}
	}


	@ViewChild('courseLocFeesModal') public courseLocFeesModal: ModalDirective;
	public showCourseLocationFeesModal(): void {
		this.courseLocFeesModal.show();
	}

	public hideCourseLocationFeesModal(): void {
		this.courseLocFeesModal.hide();
		this.feesEditTempData = null;
		for (var name in (<FormGroup>this.cLTemp.controls['fees_temp']).controls) {
			let tempControl = (<FormGroup>this.cLTemp.controls['fees_temp']).controls[name];
			(<FormControl>tempControl).updateValue(null);
			(<FormControl>tempControl).setErrors(null);
		}
	}

	showItemModal(locationId, controlName) {
		this.currentLocationObjectIndex = this.getLocationIndex(locationId);
		let contactGroup = (<FormGroup>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]).controls[controlName]);
		if (controlName == 'fees') {
			this.feesEditTempData = contactGroup.value;
		}
		else {
			for (var name in contactGroup.controls) {
				let tempValue = contactGroup.controls[name].value;
				if (tempValue) {
					(<FormControl>(<FormGroup>this.cLTemp.controls[controlName + '_temp']).controls[name]).updateValue(tempValue);
				}
			}
		}
		if (controlName == 'contact_details') {
			setTimeout(() => { this.showCourseLocationModal() }, 5);
		} else {
			let feesDisclaimerValue = (<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]).controls['fees_disclaimer'].value;
			(<FormControl>this.cLTemp.controls['fees_disclaimer_temp']).updateValue(feesDisclaimerValue);
			setTimeout(() => { this.showCourseLocationFeesModal() }, 5);
		}

	}

	updateModal(controlName) {
		for (var name in (<FormGroup>this.cLTemp.controls[controlName + '_temp']).controls) {
			let tempControl = (<FormGroup>this.cLTemp.controls[controlName + '_temp']).controls[name];

			let mainModalFormGroup = (<FormGroup>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]).controls[controlName]);
			if (tempControl.value) {
				if (mainModalFormGroup.contains(name)) {

					let previousValue = mainModalFormGroup.controls[name].value;
					(<FormControl>mainModalFormGroup.controls[name]).updateValue(tempControl.value);
					if (previousValue != mainModalFormGroup.controls[name].value) {
						mainModalFormGroup.markAsDirty();
					}

					(<FormControl>tempControl).updateValue('');
					(<FormControl>tempControl).setErrors(null);
				} else {
					mainModalFormGroup.markAsDirty();
					mainModalFormGroup.addControl(name, new FormControl(tempControl.value));
				}
			} else {
				if (mainModalFormGroup.contains(name)) {
					if (mainModalFormGroup.controls[name].value) {
						mainModalFormGroup.markAsDirty();
					}
					(<FormControl>mainModalFormGroup.controls[name]).updateValue('');
				} else {
					mainModalFormGroup.addControl(name, new FormControl(""));
				}
			}
		}
		if(controlName == 'fees'){
			let disclaimer = this.cLTemp.controls['fees_disclaimer_temp'];
			let FeesFormGroup = <FormGroup>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]);
			if (FeesFormGroup.contains('fees_disclaimer')) {
				let previousValue = FeesFormGroup.controls['fees_disclaimer'].value;
				if(previousValue != disclaimer.value){
					FeesFormGroup.controls['fees'].markAsDirty();
				}
				(<FormControl>FeesFormGroup.controls['fees_disclaimer']).updateValue(disclaimer.value);

			} else {
				if(disclaimer.value){
					FeesFormGroup.controls['fees'].markAsDirty();
				}
				FeesFormGroup.addControl('fees_disclaimer', new FormControl(disclaimer.value));
			}
		}
		// setTimeout(()=>{this.updateFlagsForTextChange(controlName);},0);
		this.updateFlagsForTextChange(controlName);
		if (controlName == 'contact_details') {
			this.hideCourseLocationModal();
		} else {
			this.hideCourseLocationFeesModal();
		}
	}

	updateFlagsForTextChange(controlName){
		let group = <FormGroup>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]).controls[controlName].value;
		let found = false;
		for(let key in group){
			if(group[key] || (typeof group[key] == 'string' && group[key].trim())){
				found = true;
				break;
			}
		}
		let value = (<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[this.currentLocationObjectIndex]).controls['locationId'].value
		if(found){
			if(controlName == 'fees'){
				this.locationFeesUpdated[value] = true;
			}
			else{
				this.locationContactsUpdated[value] = true;
			}
		}
		else{
			if(controlName == 'fees'){
				this.locationFeesUpdated[value] = false;
			}
			else{
				this.locationContactsUpdated[value] = false;
			}
		}
	}

	updateMainLocation(locationId) {
		for (let x in (<FormArray>this.courseLocations.controls['locations']).controls) {
			if ((<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[x]).controls['locationId'].value == locationId) {
				(<FormControl>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[x]).controls['locationMain']).updateValue(true);
			} else {
				(<FormControl>(<FormGroup>(<FormArray>this.courseLocations.controls['locations']).controls[x]).controls['locationMain']).updateValue(false);
			}
		}
	}

	resetLocations() {
		this.leftBox = [];
		this.rightBox = [];
		this.locationData = [];
		this.locationSelectedIds = [];
		this.locationContactsUpdated = {};
		this.locationFeesUpdated = {};
		(<FormControl>this.courseLocations.controls['locationsMain']).updateValue("");
		this.emptyFormArray((<FormArray>this.courseLocations.controls['locations']));
	}
}