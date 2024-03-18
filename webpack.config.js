const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		// Add entry points for each block script
		//block: './block/index.js',
		editor: './src/js/editor.js',
	},
	output: {
		path: defaultConfig.output.path,
		filename: '[name].js',
	},
};
