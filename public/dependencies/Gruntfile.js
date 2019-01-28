module.exports = function(grunt) {

    grunt.initConfig({


        pkg: grunt.file.readJSON('package.json'),
        concat: {
            datatables_css: {
                src: [
                    'bower_components/datatables.net-bs/css/dataTables.bootstrap.css',
                    'bower_components/datatables.net-responsive-bs/css/responsive.bootstrap.css',
                    'bower_components/datatables.net-rowreorder-bs/css/rowReorder.bootstrap.css',
                    'bower_components/datatables.net-scroller-bs/css/scroller.bootstrap.css',
                    'bower_components/datatables.net-select-bs/css/select.bootstrap.css'
                ],
                dest: '../vendor/datatables/css/datatables.css'
            },
            datatables_js: {
                src: [
                    'bower_components/datatables.net/js/jquery.dataTables.js',
                    'bower_components/datatables.net-bs/js/dataTables.bootstrap.js',
                    'bower_components/datatables.net-responsive/js/dataTables.responsive.js',
                    'bower_components/datatables.net-responsive-bs/js/responsive.bootstrap.js',
                    'bower_components/datatables.net-rowreorder/js/dataTables.rowReorder.js',
                    'bower_components/datatables.net-scroller/js/dataTables.scroller.js',
                    'bower_components/datatables.net-select/js/dataTables.select.js'
                ],
                dest: '../vendor/datatables/js/datatables.js'
            },
            jquery_ui_js: {
                src: [
                    'bower_components/jquery-ui/ui/widget.js',
                    'bower_components/jquery-ui/ui/position.js',
                    'bower_components/jquery-ui/ui/data.js',
                    'bower_components/jquery-ui/ui/disable-selection.js',
                    'bower_components/jquery-ui/ui/focusable.js',
                    'bower_components/jquery-ui/ui/form-reset-mixin.js',
                    'bower_components/jquery-ui/ui/jquery-1-7.js',
                    'bower_components/jquery-ui/ui/keycode.js',
                    'bower_components/jquery-ui/ui/labels.js',
                    'bower_components/jquery-ui/ui/scroll-parent.js',
                    'bower_components/jquery-ui/ui/tabbable.js',
                    'bower_components/jquery-ui/ui/unique-id.js',
                    'bower_components/jquery-ui/ui/widgets/draggable.js',
                    'bower_components/jquery-ui/ui/widgets/droppable.js',
                    'bower_components/jquery-ui/ui/widgets/resizable.js',
                    'bower_components/jquery-ui/ui/widgets/selectable.js',
                    'bower_components/jquery-ui/ui/widgets/sortable.js',
                    'bower_components/jquery-ui/ui/widgets/mouse.js'
                ],
                dest: '../vendor/jquery-ui/jquery-ui.js'
            }
        },
        copy: {
            main: {
                files: [
                    {expand: true, cwd: 'bower_components/jquery/dist/', src: ['**'], dest: '../vendor/jquery'},
                    {expand: true, cwd: 'bower_components/js-cookie/src/', src: ['**'], dest: '../vendor/js-cookie'},
                    {expand: true, cwd: 'bower_components/moment/', src: ['moment.js'], dest: '../vendor/moment'},
                    {expand: true, cwd: 'bower_components/bootstrap/dist/', src: ['**'], dest: '../vendor/bootstrap'},
                    {expand: true, cwd: 'bower_components/font-awesome/css/', src: ['font-awesome.min.css'], dest: '../vendor/font-awesome/css'},
                    {expand: true, cwd: 'bower_components/font-awesome/fonts/', src: ['**'], dest: '../vendor/font-awesome/fonts'},
                    {expand: true, cwd: 'bower_components/tinymce/', src: ['plugins/**', 'skins/**', 'themes/**', '*.min.js'], dest: '../vendor/tinymce'},
                    {expand: true, cwd: 'bower_components/filemanager-ui/dist/', src: ['**'], dest: '../vendor/filemanager-ui'},
                    {expand: true, cwd: 'bower_components/jquery-ui/base/images', src: ['**'], dest: '../vendor/jquery-ui'},
                    {expand: true, cwd: 'bower_components/jquery-ui/themes/base/', src: ['jquery-ui.min.css'], dest: '../vendor/jquery-ui'}
                ]
            }
        },

        uglify: {
            options: {
                compress: true
            },
            jquery_ui: {
                src: '../vendor/jquery-ui/jquery-ui.js',
                dest: '../vendor/jquery-ui/jquery-ui.min.js'
            },
            datatable_js_min: {
                src: '../vendor/datatables/js/datatables.js',
                dest: '../vendor/datatables/js/datatables.min.js'
            }
        },
        cssmin: {
            datatable_css_min: {
                src: '../vendor/datatables/css/datatables.css',
                dest: '../vendor/datatables/css/datatables.min.css'
            }
        }


    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');         // minifying js files
    grunt.loadNpmTasks('grunt-contrib-cssmin');         // minifying css files


    grunt.registerTask('dev', ['copy', 'concat', 'uglify', 'cssmin']);

};