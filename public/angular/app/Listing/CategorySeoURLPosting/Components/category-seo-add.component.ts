import { Component, OnInit, OnDestroy } from '@angular/core';
import { CategorySeoService } from '../../../services/category-seo.service';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { RangeArrayPipe } from '../../../pipes/arraypipes.pipe';
import { ListingBaseService } from '../../../services/listingbase.service';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormArray, FormControl, Validator, ValidatorFn, Validators } from '@angular/forms';
import { Router, ROUTER_DIRECTIVES, ActivatedRoute } from '@angular/router';

@Component({
    selector: 'categorySeoAdd',
    templateUrl: '/public/angular/app/Listing/CategorySeoURLPosting/Views/category-seo-add.component.html',
    providers: [CategorySeoService],
    styles: [`
        .mandatory-field {
            border: 1px solid #c00;
        }
        .overlay-window{{
            width:1100px;
        }
    `],
    directives: [REACTIVE_FORM_DIRECTIVES, ROUTER_DIRECTIVES],
    pipes: [RangeArrayPipe]
})

export class CategorySeoAddComponent implements OnInit {
    baseURL: string = '';
    streamList: any = [];
    baseBreadcrumb: string = '';
    customURL: string = '';
    categorySeoForm: FormGroup;
    mode: string = 'Add';
    action: string = 'Add';
    deletionMethod: string = '';
    substreamList: any = [];
    basecourseList: any = [];
    popularCourses: any = [];
    courseCredentials: any = [];
    educationTypes: any = [];
    deliveryMethods: any = [];
    similarURLs: any = [];
    ruleId: string = '';
    usedPlaceholders: any = [];
    displayedPlaceholders: any = [];
    hierarchyTree: any = [];


    formSubmit: boolean = false;
    goStatus: boolean = false;
    substreamListDisabled: boolean = true;

    urlInvalid: boolean = false;
    showOverlay: boolean = false;
    perform301: boolean = false;
    showCustom: boolean = false;
    matchingIndex: number = -1;
    hideCloseButton: boolean = false;

    placeholders: any = {
        streamList: '[stream]',
        substreamList: '[substream]',
        popularCourses: '[course]',
        courseCredentials: '[credential]',
        educationTypes: '[education_type]',
        deliveryMethods: '[delivery_method]',
        baseCourseSelection: '[course]',
        specializationSelection: '[specialization]',
        courseLocationSelection: '[location]',
        examSelection: '[exam]'
    };

    placeholderIdentifier = ['url', 'title', 'breadcrumb', 'description', 'h1Desktop', 'h1Mobile'];
    streamOrCourseNameInUrl = /^([a-z0-9\-\ ,\(\)\&])+\//gi;
    streamOrCourseNameInBreadcrumb = /^([a-z0-9\-\ ,\(\)\&])+\>/gi;
    streamOrSubstreamNameInUrl = /^([a-z0-9\-\ ,\(\)\&])+(\/([a-z0-9\-\ ,\(\)\&])+){0,1}\//gi;
    streamOrSubstreamNameInBreadcrumb = /^([a-z0-9\-\ ,\(\)\&])+(\>([a-z0-9\-\ ,\(\)\&])+){0,1}\>/gi;

    constructor(private categorySeoService: CategorySeoService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) {
        this.baseURL = this.categorySeoService.getBaseURL();
    }

    public generateFormControls() {
        this.categorySeoForm = new FormGroup({
            'url': new FormControl("", Validators.required),
            'breadcrumb': new FormControl("", Validators.required),
            'title': new FormControl("", Validators.required),
            'description': new FormControl("", Validators.required),
            'h1Desktop': new FormControl("", Validators.required),
            'h1Mobile': new FormControl("", Validators.required),
            'streamList': new FormControl("none"),
            'substreamList': new FormControl("none"),
            'popularCourses': new FormControl("none"),
            'courseCredentials': new FormControl("none"),
            'educationTypes': new FormControl("none"),
            'deliveryMethods': new FormControl("none"),
            'baseCourseSelection': new FormControl("none"),
            'specializationSelection': new FormControl("none"),
            'courseLocationSelection': new FormControl("any"),
            'examSelection': new FormControl("none")
        });
        this.categorySeoForm.setValidators(My_Custom_Validators.ValidateCategorySeoForm(this));
    }

