module.exports = function(grunt) { //The wrapper function

 // Project configuration & task configuration
 grunt.initConfig({
      pkg: grunt.file.readJSON('package.json'),

      pot: {
            options:{
                text_domain: 'rwop', //Your text domain. Produces my-text-domain.pot
                dest: 'languages/', //directory to place the pot file
                keywords: [ //WordPress localisation functions
                  '__:1',
                  '_e:1',
                  '_x:1,2c',
                  'esc_html__:1',
                  'esc_html_e:1',
                  'esc_html_x:1,2c',
                  'esc_attr__:1', 
                  'esc_attr_e:1', 
                  'esc_attr_x:1,2c', 
                  '_ex:1,2c',
                  '_n:1,2', 
                  '_nx:1,2,4c',
                  '_n_noop:1,2',
                  '_nx_noop:1,2,3c'
                 ]
            },
            files:{
                src:  [ 'register-with-password.php' ], //Parse all php files
                expand: true
            }
      }      
  });

  grunt.loadNpmTasks('grunt-pot');

  // Default task(s), executed when you run 'grunt'
  grunt.registerTask('default', ['pot']);
};