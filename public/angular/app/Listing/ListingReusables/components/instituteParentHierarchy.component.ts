import { Component, OnInit, Input, ViewChild, Output, EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import {ShikshaSelectComponent} from '../../../Common/Components/select/shiksha-select-component';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { ListingBaseClass, ListingBaseService } from '../../../services/listingbase.service';
import { InstitutePostingService } from '../../../services/institute-posting.service';
import { InstituteLocationService } from '../../../services/institute-locations.service';


import { Observable }     from 'rxjs/Rx';
import { Subject }     from 'rxjs/Subject';


@Component({
  selector: "institute-parent-hierarchy",
  templateUrl: '/public/angular/app/Listing/ListingReusables/views/instituteParentHierarchy.component.html',
  directives: [ShikshaSelectComponent, MODAL_DIRECTIVES],
  providers: [BS_VIEW_PROVIDERS, InstitutePostingService, ListingBaseService]
})

export class InstituteParentHierarchyComponent extends Posting implements OnInit {

    @Output() selectedHierarchyItem = new EventEmitter();
    @Input() institute_type;
    @Input() satelliteCampus;
    @Input() postingListingType;
    @Input() typeOfListingPosting;//dummy or regular listing
    @Input() excludedInstituteId;// id of institute for which hierarchy is to be attached
    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
    newInstituteType: string;
    instituteParentHierarchyList = []; //list to show in modal
    autosuggestorSource;
    autosuggestorObservable;
    public instituteAutoSuggestorItems: Array<Object> = [];
    private instituteAutoSuggestorSuggestedItem: any = {};
    private _disabledV: string = '0';
    private disabled: boolean = false;


    constructor(private institutePosting: InstitutePostingService, public listingBaseService: ListingBaseService, private instituteLocationService: InstituteLocationService
  ) {
    super();
    }

  ngOnInit() {
    this.autosuggestorSource = new Subject();
        this.autosuggestorObservable = this.autosuggestorSource.asObservable();
        this.autosuggestorObservable.debounceTime(400).map((data) => { if (typeof data == 'string') { data = data.trim(); } if (data) { return data; } }).subscribe(typedText => { if (typedText) { this.getInstituteParentSuggestion(typedText); } });
  }

    @ViewChild('lgModal') public hierarchyModal: ModalDirective;
    public showHierarchyModal(): void {
    this.hierarchyModal.show();
    }

    public hideHierarchyModal(): void {
    this.hierarchyModal.hide();
    }

    private get disabledV(): string {
    return this._disabledV;
    }

    private set disabledV(value: string) {
    this._disabledV = value;
    this.disabled = this._disabledV === '1';
    }

    public selected(value: any): void {
    let instituteString = value.id.split("_");
    this.getInputInstituteParentHierarchy(instituteString[1], instituteString[0]);
    //this.showHierarchyModal();
    }

    public removed(value: any): void {
    }

    public typed(value: any): void {
    if (!value) {
      setTimeout(() => { this.instituteAutoSuggestorItems = [] }, 0);
    } else {
      this.autosuggestorSource.next(value);
    }
    }

    public refreshValue(value: any): void {
    this.instituteAutoSuggestorSuggestedItem = value;
    }

    getInstituteParentSuggestion(typedText) {
        let suggestionType: any;
    if (this.postingListingType == 'University')
      suggestionType = this.postingListingType;
    else
      suggestionType = '';

    this.listingBaseService.getInstituteAutosuggestor(typedText, suggestionType).subscribe(data => this.fillInstituteItems(data)
      , err => err,
      () => console.log('Authentication Complete'));
  }

    fillInstituteItems(data) {
    this.instituteAutoSuggestorItems = [];
        for (let i in data) {
      for (let y in data[i]) {
        this.instituteAutoSuggestorItems.push({ 'text': data[i][y], 'id': i + "_" + y });
      }
        }
    }

  getInputInstituteParentHierarchy(instituteId, type) {
        this.instituteParentHierarchyList = [];
        if (this.satelliteCampus == true) {
            this.newInstituteType = 'satellite';
        }
        else {
      this.newInstituteType = this.institute_type;
        }
        this.institutePosting.getInstituteParentHierarchyData(instituteId, type, this.newInstituteType, this.postingListingType, this.typeOfListingPosting, this.excludedInstituteId).subscribe(
      data => { this.instituteParentHierarchyList = this.generateArray(data); },
      error => alert('Failed to get institute hierarchy data')
    );
        this.showHierarchyModal();
    }

    selectedHierarchy(index, selectedParentEntity) {
    this.selectedHierarchyItem.emit({ 'selectedParentEntity': selectedParentEntity, 'selectedParentObj': this.instituteParentHierarchyList[index] });
    this.hideHierarchyModal();
    this.instituteLocationService.pushHierarchyChange({});
    }
    generateArray(obj){
       return Object.keys(obj).map((key)=>{ return obj[key]});

    }
}