    ngOnInit() {
        this.loader('show');
        this.generateFormControls();
        this.streamList = Object.values(this.route.snapshot.data['streamList']);
        this.popularCourses = Object.values(this.route.snapshot.data['popularCoursesList']);
        this.populatBaseCourses();
        this.getCourseAttributes();

        this.hierarchyTree = this.route.snapshot.data['hierarchyTree'];
        this.categorySeoForm.controls['streamList'].valueChanges.subscribe(streamId => {

            this.substreamList = [];
            if (streamId != 'none') { // Stream id passed
                (<FormControl>this.categorySeoForm.controls['popularCourses']).updateValue('none');
                this.substreamListDisabled = false;
                if (this.hierarchyTree[streamId]) { // Stream exists
                    this.baseURL = this.categorySeoService.getBaseURL();

                    let subStreams = this.hierarchyTree[streamId]['substreams'];

                    if (Object.keys(subStreams).length > 0) { // Sub-stream Exists
                        for (let substream in subStreams) {
                            this.substreamList.push({
                                'substream_id': subStreams[substream]['id'],
                                'name': subStreams[substream]['url_name'] ? subStreams[substream]['url_name'] : subStreams[substream]['name']
                            });
                        }
                        this.substreamListDisabled = false;
                        (<FormControl>this.categorySeoForm.controls['substreamList']).updateValue('none');
                    } else { // Stream having no substream
                        this.substreamListDisabled = true;
                        (<FormControl>this.categorySeoForm.controls['substreamList']).updateValue('none');
                    }

                    let substreamChange = this.categorySeoForm.controls['substreamList'].valueChanges.subscribe(
                        substreamId => {
                            let substreamName: string = '';
                            if (subStreams[substreamId]) {
                                substreamName = subStreams[substreamId]['name'] ? subStreams[substreamId]['url_name'] : subStreams[substreamId]['name'];
                            } else if (substreamId == 'any') {
                                substreamName = '[substream]';
                            }
                            this.updateStaticPart(this.hierarchyTree[streamId]['name'], substreamName, '');
                        }
                    );
                    this.updateStaticPart(this.hierarchyTree[streamId]['name'], '', '');
                } else if (streamId == 'any') {
                    this.updateStaticPart('[stream]', '', '');
                    this.substreamListDisabled = false;
                    let substreamChange = this.categorySeoForm.controls['substreamList'].valueChanges.subscribe(
                        substreamId => {
                            let substreamName: string = '';
                            if (substreamId == 'any') {
                                substreamName = '[substream]';
                            }
                            this.updateStaticPart('[stream]', substreamName, '');
                        }
                    );
                }
                // else { // Stream having no substream
                //     console.log('here ');
                //     let thisStream = this.streamList.filter(
                //             key => key.stream_id == streamId
                //     )[0];
                //     console.log(thisStream.name);
                //     this.updateStaticPart(thisStream.name, '', '');

                //     this.substreamListDisabled = true;
                //     this.categorySeoForm.controls.substreamList.updateValue('');
                // }

            } else { // Selected stream is not valid
                this.substreamListDisabled = true;
                (<FormControl>this.categorySeoForm.controls['substreamList']).updateValue('none');
                this.updateStaticPart('', '', '');
            }
        }); // obtain the streamid and perform the required functionality upon the change event

        for (let controlName in this.categorySeoForm.controls) {
            if (this.placeholderIdentifier.indexOf(controlName) < 0) {
                this.categorySeoForm.controls[controlName].valueChanges.subscribe(() => {
                    if (this.goStatus) {
                        this.updateFormValue('', this);
                        this.goStatus = false;
                    }
                });
            }
        }

        this.categorySeoForm.controls['baseCourseSelection'].valueChanges.subscribe((basecourseSelection) => {
            (<FormControl>this.categorySeoForm.controls['courseCredentials']).updateValue('none');
        });

        this.categorySeoForm.controls['h1Desktop'].valueChanges.subscribe((h1Desktop) => {
            (<FormControl>this.categorySeoForm.controls['h1Mobile']).updateValue(h1Desktop);
        });
        this.loader('hide');
    }

