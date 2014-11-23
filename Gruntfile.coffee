#
# WP Skeleton
# https://github.com/voceconnect/wp-skeleton
#

module.exports = (grunt) ->

  # Project configuration.
  grunt.initConfig
    jshint:
      options:
        curly: true
        eqeqeq: true
        eqnull: true
        browser: true
        plusplus: true
        undef: true
        unused: true
        trailing: true
        globals:
          jQuery: true
          $: true
          ajaxurl: true

      theme: ["wp-content/themes/wp-skeleton/js/main.js"]

    uglify:
      theme:
        options:
          preserveComments: "some"

        files:
          "wp-content/themes/wp-skeleton/js/main.min.js": [
            "wp-content/themes/wp-skeleton/js/main.js"
            "wp-content/themes/wp-skeleton/js/skip-link-focus-fix.js"
          ]
          "wp-content/themes/wp-skeleton/js/libs/bootstrap.min.js": [
            "wp-content/themes/wp-skeleton/js/libs/bootstrap/**/*.js"
            "!wp-content/themes/wp-skeleton/js/libs/bootstrap/popover.js"
            "wp-content/themes/wp-skeleton/js/libs/bootstrap/popover.js"
          ]

    concat:
      bootstrap:
        src: [
          "wp-content/themes/wp-skeleton/js/libs/bootstrap/**/*.js"
          "!wp-content/themes/wp-skeleton/js/libs/bootstrap/popover.js"
          "wp-content/themes/wp-skeleton/js/libs/bootstrap/popover.js"
        ]
        dest: "wp-content/themes/wp-skeleton/js/libs/bootstrap.js"

      main:
        src: [
          "wp-content/themes/wp-skeleton/js/main.js"
          "wp-content/themes/wp-skeleton/js/skip-link-focus-fix.js"
        ]
        dest: "wp-content/themes/wp-skeleton/js/main.min.js"

    imagemin:
      theme:
        files: [
          expand: true
          cwd: "wp-content/themes/wp-skeleton/img/"
          src: "**/*.{png,jpg}"
          dest: "wp-content/themes/wp-skeleton/img/"
        ]

    compass:
      options:
        config: "wp-content/themes/wp-skeleton/config.rb"
        basePath: "wp-content/themes/wp-skeleton"
        force: true

      production:
        options:
          environment: "production"

      development:
        options:
          environment: "development"

    watch:
      scripts:
        files: "wp-content/themes/wp-skeleton/js/**/*.js"
        tasks: [
          "jshint"
          "concat"
        ]

      images:
        files: "wp-content/themes/wp-skeleton/img/**/*.{png,jpg,gif}"
        tasks: ["imagemin"]

      composer:
        files: "composer.json"
        tasks: ["composer:update"]

      styles:
        files: "wp-content/themes/wp-skeleton/sass/**/*.scss"
        tasks: ["compass"]

    build:
      production: [
        "uglify"
        "composer:install:no-dev:optimize-autoloader"
        "compass:production"
      ]
      uat: [
        "uglify"
        "composer:install:no-dev:optimize-autoloader"
        "compass:production"
      ]
      staging: [
        "concat"
        "composer:install"
        "compass:development"
      ]
      development: [
        "concat"
        "composer:install"
        "compass:development"
      ]


  #load the tasks
  grunt.loadNpmTasks "grunt-voce-plugins"

  #set the default task as the development build
  grunt.registerTask "default", ["build:development"]