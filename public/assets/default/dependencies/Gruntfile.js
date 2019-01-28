module.exports = function(grunt) {

    grunt.initConfig({
        less: {
            build: {
                files: {
                    '../css/main.css' : ['../less/main.less']
                }
            }
        },
        concat: {
            cssImport: {
                options: {
                    process: function(src) {
                        return "@import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&amp;subset=latin-ext)"+src.replace('@import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&amp;subset=latin-ext)', '');
                    }
                }
            },
            options: {
                separator: '; '
            }
        },
        watch: {
            less: {
                files: [
                    '../less/*.less',
                    '../less/*/*.less',
                    '../less/*/*/*.less',
                    '../less/*/*/*/*.less'
                    ],
                tasks: ['less', 'cssmin']
            },
            js: {
                files: [
                    '../js/pages/*.js',
                    '../js/pages/*/*.js',
                    '../js/pages/*/*/*.js'
                ],
                tasks: ['concat', 'uglify']
            }
        },
        livereload: {
            // Here we watch the files the sass task will compile to
            // These files are sent to the live reload server after sass compiles to them
            options: { livereload: true },
            files: [
                '../js/**/*',
                '../css/**/*'
            ]
        },
        uglify: {
            main_js: {
                files: {
                    '../js/main.min.js': ['../js/*.js', '../js/*/*.js', '!../js/*.min.js', '!../js/pdw.js']
                }
            }
        },
        cssmin: {
            main_css: {
                src: '../css/main.css',
                dest: '../css/main.min.css'
            }
        },
        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
            },
            dev: {
                options: {
                    map: {
                        prev: '../css/'
                    }
                },
                src: '../css/main.css'
            },
            build: {
                src: '../css/main.min.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');         // minifying js files
    grunt.loadNpmTasks('grunt-contrib-cssmin');         // minifying css files
    grunt.loadNpmTasks('grunt-autoprefixer');

    // Register tasks
    grunt.registerTask('default', [
        'build'
    ]);

    grunt.registerTask('build', [
        'less:build',
        'concat',
        'uglify',
        'cssmin',
        'autoprefixer'
    ]);
};