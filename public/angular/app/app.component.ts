import { Component,ViewContainerRef } from '@angular/core';
// Add the RxJS Observable operators we need in this app.
import './rxjs-operators';
import {ROUTER_DIRECTIVES} from '@angular/router';
import { SideBarComponent } from './Common/Components/sidebar-component';


@Component({
  selector: 'enterprise-app',
  template: `
  <div class="container body"><div class="main_container">
  <side-bar></side-bar>
  <div class="right_col" role="main"><div class="">
  <router-outlet></router-outlet>
  </div></div><footer><div class="pull-right"></div><div class="clearfix"></div></footer></div></div>
  `,
  directives: [ROUTER_DIRECTIVES,SideBarComponent],
})
export class AppComponent {
  public constructor(public viewContainerRef:ViewContainerRef) {
    // You need this small hack in order to catch application root view container ref
    this.viewContainerRef = viewContainerRef;
  }
}