    private updateStaticPart(stream: string, substream: string, popularCourse: string): void {
        let staticURLPart: string = '', staticBreadcrumbPart: string = '';
        let invalidChars = /[\.\/>]/g;
        if (stream) {
            stream = stream.replace(invalidChars, ' ');
            if (substream) {
                substream = substream.replace(invalidChars, ' ');
                staticURLPart = stream + "/" + substream + "/colleges/";
                staticBreadcrumbPart = 'Home > ' + stream + " > " + substream + " > colleges > ";
            } else {
                staticURLPart = stream + "/colleges/";
                staticBreadcrumbPart = "Home > " + stream + " > colleges > ";
            }
        } else if (popularCourse) {
            popularCourse = popularCourse.replace(invalidChars, '-');
            staticURLPart = popularCourse + "/colleges/";
            staticBreadcrumbPart = "Home > " + popularCourse + " > colleges > ";
        } else {
            staticURLPart = '';
            staticBreadcrumbPart = '';
        }

        this.baseURL = this.categorySeoService.getBaseURL() + staticURLPart;
        this.baseBreadcrumb = staticBreadcrumbPart;
    }

    private loader(loaderAction: string) {
        let temp = this.action;
        if (loaderAction == 'show') {
            this.action = 'Loader';
            this.showOverlay = true;
        } else if (loaderAction == 'hide') {
            this.action = temp;
            this.showOverlay = false;
        }
    }

    // Needs to be moved at a common location because it is being used in more than one components
    public backToHome() {
        this.router.navigate(['/nationalCategoryList/CategoryPageSeoEnterprise/viewList']);
    }

    /**
     * Activated on pressing the <b>Go</b> button.
     *
     * The data returned from the API works under two conditions:
     * <ul>
     *     <li>If an exact match of the combinations is found, the values are pre-filled in the form</li>
     *     <li>If an exact match of the combinations is not found, the matching values are searched and if found, the user can select any of the values to pre-fill the input form. If not found, the flow becomes that for 'Add Category SEO Information '</li>
     * </ul>
     *
     * @returns {boolean}
     */
    public populateValues() {

        this.matchingIndex = -1;
        let streamId = this.categorySeoForm.controls['streamList'].value;
        let substreamId = this.categorySeoForm.controls['substreamList'].value;
        let popularCourse = this.categorySeoForm.controls['popularCourses'].value;

        if (streamId == 'none' && substreamId == 'none' && popularCourse == 'none') {
            alert('Please select either of Stream OR a Popular Course');
            this.goStatus = false;
            this.baseBreadcrumb = '';
            this.placeholderIdentifier.forEach((control) => {
                (<FormControl>this.categorySeoForm.controls[control]).updateValue('');
            });
        } else {
            this.goStatus = true;
        }

        if (this.goStatus) {
            this.loader('show');
            this.categorySeoService.getSeoDetails(this.categorySeoForm.value)
                .subscribe(
                data => {
                    this.loader('hide');

                    let dataFromDB = '';
                    if (data.found == 'yes') {
                        this.mode = 'Edit';
                        this.action = 'Edit';
                        dataFromDB = data.result[0];
                        this.updateFormValue(dataFromDB, this);
                        this.findUsedPlaceholders();
                    } else if (data.found == 'match') {
                        this.mode = 'Add';
                        this.action = 'SelectMatch';
                        if (data.result != 'not-found') {
                            this.similarURLs = data.result;
                            this.showOverlay = true;
                            this.findUsedPlaceholders();
                        } else {
                            this.similarURLs = [];
                            this.updateFormValue('', this);
                            alert('No matching entries found');
                            this.showOverlay = false;
                            this.action = 'Add';
                            this.findUsedPlaceholders();
                        }
                    }
                },
                error => {
                    console.log(error);
                    this.loader('hide');
                    alert('Internal error');
                    this.backToHome();
                }
                );

        }
        return false;
    }

