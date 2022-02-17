import { Component, OnDestroy, OnInit, OnChanges, Input, Output, Injectable, EventEmitter } from '@angular/core';
import { HierarchyFormField } from './hierarchyFormField';
import { FormGroup, FormControl, FormArray, REACTIVE_FORM_DIRECTIVES, Validators }    from '@angular/forms';
import { Observable }     from 'rxjs/Rx';

@Component({
	selector: 'hierarchyFormGroup',
	templateUrl: '/public/angular/app/reusables/hierarchyFormGroup.template.html',
	directives: [HierarchyFormField, REACTIVE_FORM_DIRECTIVES]
})

@Injectable()
export class HierarchyFormGroup implements OnChanges,OnDestroy,OnInit {

	@Input() hierarchyTree: any;
	@Input() hierarchyArray: any;
	@Input() hierarchyMapping: any;
	@Input() mode;
	@Input() selectedHierarchies = [];
	@Input() showPrimary = false;
	@Input() showAll = true;
	@Input() showNone = true;
	@Input() defaultOption = '';
	@Output() formGroupInitialized = new EventEmitter();

	hierarchySubscribeArray = {};
	lastPrimaryIndex;

	ngOnInit(){
		if(this.mode == 'add'){
			this.addItemToHierarchy();
		}
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'selectedHierarchies' && changes['selectedHierarchies']['currentValue']) {
				if (this.hierarchyMapping.length) {
					this.emptyFormArray(this.hierarchyMapping);
				}
				for (let i in this.selectedHierarchies) {
					this.addItemToHierarchy();
				}
				setTimeout(()=>{this.selectedHierarchies = null;},2500);
			}
			// if (propName == 'hierarchyTree' && changes['hierarchyTree']['currentValue']) {
			// 	if (this.mode == 'add') {
			// 		this.addItemToHierarchy();
			// 	}
			// }
		}
	}

	addItemToHierarchy() {
		setTimeout(() => {
			let item = new FormGroup({
				'streamId': new FormControl("", Validators.required),
				'substreamId': new FormControl("", Validators.required),
				'specializationId': new FormControl("", Validators.required),
			});
			if (this.showPrimary) {
				if (this.hierarchyMapping.length) {
					item.addControl('is_primary', new FormControl('0'));
				}
				else {
					item.addControl('is_primary', new FormControl('1'));
				}
			}
			this.hierarchyMapping.push(item);
			if (this.showPrimary) {
				this.reSubscribePrimaryHierarchy();
			}
		}, 0);
	}

	removeHierarchy(index, clicked = true) {
		let setPrimary = false; let newIndex;
		if (this.showPrimary && clicked && this.hierarchyMapping.controls[index].controls['is_primary'].value == '1') {
			setPrimary = true;
			newIndex = index ? index - 1 : index;
			this.unsubscribePrimaries();
		}

		this.hierarchyMapping.removeAt(index);
		if (setPrimary) {
			this.reSubscribePrimaryHierarchy();
			if (this.hierarchyMapping.controls[newIndex]) {
				this.hierarchyMapping.controls[newIndex].controls['is_primary'].updateValue('1', { emitEvent: true });
			}
		}

		if (clicked) {
			this.hierarchyMapping.markAsDirty();
		}
	}

	reSubscribePrimaryHierarchy() {
		this.unsubscribePrimaries();
		for (let control in this.hierarchyMapping.controls) {
			this.hierarchySubscribeArray[control] = this.hierarchyMapping.controls[control].controls['is_primary'].valueChanges.subscribe((data) => {
				if (data == '1') {
					this.markHierarchyAsPrimary(control);
				}
			});
		}
	}

	markHierarchyAsPrimary(index) {
		if (index != this.lastPrimaryIndex) {
			for (let control in this.hierarchyMapping.controls) {
				if (index != control) {
					setTimeout(() => { this.hierarchyMapping.controls[control].controls['is_primary'].updateValue('0', { emitEvent: true }); }, 0);
				}
			}
		}
	}

	unsubscribePrimaries() {
		for (let name in this.hierarchySubscribeArray) {
			this.hierarchySubscribeArray[name].unsubscribe();
		}
	}

	emptyFormArray(mappingInfo: FormArray) {
	    while (mappingInfo.length != 0) {
	        mappingInfo.removeAt(mappingInfo.length - 1);
	    }
	}

	ngOnDestroy(){
		if(this.hierarchyMapping && this.hierarchyMapping.length){
			this.emptyFormArray(this.hierarchyMapping);
		}
	}
}