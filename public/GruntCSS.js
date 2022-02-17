module.exports = function(grunt) {
    var settings = require('./gruntCssConfig.json');
    var path = require('path');
    var cssConcatObj = {},
        temp = { 'files': {}, 'options': { 'extDot': 'last' } },
        versionObj = {},
        urlRewrite = {},
        cleanObj = [];

    var mapping = { 'css': 'desktopAll', 'css/trackingMIS': 'trackingMIS', 'mobile5/css': 'mobile5All', 'mobile5/css/vendor': 'mobile5Vendor', 'mobileSA/css': 'mobileSA','../pwa/public/css' : 'pwa','responsiveAssets/css':'responsiveAssetsCSS' };
    var versionMapping = { 'desktopAll': 'mappings/desk_vm_css.js', 'trackingMIS': 'mappings/trackingMIS_vm_css.js', 'mobile5All': 'mappings/nat_mobile_vm_css.js', 'mobile5Vendor': 'mappings/mobile5_vendor_vm_css.js', 'mobileSA': 'mappings/sa_mobile_vm_css.js','pwa' : '../pwa/mappings/pwa_mobile_vm_css.js','responsiveAssetsCSS' : 'mappings/responsive_assets_vm_css.js' };

    var checkIfUrl = function(str) {
        var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
        return regexp.test(str);
    };

    var rewriterFunc = function(dir) {
        return function(url) {
            var returnUrl;
            if (url.indexOf('data:') === 0) {
                returnUrl = url;
            } else {
                if (checkIfUrl(url)) {
                    returnUrl = url;
                } else {
                    if (path.isAbsolute(url)) {
                        returnUrl = '//' + settings.imageCDN + url;
                    } else {
                        url = path.resolve(dir + '/', url);
                        url = url.split('/public');
                        returnUrl = '//' + settings.imageCDN + '/public' + url[1];
                    }
                }
            }
            return returnUrl;
        };
    }

    Object.keys(mapping).forEach(function(dir) {
        urlRewrite[mapping[dir]] = {
            'options': {
                'rewriter': rewriterFunc(dir)
            },
            'files': [{
                'expand': true,
                'cwd': dir,
                'src': '*.css',
                'dest': dir + '/dist'
            }]
        };
    });

    for (var key in settings.mappings) {
        var filesWithDirectory = settings.mappings[key]['files'].map(function(fileName) {
            return settings.mappings[key]['cwd'] + '/dist/' + fileName;
        });

        var keyName = settings.mappings[key]['cwd'] + '/build/' + settings.mappings[key]['bundleName'] + '.min.css';

        temp['files'][keyName] = filesWithDirectory;
    }
    cssConcatObj['cssConcat'] = temp;

    Object.keys(mapping).forEach(function(dir) {
        var temp = {};
        temp['expand'] = true;
        temp['src'] = ['*.css', '!*.min.css'];
        temp['cwd'] = dir + '/dist';
        temp['dest'] = dir + '/build';
        temp['ext'] = '.min.css';
        temp['extDot'] = 'last';
        cssConcatObj[mapping[dir]] = { 'files': [temp] };
        versionObj['css_' + mapping[dir]] = {
            'options': {
                'versionsMapFile': versionMapping[mapping[dir]],
                'versionsMapTrimPath': dir + '/build/'
            },
            files: [{
                'expand': true,
                'cwd': dir + '/build',
                'src': ['*.min.css'],
                'dest': dir + '/build',
                'extDot': 'last'
            }]
        };
        cleanObj.push(dir + '/build/*.min.css');
        cleanObj.push(dir + '/dist');
    });

    var returnObj = { 'cssConcatObj': cssConcatObj, 'versionObj': versionObj, 'urlRewrite': urlRewrite, 'cleanObj': cleanObj };
    return returnObj;
};