    public getExpandedValueForField(fieldName) {
        let value = this.categorySeoForm.controls[fieldName].value;
        let toReplace;
        switch (fieldName) {
            case "url":
            case "breadcrumb":
                if (fieldName == "url") {
                    value = this.baseURL + value;
                    toReplace = this.categorySeoService.getBaseURL();
                    value = value.replace(toReplace, '');
                }
                else if (fieldName == "breadcrumb") {
                    value = this.baseBreadcrumb + value;
                    toReplace = 'Home > ';
                    value = value.replace(toReplace, '');
                }
                let temp = fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
                if (this.categorySeoForm.controls['streamList'].value == 'none') {
                    toReplace = (fieldName == 'url') ? '[course]/' : '[course] >';
                    value = value.replace(this['streamOrCourseNameIn' + temp], toReplace);
                } else if (this.categorySeoForm.controls['substreamList'].value != 'none') {

                    if (this.categorySeoForm.controls['substreamList'].value == 'any') {
                        toReplace = (fieldName == 'url') ? '[stream]/' : '[stream] >';
                        value = value.replace(this['streamOrCourseNameIn' + temp], toReplace);
                    } else {
                        toReplace = (fieldName == 'url') ? '[stream]/[substream]/' : '[stream] > [substream] >';
                        value = value.replace(this['streamOrSubstreamNameIn' + temp], toReplace);
                    }
                } else {
                    toReplace = (fieldName == 'url') ? '[stream]/' : '[stream] >';
                    value = value.replace(this['streamOrCourseNameIn' + temp], toReplace);
                }
                break;

            default:
                break;
        }
        return value;
    }

    public submitURL() {
        if (this.categorySeoForm.dirty && this.categorySeoForm.valid) {
            this.formSubmit = true;
            let mode: string = 'write', formValue = this.categorySeoForm.value;
            if (this.matchingIndex == -1) {
                formValue['id'] = this.ruleId;
            } else if (this.matchingIndex > -1 && this.action == 'Delete') {
                mode = 'delete';
            } else { // New entry has to be made
                formValue['id'] = '';

                if (this.action == "SelectMatch") {
                    if (!confirm("Creating this rule will override all the existing rules that might be clashing with this rule. Are you sure you want to continue?")) {
                        return false;
                    }
                } else {
                    if (!confirm("Are you sure you want to create this rule?")) {
                        return false;
                    }
                }
            }

            formValue['url'] = this.getExpandedValueForField('url');
            formValue['breadcrumb'] = 'Home > ' + this.getExpandedValueForField('breadcrumb');
            this.loader('show');

            let checkedData: any = this.categorySeoService.submitURL(formValue, mode).subscribe(data => {

                let dataFromDb: any = '';
                if (data.status == 'ok') {
                    this.loader('hide');
                    this.action = 'Edit';
                    this.ruleId = data.result.ruleId;
                    if (data.result.combinations.length > 1) {
                        alert('Rule ' + this.mode + 'ed successfully!');
                        this.showOverlay = true;
                        this.similarURLs = data.result.combinations;
                        if (this.isDuplicate()) {
                            this.hideCloseButton = true;
                        }
                    } else {
                        // this.action = 'Add';
                        if (mode == 'delete') {
                            alert('Rule Deleted successfully!.');
                        } else {
                            alert('Rule ' + this.mode + 'ed successfully!.');
                        }
                        this.backToHome();
                    }
                } else {
                    alert(data.result);
                    this.loader('hide');
                }
            },
                error => { console.log(error); this.loader('hide'); }
            );
        }
        else {
            this.backToHome();
        }
    }

    public populatBaseCourses(): void {
        let o1 = this.categorySeoForm.controls['streamList'].valueChanges;
        let o2 = this.categorySeoForm.controls['substreamList'].valueChanges;

        o1.merge(o2).debounceTime(50).subscribe(() => {
            let streamId = this.categorySeoForm.controls['streamList'].value;
            let substreamId = this.categorySeoForm.controls['substreamList'].value;
            let baseCourseId = this.categorySeoForm.controls['baseCourseSelection'].value;
            let found = false;
            this.basecourseList = [];

            if (streamId == 'none') {
                this.basecourseList = [];
                (<FormControl>this.categorySeoForm.controls['baseCourseSelection']).updateValue('none');
            }
            else if (streamId == 'any') {
                this.listingBaseService.getNonPopularCourses().map((data) => {
                    if (data[baseCourseId]) {
                        found = true;
                    }
                    return Object.values(data).filter((row) => { if (+row['is_popular']) { return false; } return true; });
                }).subscribe((data) => { this.basecourseList = data; if (!found) { (<FormControl>this.categorySeoForm.controls['baseCourseSelection']).updateValue('none'); } });
            }
            else {
                this.listingBaseService.getBasecoursesObjByMultipleBaseEntities([{ 'streamId': streamId, 'substreamId': substreamId, 'specializationId': 'any' }]).map((data) => {
                    if (data[baseCourseId]) {
                        found = true;
                    }
                    return Object.values(data);
                }).subscribe((data) => { this.basecourseList = data; if (!found) { (<FormControl>this.categorySeoForm.controls['baseCourseSelection']).updateValue('none'); } });
            }
        });

        this.categorySeoForm.controls['popularCourses'].valueChanges.subscribe(
            popularCourse => {
                if (popularCourse != 'none') {

                    this.substreamListDisabled = true;
                    (<FormControl>this.categorySeoForm.controls['streamList']).updateValue('none');
                    (<FormControl>this.categorySeoForm.controls['substreamList']).updateValue('none');
                    (<FormControl>this.categorySeoForm.controls['baseCourseSelection']).updateValue('none');
                    (<FormControl>this.categorySeoForm.controls['courseCredentials']).updateValue('none');

                    if (popularCourse == 'any') {
                        this.updateStaticPart('', '', '[course]');
                    } else {
                        let thisCourse = this.popularCourses.filter(
                            key => key.base_course_id == popularCourse
                        )[0];
                        this.updateStaticPart('', '', thisCourse.name);
                    }
                } else {
                    this.baseURL = this.categorySeoService.getBaseURL();
                }
            },
            error => {
                alert('Popular course change failed');
            }
        );
    }

