import { Component, OnInit, Output, EventEmitter, OnChanges, Input } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, FormArray, Validators, ValidatorFn } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { CoursePostingService } from '../services/coursePosting.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
    selector: 'courseMedia',
    templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseMedia.template.html'),
    directives: [REACTIVE_FORM_DIRECTIVES],
    providers: [CoursePostingService]

})
export class CourseMedia extends Posting implements OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group')
    public courseMedia: FormGroup;

    @Input() editData;

    constructor(
        private coursePosting: CoursePostingService
    ) {
		super();
    }
    medias;
    showMedia = false;
    mediaData = [];

	ngOnInit() {
		this.courseMedia.addControl('mediaCount', new FormControl(''));
		(<FormArray>(<FormGroup>this.courseMedia.root).controls['hierarchyForm']).controls['primary_course_hierarchy'].valueChanges
			.distinctUntilChanged()
			.subscribe((res) => {
				if (res) {
					let instituteString = res.split('_');
					let instituteId = instituteString[1];
					if (typeof instituteId == 'undefined') {
						this.resetMediaData();
					}
					this.coursePosting.getMediaMappedToInstitute(instituteId).subscribe(data => {
						if (data.length == 0) {
							this.resetMediaData();
							return false;
						}
						//this.showRCWidget = true;
						this.medias = data;
						this.mediaData = [];
						if (data.length) {
							this.updateMediaData(data);
						}
					});
				}
			});
	}

	resetMediaData() {
		for (let mediaId in this.courseMedia.controls) {
			if (mediaId != 'mediaCount') {
				if(this.courseMedia.controls[mediaId].value){
					this.courseMedia.markAsDirty();
				}
				this.courseMedia.removeControl(mediaId);
			} else {
				(<FormControl>this.courseMedia.controls['mediaCount']).updateValue("");
			}
		}
		this.editData = "";
		this.showMedia = false;
		this.mediaData = [];
	}

	updateMediaData(data) {
		if (this.courseMedia.controls['mediaCount'].value != "") {
			this.resetMediaData();
		}

		(<FormControl>this.courseMedia.controls['mediaCount']).updateValue(data.length);
		for (let item of data) {
			let temp = [];
			temp['thumb_url'] = item['media_thumb_url'];
			temp['media_id'] = item['media_id'];
			temp['media_title'] = item['media_title'];
			temp['media_type'] = item['media_type'];
			temp['media_url'] = item['media_url'];
			let currentMediaId = item['media_id'];
			this.mediaData.push(temp);
			if (this.courseMedia.contains(currentMediaId)) {
				this.courseMedia.removeControl(currentMediaId);
			}
			if (this.editData) {
				if (this.editData.indexOf(currentMediaId) != -1 || this.editData == -1) {
					this.courseMedia.addControl(currentMediaId, new FormControl(true));
				} else {
					this.courseMedia.addControl(currentMediaId, new FormControl(false));
				}
			} else {
				this.courseMedia.addControl(currentMediaId, new FormControl(false));
			}
		}
		this.showMedia = true;
	}

	selectAllMedia() {
		for (let item of this.medias) {
			if(!this.courseMedia.controls[item['media_id']].value){
				(<FormControl>this.courseMedia.controls[item['media_id']]).updateValue(true);
				this.courseMedia.markAsDirty();
			}
		}
	}

	removeAllMedia() {
		for (let item of this.medias) {
			if(this.courseMedia.controls[item['media_id']].value){
				(<FormControl>this.courseMedia.controls[item['media_id']]).updateValue(false);
				this.courseMedia.markAsDirty();
			}
		}
	}
}