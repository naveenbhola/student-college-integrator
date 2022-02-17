import seoConfig from '../config/defaultSeoConfig';
import config from './../../../../config/config';
import {addingDomainToUrl} from './../../../utils/commonHelper';

export default function ClientSeo(props){
    if(typeof document !='undefined' && document.getElementById('seoTitle')){
        document.getElementById('seoTitle').innerHTML = (props && props.metaTitle) ? props.metaTitle : seoConfig['default'].metaTitle;
    }
    if(typeof document !='undefined' && document.getElementById('canonicalUrl')){
    	let canonicalUrl = (props && props.canonicalUrl) ? addingDomainToUrl(props.canonicalUrl, config().SHIKSHA_HOME) : config().SHIKSHA_HOME;
        document.getElementById('canonicalUrl').setAttribute('href', canonicalUrl);
    }
}