    public getCourseAttributes() {
        let attributeInfo = this.route.snapshot.data['attributeInfo'];
        for (let oneCourseCredential in attributeInfo.Credential) {
            if (attributeInfo.Credential[oneCourseCredential] != 'None') {

                let courseCredential = {
                    'credential_id': oneCourseCredential,
                    'credential_name': attributeInfo.Credential[oneCourseCredential]
                };
                this.courseCredentials.push(courseCredential);
            }
        }

        for (let oneEducationType in attributeInfo['Education Type']) {
            let educationType = {
                'education_type_id': oneEducationType,
                'education_type': attributeInfo['Education Type'][oneEducationType]
            };
            this.educationTypes.push(educationType);
        }

        for (let oneDeliveryMethod in attributeInfo['Medium/Delivery Method']) {
            let deliveryMethod = {
                'delivery_method_id': oneDeliveryMethod,
                'delivery_method': attributeInfo['Medium/Delivery Method'][oneDeliveryMethod]
            };
            this.deliveryMethods.push(deliveryMethod);
        }
    }

    public hideOverlay(): void {
        if (this.isDuplicate() && (this.mode == 'Add' || this.mode == 'Edit') && this.deletionMethod == '') {
            alert('Current rule(id: ' + this.ruleId + ') is conflicting with other rules');
            this.showOverlay = true;
        }
        else {
            if (this.action == 'SelectMatch') {
                this.mode = 'Add';
                this.action = 'Add';
                this.updateFormValue('', this);
                this.matchingIndex = -1;
                this.showOverlay = this.showOverlay ? false : true;
            }
            else if (this.action == 'Add' || this.action == 'Edit') {
                this.showOverlay = false;
                this.backToHome();
            }
            else {
                this.deletionMethod = '';
                this.showOverlay = this.showOverlay ? false : true;
                this.findUsedPlaceholders();
            }
        }
    }

    private findUsedPlaceholders(): void {
        this.usedPlaceholders = [];

        for (let controlName in this.placeholders) {
            if (this.categorySeoForm.value[controlName] && this.categorySeoForm.value[controlName] != 'none') {
                this.usedPlaceholders.push(this.placeholders[controlName]);
            }
        }

        this.usedPlaceholders.sort();
    }


    public selectIndex(event) {
        this.matchingIndex = event.target.value;
    }

    public prefillFields() {

        if (this.matchingIndex > -1) {
            this.updateFormValue(this.similarURLs[this.matchingIndex], this,'prefill');
            this.findUsedPlaceholders();
            this.action = 'SelectMatch';
            this.showOverlay = false;
            this.similarURLs = [];
        } else {
            alert('Please select a rule');
        }
    }


