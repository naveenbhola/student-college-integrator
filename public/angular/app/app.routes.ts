import {provideRouter, RouterConfig} from '@angular/router';
import { SUBSTREAMROUTES } from './forms_substream/substream.routes';
import { SPECIALIZATIONROUTES } from './forms_specialization/specialization.routes';
import { HIERARCHYROUTES } from './forms_hierarchy/hierarchy.routes';
import { POPULARGROUPROUTES } from './forms_populargroup/populargroup.routes';
import { BASECOURSEROUTES } from './forms_basecourse/basecourse.routes';
import { CERTIFICATIONPROVIDERROUTES } from './forms_certification_provider/certification-provider.routes';
import { INSTITUTEPOSTINGROUTES } from './Listing/InstitutePosting/Routes/institutePosting.routes';
import { COURSEPOSTINGROUTES, COURSEPOSTINGPROVIDERS } from './Listing/CoursePosting/routes/CoursePosting.routes';
import { CATEGORYSEOROUTES, CATEGORYSEOPROVIDERS } from './Listing/CategorySeoURLPosting/Components/category-seo.routes';
import { LISTINGCONTENTROUTES } from './Listing/ListingContent/Routes/listingContent.routes';

import { UNIVERSITYPOSTINGROUTES } from './Listing/InstitutePosting/Routes/universityPosting.routes';
import { UserService } from './Common/services/userservice';
import { UserGuard } from './Common/services/userGuard';
import { ListingAdminGuard,ListingSpecialUsersGuard } from './Listing/CoursePosting/services/coursePostingGuards';

export const routes: RouterConfig = [
	...SUBSTREAMROUTES,
	...SPECIALIZATIONROUTES,
	...HIERARCHYROUTES,
	...POPULARGROUPROUTES,
	...BASECOURSEROUTES,
	...CERTIFICATIONPROVIDERROUTES,
	...INSTITUTEPOSTINGROUTES,
	...COURSEPOSTINGROUTES,
	...CATEGORYSEOROUTES,
	...UNIVERSITYPOSTINGROUTES,
	...LISTINGCONTENTROUTES
];

export const APP_ROUTER_PROVIDERS = [provideRouter(routes),COURSEPOSTINGPROVIDERS,CATEGORYSEOPROVIDERS,UserService,UserGuard,ListingSpecialUsersGuard,ListingAdminGuard];