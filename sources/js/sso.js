/**
 * Bootstrap - Starter Kit
 *
 * @author Prismify
 * @version 1.0.6
 */

// require('more-packages-installed-with-npm-install');
require('jquery-validation');
require('@popperjs/core');
//window.bootstrap =  require('bootstrap');
window.$ = require('jquery')

$(function() {
    'use-strict'
//    require('./helpers/jquery.validate.min');
    require('./helpers/tooltip');
    require('./helpers/reg_form_validate');
    require('./helpers/form_validate')

    // Helpers
    // require('.abstracts/more-abstracts');

    // Components
    // require('./components/more-components');
});
