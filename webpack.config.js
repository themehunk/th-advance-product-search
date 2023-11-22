const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
module.exports = {
	...defaultConfig,
  entry:{
     'th-advance-product-search':'./src/block/th-advance-product-search',
     'th-advance-product-search-data':'./src/data',
  }
};