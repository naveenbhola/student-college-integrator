let COOKIE_DOMAIN = '.shiksha.com';

export function set_siteview(req, res){
    let siteview = 'desktop';
    if(req.cookies.ci_mobile == 'mobile') {
        if(req.cookies.ci_mobile_js_support == 'yes') {
            siteview = 'mobile5';
        }
        else {
            siteview = 'mobile4';
        }
    }
    res.cookie('siteview', siteview, { domain:COOKIE_DOMAIN, expires:  new Date(Date.now() + (3600 * 24 * 30))});
}