    /**
        * Update the value of the form fields such as URL, Title, Description, H1 Desktop, and H1 Mobile
        * @param dataToUpdate JSON object when editing, blank value when adding
        * @param categorySeoForm The form being worked upon (the reference to the <code>this</code> object)
    */
    private updateFormValue(dataToUpdate, comp: CategorySeoAddComponent,type:string='fetch') {
        let title: string = '', description: string = '', h1Desktop: string = '', h1Mobile: string = '', fixedURL: string = '', variableURL: string = '', fixedBreadcrumb: string = '', variableBreadcrumb: string = '';

        if (typeof dataToUpdate == 'object') {
            // filter out the (case-insensitive) appearance of the term /colleges/ from the input URL
            let collegeSeparatorRegex = /\/colleges\//i;
            let rawUrl = dataToUpdate.url.split(collegeSeparatorRegex);
            variableURL = rawUrl[1];

            // filter out the (case-insensitive) appearance of the term [colleges] from the input breadcrumb
            collegeSeparatorRegex = / > colleges > /i;
            let rawBreadcrumb = dataToUpdate.breadcrumb.split(collegeSeparatorRegex);
            variableBreadcrumb = rawBreadcrumb[1];

            title = dataToUpdate.meta_title;
            description = dataToUpdate.meta_description;
            h1Desktop = dataToUpdate.heading_desktop;
            h1Mobile = dataToUpdate.heading_mobile;

            if(type == 'fetch'){
                comp.ruleId = dataToUpdate.id;
            }
            else{
                comp.ruleId = '';
            }
            comp.baseURL = comp.categorySeoService.getBaseURL() + rawUrl[0] + "/colleges/";
            comp.baseBreadcrumb = rawBreadcrumb[0] + " > colleges > ";
        } else { // The data was not found, fill the form using the input information
            //form.baseBreadcrumb = "";
            comp.ruleId = '';
        }

        setTimeout(() => {
            (<FormControl>comp.categorySeoForm.controls['url']).updateValue(variableURL);
            (<FormControl>comp.categorySeoForm.controls['breadcrumb']).updateValue(variableBreadcrumb);
            (<FormControl>comp.categorySeoForm.controls['title']).updateValue(title);
            (<FormControl>comp.categorySeoForm.controls['description']).updateValue(description);
            (<FormControl>comp.categorySeoForm.controls['h1Desktop']).updateValue(h1Desktop);
            (<FormControl>comp.categorySeoForm.controls['h1Mobile']).updateValue(h1Mobile);
        }, 0);
    }

    /**
     * This function is dormant for now LF-4864 / LF-4705
     */
    public rearrangeURL() {

        if (this.isDuplicate()) {
            alert('Current rule(id: ' + this.ruleId + ') is conflicting with other rules');
        } else {
            this.categorySeoService.rearrangeURL(this.similarURLs).subscribe(
                data => {
                    if (data.status == 'ok') {
                        alert('Successful rearrangement');
                        this.backToHome();
                    } else {
                        alert(data.result);
                    }
                },
                error => {
                    alert('Error in re-ordering...');
                }
            );
        }
    }

    /**
     *
     * @param listOfElements
     * @returns {boolean}
     */
    private isDuplicate(): boolean {
        if(!this.ruleId){
            return false;
        }
        let set = new Set(), currentRuleUrl;
        this.similarURLs.forEach((url) => {
            if (url.id != this.ruleId) {
                set.add(url.new_priority);
            }
            else {
                currentRuleUrl = url;
            }
        });
        return (set.has(currentRuleUrl.new_priority)) ? true : false;
    }

    // public redirectionMethod(event): void {

    //     let selectedMethod: string = event.target.value;

    //     if (selectedMethod == '404' || selectedMethod == '301') {
    //         this.deletionMethod = selectedMethod;
    //     } else {
    //         this.deletionMethod = '';
    //     }
    // }

    // public getCombinations(): void {
    //     this.findUsedPlaceholders();
    //     this.displayedPlaceholders = Array.from(this.usedPlaceholders);
    //     this.fetchSimilarURLs();
    //     this.action = 'Delete';
    //     this.showOverlay = true;
    // }

    // // Used in case of 301 redirect URL selection by change in the selected entities
    // public updateCombinations(source: any, text: string): void {

    //     let index = this.displayedPlaceholders.indexOf(text);
    //     if (source.currentTarget.checked === false) {
    //         this.displayedPlaceholders = this.displayedPlaceholders.filter(
    //             (item, at) => at !== index
    //         );

    //     } else if (source.currentTarget.checked === true) {
    //         if (index < 0) {
    //             this.displayedPlaceholders.push(text);
    //         }
    //     }

