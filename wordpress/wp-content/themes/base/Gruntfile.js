'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({

        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {
            clear: {
                //clear terminal on any watch task. beauty.
                files: ['sass/**/*.{scss,sass}', 'js/build/*.js', 'images/**/*', '*.php', 'includes/{,*/}*.php'], //or be more specific
                tasks: ['clear']
            },
            imagemin: {
                files: ['images/**/*'],
                tasks: ['imagemin', 'notify:success']
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify', 'notify:success']
            },
            php: {
                files: ['*.php', 'includes/{,*/}*.php'],
                tasks: ['notify:success']
            },
            html: {
                files: ['*.html'],
                tasks: ['notify:success']
            },
            sass: {
                files: ['sass/**/*.{scss,sass}'],
                tasks: ['sass', 'prettysass', 'notify:success']
            },
            options: {
                livereload: true
            }
        },

        sass: {
            dist: {
              options: {
                style: 'compressed',
                //sourcemap: 'true',
                compass: true
              },
              files: {
                'style.css': 'sass/style.scss',
                //'ie.css': 'sass/ie.scss'
              }
            }
          },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                "force": true,
                spawn: false
            },
            all: [
                'Gruntfile.js',
                'js/build/**/*.js'
            ]
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            build: {
                options: {
                    mangle: false
                },
                files: {
                    'js/app.min.js': ['js/build/*.js']
                }
            }
        },

        //Sort SASS properties alphabetically and indent
        prettysass: {
            options: {
                alphabetize: true,
                indent: 4
            },
            app: {
                src: ['sass/**/*.scss']
            },
        },

        // Automatic desktop notifications for Grunt errors and warnings 
        notify: {
            success: {
                options: {
                    title: 'Task Completed',
                    message: 'finished running'
                }
            }
        },

        // image optimization
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 7,
                    progressive: true
                },
                files: [{
                    expand: true,
                    cwd: 'images/',
                    src: ['**/*.{png,jpg,gif}'],
                    dest: 'images/'
                }]
            }
        }

    });

    // register task
    grunt.registerTask('default', ['watch']);
};