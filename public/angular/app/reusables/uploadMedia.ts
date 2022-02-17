import {Component, Input, Output, EventEmitter, ElementRef, Injectable, OnInit} from '@angular/core';
import { FileUploadService } from '../services/file-upload.service';


@Component({
	selector: 'shiksha-upload',
	template: `
		<div [mysize]="'3'" style="padding-top:8px;"> 
			<input name="mediaFile{{randomNumber}}" type="file" (change)="uploadMedia(mediaRef)" #mediaRef/>
            <span class="help-block text-danger">{{mediaFileErrMsg}}</span>
        </div>
        <div [mysize]="'3'" style="padding-top:8px">
	        <div class="progress" *ngIf="uploadProgress > 0 && !mediaFileErrMsg">
	            <div class="progress-bar progress-bar-danger" data-transitiongoal="25" aria-valuenow="100" [style.width]="uploadProgress + '%'">{{uploadProgress}}</div>
	        </div>
	    </div> 	    
		`,
	providers: [FileUploadService],


})



export class UploadMedia implements OnInit {
	@Output() mediaResponse = new EventEmitter();

	@Input() uploadXhrUrl = '';
	@Input() showProgressBar = 1;

	uploadProgress = 0;
	randomNumber: number;
	mediaFileErrMsg = '';
	uploadArr = [];

	constructor(public fileUploadService: FileUploadService) {
		this.fileUploadService.progress$.subscribe(
			data => {
				this.uploadProgress = data;
			});
	}

	ngOnInit() {
		this.randomNumber = Math.floor(Math.random() * 6) + 1;
	}


	uploadMedia(input) {
		this.mediaResponse.emit("start");
		if (input.files.length == 0) {
			this.mediaResponse.emit("no file");
			return false;
		}
		this.uploadArr = [];
		this.mediaFileErrMsg = '';
		this.uploadProgress = 0;
		let FileList: FileList = input.files;
		for (let i = 0, length = FileList.length; i < length; i++) {
			this.uploadArr.push(FileList.item(i));
		}
		this.fileUploadService.upload(this.uploadXhrUrl, this.uploadArr)
			.then(this.uploadMediaCallback.bind(this))
			.catch(function(err) {
				console.log(err);
				alert('Unable to upload logo due to network issue');
			})
	}
	uploadMediaCallback(response) {
		if (response.hasOwnProperty('error')) {
			this.mediaFileErrMsg = response.error.msg;
		}
		this.mediaResponse.emit(response);
	}
}