    //     if (this.displayedPlaceholders.length > 0) {
    //         this.fetchSimilarURLs();
    //     } else {
    //         alert('Please select at least one option to get matching URLs');
    //         this.similarURLs = [];
    //     }
    // }

    // private fetchSimilarURLs() {
    //     let usedPlaceholders: any = {};
    //     Object.assign(usedPlaceholders, this.displayedPlaceholders);
    //     usedPlaceholders.ruleId = this.ruleId;
    //     let combinationURLs = this.categorySeoService.getCategoryURLs(usedPlaceholders).subscribe(
    //         data => {
    //             if (data.found == 'yes') {
    //                 this.similarURLs = data.result;
    //             } else {
    //                 this.similarURLs = [];
    //                 this.deletionMethod = '301'; // probably not needed
    //             }
    //         },
    //         error => {
    //             console.log(error);
    //             this.loader('hide');
    //             alert("Error");
    //         }
    //     );

    // }

    // Used while setting 301 redirect
    // public setURL(event) {
    //     let selectedValue = event.target.value;

    //     if (selectedValue == 'custom') {
    //         this.showCustom = true;
    //         this.customURL = '';
    //     } else {
    //         this.showCustom = false;
    //         this.customURL = selectedValue;
    //     }
    // }

    // Used while setting 301 redirect
    // public checkURL() {
    //     this.categorySeoService.ping(this.customURL).subscribe(
    //         data => {
    //             if (data.status != 'ok') {
    //                 this.urlInvalid = true;
    //             } else {
    //                 this.urlInvalid = false;
    //             }
    //         },
    //         error => {
    //             console.log(error);
    //         }
    //     );
    // }

    // Used while deleting data and setting the corresponding values
    // public deleteURL(): void {

    //     let deletionData: any = {};
    //     if (this.matchingIndex != -1) { // some match was performed
    //         this.ruleId = null;

    //         if (this.deletionMethod == '301') { // set the 301 values
    //             this.categorySeoForm.value['301_url'] = this.customURL;
    //         } else if (this.deletionMethod == '404') { // do a 404 update in the datastore
    //             this.categorySeoForm.value['show_404'] = 1;
    //         }

    //         this.submitURL();
    //     } else { // match was not performed
    //         deletionData.id = this.ruleId

    //         if (this.deletionMethod == '301') { // set the 301 values
    //             deletionData.type = '301';
    //             deletionData.URL = this.customURL;
    //         } else if (this.deletionMethod == '404') { // do a 404 update in the datastore
    //             deletionData.type = '404';
    //         }
    //         this.loader('show');

    //         let deleteOperation = this.categorySeoService.submitURL(deletionData, 'remove').subscribe(
    //             data => {
    //                 if (data.status == 'ok') {
    //                     alert("Rule deleted succesfully!");
    //                     this.backToHome();
    //                     this.showOverlay = false;
    //                 } else {
    //                     alert("Error deleting rule...");
    //                 }
    //                 this.loader('hide');

    //             },
    //             error => {
    //                 console.log(error);
    //                 this.loader('hide');
    //             }
    //         );
    //     }

    // }

    /**
     * This function is dormant for now LF-4864 / LF-4705
     */
    // public rearrangeURL() {

    //     if (this.isDuplicate(this.similarURLs)) {
    //         alert('Multiple entries with the same order found. Please check.');
    //     } else {
    //         this.categorySeoService.rearrangeURL(this.similarURLs).subscribe(
    //             data => {
    //                 if (data.status == 'ok') {
    //                     alert('Successful rearrangement');
    //                     this.backToHome();
    //                 } else {
    //                     alert(data.result);
    //                 }
    //             },
    //             error => {
    //                 alert('Error in re-ordering...');
    //             }
    //         );
    //     }
    // }

    /**
     *
     * @param listOfElements
     * @returns {boolean}
     */
    // private isDuplicate(listOfElements): boolean {
    //     let foundURLs: any = [];
    //     for (let oneURL in this.similarURLs) {
    //         foundURLs.push(this.similarURLs[oneURL].priority);
    //     }

    //     if (foundURLs.length > 0) { // comparison between non-zero list of elements only
    //         let foundLength = foundURLs.length;
    //         foundURLs = new Set(foundURLs);

