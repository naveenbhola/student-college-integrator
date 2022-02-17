import { Component, OnInit, Input, ViewChild, Output, EventEmitter } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';
import { ShikshaSelectComponent } from '../../../Common/Components/select/shiksha-select-component';
import { ListingBaseClass, ListingBaseService } from '../../../services/listingbase.service';
import { InstitutePostingService } from '../../../services/institute-posting.service';

import { Observable } from 'rxjs/Rx';
import { Subject } from 'rxjs/Subject';


@Component({
  selector: "shikshaInstituteAutosuggestor",
  templateUrl: '/public/angular/app/Listing/ListingReusables/views/shikshaInstituteAutosuggestor.component.html',
  directives: [ShikshaSelectComponent],
  providers: [InstitutePostingService, ListingBaseService]
})

export class ShikshaInstituteAutosuggestorComponent extends Posting implements OnInit {

  @Output() selectedInstituteItem = new EventEmitter();
  @Input() autoSuggestorType = 'domestic';
  @Input() suggestionType = 'university';
  autosuggestorSource;
  autosuggestorObservable;
  public instituteAutoSuggestorItems: Array<Object> = [];
  private instituteAutoSuggestorSuggestedItem: any = {};
  private _disabledV: string = '0';
  private disabled: boolean = false;


  constructor(private institutePosting: InstitutePostingService, public listingBaseService: ListingBaseService
  ) {
    super();
  }

  ngOnInit() {
    this.autosuggestorSource = new Subject();
    this.autosuggestorObservable = this.autosuggestorSource.asObservable();
    this.autosuggestorObservable.debounceTime(400).map((data) => { if (typeof data == 'string') { data = data.trim(); } if (data) { return data; } }).subscribe(typedText => { if (typedText) { this.getInstituteParentSuggestion(typedText); } });
  }


  private get disabledV(): string {
    return this._disabledV;
  }

  private set disabledV(value: string) {
    this._disabledV = value;
    this.disabled = this._disabledV === '1';
  }

  public selected(value: any): void {
    let temp = value['id'].split('_');
    let resultObj = [];
    resultObj['type'] = temp[0];
    resultObj['id'] = temp[1];
    resultObj['name'] = value['text'];
    this.selectedInstituteItem.emit({ 'selectedValue': resultObj, 'autosuggestorType': this.autoSuggestorType });
  }

  public removed(value: any): void {
  }

  public typed(value: any): void {
    if (!value) {
      this.instituteAutoSuggestorItems = [];
    } else {
      this.autosuggestorSource.next(value);
    }
  }

  public refreshValue(value: any): void {
    this.instituteAutoSuggestorSuggestedItem = value;
  }

  getInstituteParentSuggestion(typedText) {
    if (this.autoSuggestorType == 'domestic') {
      this.listingBaseService.getInstituteAutosuggestor(typedText, this.suggestionType).subscribe(data => this.fillInstituteItems(data)
        , err => err,
        () => console.log('Authentication Complete'));
    } else {
      this.listingBaseService.getSAInstituteAutosuggestor(typedText).subscribe(data => this.fillInstituteItems(data)
        , err => err,
        () => console.log('Authentication Complete'));
    }
  }

  fillInstituteItems(data) {
    this.instituteAutoSuggestorItems = [];
    for (let i in data) {
      for (let y in data[i]) {
        this.instituteAutoSuggestorItems.push({ 'text': data[i][y], 'id': i + "_" + y });
      }
    }
  }

  getInputInstitute(val, type) {
    if(parseInt(val) > 0){
      if (this.autoSuggestorType == 'domestic') {
        this.listingBaseService.getInstituteName(type, val).subscribe(data => this.getInstituteNameResponse(data, type, val)
          , err => err,
          () => console.log('Authentication Complete'));
      } else {
        this.listingBaseService.getSAInstituteName(type, val).subscribe(data => this.getSAInstituteNameResponse(data, type, val)
          , err => err,
          () => console.log('Authentication Complete'));
      }
    } else {
      let resultObj = [];
      resultObj['error'] = "Invalid " + type + " id entered!";
      this.selectedInstituteItem.emit({ 'selectedValue': resultObj, 'autosuggestorType': this.autoSuggestorType });
    }
  }

  getInstituteNameResponse(res, type, val) {
    let resultObj = {}, name = '';
    name = type + "_" + val;
    if (res && res[name]) {
      resultObj['type'] = type;
      resultObj['id'] = val;
      resultObj['name'] = res[name];
    } else {
      resultObj['error'] = "Invalid " + type + " id entered!";
    }
    this.selectedInstituteItem.emit({ 'selectedValue': resultObj, 'autosuggestorType': this.autoSuggestorType });
  }

  getSAInstituteNameResponse(res, type, val) {

    for (let i in res.institute) {
      let resultObj = [];

      if (res.institute[i] !== null) {
        resultObj['type'] = type;
        resultObj['id'] = i;
        resultObj['name'] = res.institute[i];
      } else {
        resultObj['error'] = "Invalid " + type + " id entered!";
      }
      this.selectedInstituteItem.emit({ 'selectedValue': resultObj, 'autosuggestorType': this.autoSuggestorType });
    }
  }
}
