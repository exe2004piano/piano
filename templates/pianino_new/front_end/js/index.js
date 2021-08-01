/****libs******/
window.$ = window.jQuery = require('jquery')
window.WebFont = require('webfontloader')
WebFont.load({
	google: {
		families: ['Montserrat:400,700:&display=swap'],
	},
})

/*******modules********/
require('./modules/Loader.js');
require('./modules/HeaderSearch.js');