    //         return (foundLength != foundURLs.size) ? true : false;
    //     } else { // which does not exist is assumed to be non-duplicate
    //         return false;
    //     }
    // }

    // public redirectionMethod(event): void {

    //     let selectedMethod: string = event.target.value;

    //     if (selectedMethod == '404' || selectedMethod == '301') {
    //         this.deletionMethod = selectedMethod;
    //     } else {
    //         this.deletionMethod = '';
    //     }
    // }

    // public getCombinations(): void {
    //     this.findUsedPlaceholders();
    //     this.displayedPlaceholders = Array.from(this.usedPlaceholders);
    //     this.fetchSimilarURLs();
    //     this.action = 'Delete';
    //     this.showOverlay = true;
    // }

    // // Used in case of 301 redirect URL selection by change in the selected entities
    // public updateCombinations(source: any, text: string): void {

    //     let index = this.displayedPlaceholders.indexOf(text);
    //     if (source.currentTarget.checked === false) {
    //         this.displayedPlaceholders = this.displayedPlaceholders.filter(
    //             (item, at) => at !== index
    //         );

    //     } else if (source.currentTarget.checked === true) {
    //         if (index < 0) {
    //             this.displayedPlaceholders.push(text);
    //         }
    //     }

    //     if (this.displayedPlaceholders.length > 0) {
    //         this.fetchSimilarURLs();
    //     } else {
    //         alert('Please select at least one option to get matching URLs');
    //         this.similarURLs = [];
    //     }
    // }

    // private fetchSimilarURLs() {
    //     let usedPlaceholders: any = {};
    //     Object.assign(usedPlaceholders, this.displayedPlaceholders);
    //     usedPlaceholders.ruleId = this.ruleId;
    //     let combinationURLs = this.categorySeoService.getCategoryURLs(usedPlaceholders).subscribe(
    //         data => {
    //             if (data.found == 'yes') {
    //                 this.similarURLs = data.result;
    //             } else {
    //                 this.similarURLs = [];
    //                 this.deletionMethod = '301'; // probably not needed
    //             }
    //         },
    //         error => {
    //             console.log(error);
    //             this.loader('hide');
    //             alert("Error");
    //         }
    //     );

    // }

    // Used while setting 301 redirect
    // public setURL(event) {
    //     let selectedValue = event.target.value;

    //     if (selectedValue == 'custom') {
    //         this.showCustom = true;
    //         this.customURL = '';
    //     } else {
    //         this.showCustom = false;
    //         this.customURL = selectedValue;
    //     }
    // }

    // Used while setting 301 redirect
    // public checkURL() {
    //     this.categorySeoService.ping(this.customURL).subscribe(
    //         data => {
    //             if (data.status != 'ok') {
    //                 this.urlInvalid = true;
    //             } else {
    //                 this.urlInvalid = false;
    //             }
    //         },
    //         error => {
    //             console.log(error);
    //         }
    //     );
    // }

    // Used while deleting data and setting the corresponding values
    // public deleteURL(): void {

    //     let deletionData: any = {};
    //     if (this.matchingIndex != -1) { // some match was performed
    //         this.ruleId = null;

    //         if (this.deletionMethod == '301') { // set the 301 values
    //             this.categorySeoForm.value['301_url'] = this.customURL;
    //         } else if (this.deletionMethod == '404') { // do a 404 update in the datastore
    //             this.categorySeoForm.value['show_404'] = 1;
    //         }

    //         this.submitURL();
    //     } else { // match was not performed
    //         deletionData.id = this.ruleId

    //         if (this.deletionMethod == '301') { // set the 301 values
    //             deletionData.type = '301';
    //             deletionData.URL = this.customURL;
    //         } else if (this.deletionMethod == '404') { // do a 404 update in the datastore
    //             deletionData.type = '404';
    //         }
    //         this.loader('show');

    //         let deleteOperation = this.categorySeoService.submitURL(deletionData, 'remove').subscribe(
    //             data => {
    //                 if (data.status == 'ok') {
    //                     alert("Rule deleted succesfully!");
    //                     this.backToHome();
    //                     this.showOverlay = false;
    //                 } else {
    //                     alert("Error deleting rule...");
    //                 }
    //                 this.loader('hide');

    //             },
    //             error => {
    //                 console.log(error);
    //                 this.loader('hide');
    //             }
    //         );
    //     }

    // }
}
