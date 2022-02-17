import { Component, OnInit, OnChanges, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, FormArray, Validators, ValidatorFn } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { UploadMedia } from '../../../reusables/uploadMedia';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
    selector: 'courseBrochure',
    templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseBrochure.html'),
    directives: [REACTIVE_FORM_DIRECTIVES, MY_VALIDATORS, UploadMedia]
})
export class CourseBrochure extends Posting implements OnInit {

    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
    @Input() courseStaticData;
    @Input("group")
    public courseBrochureForm: FormGroup;
    courseBrochure = [];
    showLoader = 0;
    yearsValue;
    showBrochureYear = false;


    ngOnInit() {
        let year = new Date().getFullYear();
        this.courseBrochureForm.addControl('brochure_url', new FormControl(''));
        this.courseBrochureForm.addControl('brochure_year', new FormControl(year));
        this.courseBrochureForm.addControl('brochure_size', new FormControl(0));
        this.yearsValue = [
            { value: year - 1, label: year - 1 },
            { value: year, label: year },
            { value: year + 1, label: year + 1 }
        ];
    }

    handleBrochureUpload(brochureData) {
        if (brochureData == 'start') {
            this.showLoader = 1;
        }
        if (typeof brochureData === "string") {
            if (brochureData == 'no file') {
                this.clearBrochureData();
                this.showLoader = 0;
            }
        } else if (typeof brochureData['brochure_url'] !== 'undefined') {
            this.courseBrochureForm.markAsDirty();
            (<FormControl>this.courseBrochureForm.controls['brochure_url']).updateValue(brochureData.brochure_url);
            (<FormControl>this.courseBrochureForm.controls['brochure_size']).updateValue(parseInt(brochureData.brochure_size));
            this.showLoader = 0;
        }
        else {
            (<FormControl>this.courseBrochureForm.controls['brochure_url']).updateValue('');
            (<FormControl>this.courseBrochureForm.controls['brochure_size']).updateValue(0);
            this.showLoader = 0;
        }
    }

    resetBrochureYear() {
        let year = new Date().getFullYear();
        let control = <FormControl>this.courseBrochureForm.controls['brochure_year'];
        if (control) {
            control.updateValue(year, { emitEvent: true });
        }
    }

    clearBrochureData() {
        this.courseBrochureForm.markAsDirty();
        (<FormControl>this.courseBrochureForm.controls['brochure_url']).updateValue('');
        (<FormControl>this.courseBrochureForm.controls['brochure_size']).updateValue(0);

        this.resetBrochureYear();
        this.showBrochureYear = true;

        setTimeout(() => { this.showBrochureYear = false; }, 100);
    }
}