const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
module.exports = {
	...defaultConfig,
  entry:{
     'th-advance-product-search':'./src/block/th-advance-product-search',
     'thaps-component-editor': './src/block/th-advance-product-search/components/component-editor.scss',
     }
};