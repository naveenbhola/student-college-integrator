import { bootstrap }    from '@angular/platform-browser-dynamic';
import { AppComponent } from './app.component';
import { HTTP_PROVIDERS } from '@angular/http';
import { APP_ROUTER_PROVIDERS } from './app.routes';
import { SidebarService } from './services/sidebar-service';
import { provideForms, disableDeprecatedForms } from '@angular/forms';

import {PLATFORM_DIRECTIVES} from '@angular/core'; 
import {OffClickDirective} from './Common/Components/select/off-click';
import { MY_FORM_FIELD_DIRECTIVES } from './reusables/formFields';
import { Loader } from './reusables/loader';

import { Multiselect } from './reusables/multiselect';

import {enableProdMode} from '@angular/core';

enableProdMode();

bootstrap(AppComponent, [{provide:PLATFORM_DIRECTIVES,useValue:[OffClickDirective,MY_FORM_FIELD_DIRECTIVES,Loader,Multiselect],multi:true},HTTP_PROVIDERS, APP_ROUTER_PROVIDERS,provideForms(),disableDeprecatedForms(),SidebarService]).catch(err => console.error(err));



