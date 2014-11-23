#
# WP Skeleton
# https://github.com/kevinlangleyjr/wp-foundation
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

      theme: ["wp-content/themes/wp-foundation/js/main.js"]

    uglify:
      theme:
        options:
          preserveComments: "some"

        files:
          "wp-content/themes/wp-foundation/js/main.min.js": [
            "wp-content/themes/wp-foundation/js/main.js"
            "wp-content/themes/wp-foundation/js/skip-link-focus-fix.js"
          ]
          "wp-content/themes/wp-foundation/js/libs.min.js": [
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.abide.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.accordion.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.alert.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.clearing.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.dropdown.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.interchange.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.joyride.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.magellan.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.offcanvas.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.orbit.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.reveal.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.slider.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.tab.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.tooltip.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.topbar.js"
            "wp-content/themes/wp-foundation/js/libs/foundation/foundation.equalizer.js"
          ]

    concat:
      foundation:
        src: [
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.abide.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.accordion.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.alert.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.clearing.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.dropdown.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.interchange.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.joyride.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.magellan.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.offcanvas.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.orbit.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.reveal.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.slider.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.tab.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.tooltip.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.topbar.js"
          "wp-content/themes/wp-foundation/js/libs/foundation/foundation.equalizer.js"
        ]
        dest: "wp-content/themes/wp-foundation/js/libs/foundation.js"

      main:
        src: [
          "wp-content/themes/wp-foundation/js/main.js"
          "wp-content/themes/wp-foundation/js/skip-link-focus-fix.js"
        ]
        dest: "wp-content/themes/wp-foundation/js/main.min.js"

    imagemin:
      theme:
        files: [
          expand: true
          cwd: "wp-content/themes/wp-foundation/img/"
          src: "**/*.{png,jpg}"
          dest: "wp-content/themes/wp-foundation/img/"
        ]

    compass:
      options:
        config: "wp-content/themes/wp-foundation/config.rb"
        basePath: "wp-content/themes/wp-foundation"
        force: true

      production:
        options:
          environment: "production"

      development:
        options:
          environment: "development"

    watch:
      scripts:
        files: "wp-content/themes/wp-foundation/js/**/*.js"
        tasks: [
          "jshint"
          "concat"
        ]

      images:
        files: "wp-content/themes/wp-foundation/img/**/*.{png,jpg,gif}"
        tasks: ["imagemin"]

      composer:
        files: "composer.json"
        tasks: ["composer:update"]

      styles:
        files: "wp-content/themes/wp-foundation/sass/**/*.scss"
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
  grunt.loadNpmTasks "grunt-contrib-compass"
  grunt.loadNpmTasks "grunt-contrib-concat"
  grunt.loadNpmTasks "grunt-contrib-imagemin"
  grunt.loadNpmTasks "grunt-contrib-jshint"
  grunt.loadNpmTasks "grunt-contrib-uglify"
  grunt.loadNpmTasks "grunt-contrib-watch"
  grunt.loadNpmTasks "grunt-peon-build"
  grunt.loadNpmTasks "grunt-composer"

  #set the default task as the development build
  grunt.registerTask "default", ["build:development"]