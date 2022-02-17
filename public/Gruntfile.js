module.exports = function(grunt) {
    var settings = grunt.file.readJSON('gruntConfig.json');
    var concatCustom = {};
    var uglifyCustom = {};
    var cssConfig = (require('./GruntCSS'))(grunt);
    // grunt.log.writeln(JSON.stringify(cssConfig));
    for (var key in settings.mappings) {
        var filesWithDirectory = settings.mappings[key]['files'].map(function(fileName) {
            return settings.mappings[key]['cwd'] + '/' + fileName;
        });

        var destConcatDirectory = settings.mappings[key]['cwd'] + '/devJS/';

        //preparing an array to concat files
        concatCustom[key] = { 'src': filesWithDirectory, 'dest': destConcatDirectory + settings.mappings[key]['bundleName'] };

        var destUglifyDirectory = settings.mappings[key]['cwd'] + '/uglifiedJS/';

        //preparing an array to uglify files
        uglifyCustom[key] = { 'src': destConcatDirectory + settings.mappings[key]['bundleName'], 'dest': destUglifyDirectory + settings.mappings[key]['minifiedName'] };
    }

    uglifyCustom['desktopAll'] = { 'files': [{ 'src': '*.js', 'dest': 'js/uglifiedJS', 'expand': true, 'cwd': 'js' }] };

    uglifyCustom['mobile5All'] = { 'files': [{ 'src': '*.js', 'dest': 'mobile5/js/uglifiedJS', 'expand': true, 'cwd': 'mobile5/js' }] };
    uglifyCustom['mobileSA']={'files':[{'src':'*.js','dest':'mobileSA/js/uglifiedJS','expand': true,'cwd': 'mobileSA/js'}]};

    uglifyCustom['mobile5Vendor'] = { 'files': [{ 'src': '*.js', 'dest': 'mobile5/js/vendor/uglifiedJS', 'expand': true, 'cwd': 'mobile5/js/vendor' }] };
    uglifyCustom['mobile5VendorBoomerang'] = { 'files': [{ 'src': '*.js', 'dest': 'mobile5/js/vendor/boomerang/uglifiedJS', 'expand': true, 'cwd': 'mobile5/js/vendor/boomerang' }] };

    uglifyCustom['mobileSAVendor']={'files':[{'src':'*.js','dest':'mobileSA/js/vendor/uglifiedJS','expand': true,'cwd': 'mobileSA/js/vendor'}]};    

    uglifyCustom['trackingMIS'] = { 'files': [{ 'src': '*.js', 'dest': 'js/trackingMIS/uglifiedJS', 'expand': true, 'cwd': 'js/trackingMIS' }] };
    uglifyCustom['pwa'] = { 'files': [{ 'src': 'service-worker.js', 'dest': '../pwa/', 'expand': true, 'cwd': '../pwa/' }] };
    uglifyCustom['responsiveAssetsJS'] = { 'files': [{ 'src': '*.js', 'dest': 'responsiveAssets/js/uglifiedJS', 'expand': true, 'cwd': 'responsiveAssets/js' }] };

    grunt.initConfig({
        cssmin: cssConfig['cssConcatObj'],
        copy: {
            js: {
                files: [
                    // includes files within path
                    { expand: true, src: '*', dest: 'js', 'cwd': 'js/thirdParty' },
                    { expand: true, src: '*', dest: 'js', 'cwd': 'js/rawJs' },
                    { expand: true, src: '*', dest: 'mobile5/js', 'cwd': 'mobile5/js/thirdParty' },
                    { expand: true, src: '*', dest: 'mobileSA/js', 'cwd': 'mobileSA/js/vendor' },
                    { expand: true, src: '*', dest: 'js', 'cwd': 'responsiveAssets/js' },
                    { expand: true, src: '*', dest: 'mobileSA/js', 'cwd': 'responsiveAssets/js' }
                ]
            },
            css: {
                files: [
                    // includes files within path
                    { expand: true, src: '*', dest: 'css', 'cwd': 'responsiveAssets/css' },
                    { expand: true, src: '*', dest: 'mobileSA/css', 'cwd': 'responsiveAssets/css' }                    
                ]
            }
        },
        concat: concatCustom,
        uglify: uglifyCustom,
        assets_versioning: {
            options: {
                hashLength: 6,
                tag: 'hash',
            },
            desktopAll: {
                options: {
                    versionsMapFile: 'mappings/desk_vm_js.js',
                    versionsMapTrimPath: 'js/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'js/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'js/build',
                }]
            },
            mobile5All: {
                options: {
                    versionsMapFile: 'mappings/nat_mobile_vm_js.js',
                    versionsMapTrimPath: 'mobile5/js/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'mobile5/js/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'mobile5/js/build',
                }]
            },
            saMobile: {
                options: {
                    versionsMapFile:'mappings/sa_mobile_vm_js.js',
                    versionsMapTrimPath:'mobileSA/js/build/'
                },
                files: [{
                    expand:true,
                    cwd: 'mobileSA/js/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'mobileSA/js/build',
                }]
            },
            mobile5Vendor: {
                options: {
                    versionsMapFile: 'mappings/mobile5_vendor_vm_js.js',
                    versionsMapTrimPath: 'mobile5/js/vendor/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'mobile5/js/vendor/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'mobile5/js/vendor/build',
                }]
            },
            mobile5VendorBoomerang: {
                options: {
                    versionsMapFile: 'mappings/mobile5_vendor_boomerang_vm_js.js',
                    versionsMapTrimPath: 'mobile5/js/vendor/boomerang/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'mobile5/js/vendor/boomerang/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'mobile5/js/vendor/boomerang/build',
                }]
            },
            mobileSAVendor: {
                options: {
                    versionsMapFile:'mappings/mobileSA_vendor_vm_js.js',
                    versionsMapTrimPath:'mobileSA/js/vendor/build/'
                },
                files: [{
                    expand:true,
                    cwd: 'mobileSA/js/vendor/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'mobileSA/js/vendor/build',
                }]
            },
            trackingMIS: {
                options: {
                    versionsMapFile: 'mappings/trackingMIS_vm_js.js',
                    versionsMapTrimPath: 'js/trackingMIS/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'js/trackingMIS/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'js/trackingMIS/build',
                }]
            },
            pwa: {
                options: {
                    versionsMapFile: '../pwa/mappings/pwa_mobile_vm_js.js',
                    versionsMapTrimPath: '../'
                },
                files: [{
                    expand: true,
                    cwd: '../pwa',
                    src: ['service-worker.js'],
                    dest: '../',
                }]
            },
            responsiveAssetsJS: {
                options: {
                    versionsMapFile: 'mappings/responsive_assets_vm_js.js',
                    versionsMapTrimPath: 'responsiveAssets/js/build/'
                },
                files: [{
                    expand: true,
                    cwd: 'responsiveAssets/js/uglifiedJS/',
                    src: ['*.js'],
                    dest: 'responsiveAssets/js/build',
                }]
            },
            css_desktopAll: cssConfig['versionObj']['css_desktopAll'],
            css_trackingMIS: cssConfig['versionObj']['css_trackingMIS'],
            css_mobile5Vendor: cssConfig['versionObj']['css_mobile5Vendor'],
            css_mobile5All: cssConfig['versionObj']['css_mobile5All'],
            css_mobileSA: cssConfig['versionObj']['css_mobileSA'],
            css_pwa: cssConfig['versionObj']['css_pwa'],
            css_responsiveAssetsCSS: cssConfig['versionObj']['css_responsiveAssetsCSS']
        },
        cdnify: cssConfig['urlRewrite'],
        clean: {
            js: [
                'js/devJS',
                'js/uglifiedJS',
                'mobile5/js/devJS/',
                'mobile5/js/uglifiedJS/',
                'mobile5/js/vendor/devJS/',
                'mobile5/js/vendor/uglifiedJS/',
                'mobile5/js/vendor/boomerang/devJS/',
                'mobile5/js/vendor/boomerang/uglifiedJS/',
                'mobileSA/js/devJS/',
                'mobileSA/js/uglifiedJS/',
                'mobileSA/js/vendor/devJS/',
                'mobileSA/js/vendor/uglifiedJS/',
                'js/trackingMIS/devJS/',
                'js/trackingMIS/uglifiedJS/'
            ],
            css: cssConfig['cleanObj'],
            options: {
                force : true
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-assets-versioning');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-cdnify');

    // Default task
    // grunt.registerTask('default', 'Log some stuff',function(){
    //     grunt.log.write('Logging some stuff...').ok();
    // });
    grunt.registerTask('js', ['copy:js', 'concat', 'uglify', 'assets_versioning:desktopAll', 'assets_versioning:mobile5All','assets_versioning:saMobile', 'assets_versioning:mobile5Vendor', 'assets_versioning:mobile5VendorBoomerang','assets_versioning:mobileSAVendor', 'assets_versioning:trackingMIS', 'assets_versioning:pwa', 'clean:js']);
    grunt.registerTask('css', ['copy:css','cdnify', 'cssmin', 'assets_versioning:css_desktopAll', 'assets_versioning:css_mobile5All', 'assets_versioning:css_mobile5Vendor', 'assets_versioning:css_mobileSA','assets_versioning:css_pwa','assets_versioning:css_responsiveAssetsCSS','assets_versioning:css_trackingMIS', 'clean:css']);
    grunt.registerTask('default', ['js', 'css']);
}
