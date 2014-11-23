/*
 * WP Skeleton
 * https://github.com/voceconnect/wp-skeleton
 */

'use strict';

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    "imagemin": {
      "theme": {
        "files": [
          {
            "expand": true,
            "cwd": "wp-content/themes/wp-skeleton/",
            "src": "**/*.{png,jpg}",
            "dest": "wp-content/themes/wp-skeleton/"
          }
        ]
      }
    },
    "watch": {
      "images": {
        "files": "wp-content/themes/wp-skeleton/images/**/*.{png,jpg,gif}",
        "tasks": ["imagemin"]
      }
    },
    "build": {
      "production": [ "composer:install:no-dev:optimize-autoloader" ],
      "uat": [ "composer:install:no-dev:optimize-autoloader"],
      "staging": [ "composer:install" ],
      "development": [ "composer:install" ]
    }
  });

  //load the tasks
  grunt.loadNpmTasks('grunt-voce-plugins');

  //set the default task as the development build
  grunt.registerTask('default', ['build:development']);

};