import { Component, OnInit, OnChanges, Output, EventEmitter, Input, Injectable } from '@angular/core';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { SortArrayPipe } from '../pipes/arraypipes.pipe';
import { REACTIVE_FORM_DIRECTIVES }    from '@angular/forms';

@Component({
	selector: 'hierarchyFormField',
	templateUrl: '/public/angular/app/reusables/hierarchyFormField.template.html',
	directives: [REACTIVE_FORM_DIRECTIVES],
	pipes: [SortArrayPipe],
	styles: [`
        .ng-invalid {
            border: 1px solid #a94442;
        }
        .ng-pristine{
        	border: 1px solid #ccc;
        }
        .no-padding-left{
        	padding-left: 0 !important;
        	margin-left: 0 !important;
        }
    `],
})

@Injectable()
export class HierarchyFormField extends ListingBaseClass implements OnInit, OnChanges {
	streamList = [];
	substreamList = [];
	specializationList = [];
	processingEdit = false;
	@Input() hierarchy;
	@Input() index;
	@Input() showPrimary;
	@Input() hierarchyTree: any;
	@Input() hierarchyArray: any;
	@Input() showAll;
	@Input() showNone;
	@Input() numberOfHierarchies;
	@Input() defaultOption;
	@Input() selectedHierarchy;
	@Output() removeHierarchyEvent = new EventEmitter();

	ngOnInit() {
		this.streamList = this.getStreams();
		this.hierarchy.controls.streamId.valueChanges.subscribe(streamId => this.onStreamChange(streamId));
		this.hierarchy.controls.substreamId.valueChanges.subscribe(substreamId => this.onSubstreamChange(substreamId, this.hierarchy.controls.streamId.value));
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'selectedHierarchy' && changes[propName]['currentValue']) {
				this.fillFormGroupData(this.selectedHierarchy);
			}
		}
	}

	removeHierarchy(index) {
		this.removeHierarchyEvent.emit(index);
	}

	getStreams() {
		let streamList = [];
		let streams = this.hierarchyTree;
		for (let streamId in streams) {
			streamList.push({ 'stream_id': streamId, 'name': streams[streamId]['name'] });
		}
		return streamList;
	}

	onStreamChange(streamId) {
		this.substreamList = [];

		if (!this.processingEdit) {
			if (this.showNone && this.defaultOption == 'none' && streamId) {
				setTimeout(() => { this.hierarchy.controls.substreamId.updateValue('none', { emitEvent: true }); }, 0);
				setTimeout(() => { this.hierarchy.controls.specializationId.updateValue('none', { emitEvent: true }); }, 0);
			}
			else {
				setTimeout(() => { this.hierarchy.controls.substreamId.updateValue('', { emitEvent: true }); }, 0);
				setTimeout(() => { this.hierarchy.controls.specializationId.updateValue('', { emitEvent: true }); }, 0);
			}
		}
		this.specializationList = [];

		if (streamId) {
			let substreams = this.hierarchyTree[streamId]['substreams'];

			for (let substream in substreams) {
				this.substreamList.push({ 'substream_id': substreams[substream]['id'], 'name': substreams[substream]['name'] });
			}
			this.substreamList.sort();
			if (this.showAll) {
				if (this.substreamList.length) {
					this.substreamList.push({ 'substream_id': 'any', 'name': 'All Substreams' });
				}
			}
			if (this.showNone) {
				if (this.checkIfSubstreamNoneExists(streamId)) {
					this.substreamList.push({ 'substream_id': 'none', 'name': 'None' });
				}
			}
		}
	}

	onSubstreamChange(substreamId, streamId) {
		this.specializationList = [];

		if (!this.processingEdit) {
			if (this.showNone && this.defaultOption == 'none' && substreamId) {
				setTimeout(() => { this.hierarchy.controls.specializationId.updateValue('none', { emitEvent: true }); }, 0);
			}
			else {
				setTimeout(() => { this.hierarchy.controls.specializationId.updateValue('', { emitEvent: true }); }, 0);
			}
		}

		let data = {};
		if (substreamId) {
			if (substreamId == 'any') {
				let substreams = this.hierarchyTree[streamId]['substreams'];
				for (let sId in substreams) {
					for (let spId in substreams[sId]['specializations']) {
						data[spId] = { 'specialization_id': spId, 'name': substreams[sId]['specializations'][spId]['name'] };
					}
				}
			}
			else {
				let specializations;
				if (substreamId == 'none') {
					specializations = this.hierarchyTree[streamId]['specializations'];
				}
				else {
					specializations = this.hierarchyTree[streamId]['substreams'][substreamId]['specializations'];
				}
				for (let specialization in specializations) {
					data[specialization] = { 'specialization_id': specialization, 'name': specializations[specialization]['name'] };
				}
			}
			this.specializationList = Object.values(data);
			this.specializationList.sort();
			if (this.showAll) {
				if (this.specializationList.length) {
					this.specializationList.push({ 'specialization_id': 'any', 'name': 'All Specializations' });
				}
			}
			if (this.showNone) {
				if (this.checkIfSpecializationNoneExists(streamId, substreamId)) {
					this.specializationList.push({ 'specialization_id': 'none', 'name': 'None' });
				}
			}
		}
	}

	checkIfSubstreamNoneExists(streamId) {
		for (let hierarchy of this.hierarchyArray) {
			if (hierarchy['stream_id'] == streamId && hierarchy['substream_id'] == null)
				return true;
		}
		return false;
	}

	checkIfSpecializationNoneExists(streamId, substreamId) {
		for (let hierarchy of this.hierarchyArray) {
			if (hierarchy['stream_id'] == streamId && hierarchy['specialization_id'] == null) {
				if (substreamId == 'any' || (substreamId == 'none' && hierarchy['substream_id'] == null) || (hierarchy['substream_id'] == substreamId)) {
					return true;
				}
			}
		}
		return false;
	}

	fillFormGroupData(hierarchy) {
		this.processingEdit = true;
		if (hierarchy['substream_id'] == null || hierarchy['substream_id'] === 0) {
			hierarchy['substream_id'] = 'none';
		}
		if (hierarchy['specialization_id'] == null || hierarchy['specialization_id'] === 0) {
			hierarchy['specialization_id'] = 'none';
		}

		setTimeout(() => {
			this.hierarchy.controls.streamId.updateValue(hierarchy['stream_id'], { emitEvent: true });
			this.hierarchy.controls.substreamId.updateValue(hierarchy['substream_id'], { emitEvent: true });
			this.hierarchy.controls.specializationId.updateValue(hierarchy['specialization_id'], { emitEvent: true });
			if (this.showPrimary) {
				this.hierarchy.controls.is_primary.updateValue(hierarchy['is_primary'], { emitEvent: true });
			}
			this.processingEdit = false;
		}, 0);
	}
}