import React from 'react';
import { __ } from '@wordpress/i18n';

const Thsearchflexi = ({placeholder}) => {
  return (
    <div id="thaps-search-box" className="thaps-search-box bar_style flexible-style">
      <form 
        className="thaps-search-form" 
        id="thaps-search-form" 
        role="search" 
        method="get"
      >
        <div className="thaps-from-wrap">
           <button id="thaps-search-button" value="Submit" type="submit">
          <span className="th-icon th-icon-vector-search icon-style"></span>
          </button>
          <input 
            id="thaps-search-autocomplete-1" 
            name="s" 
            placeholder={placeholder} 
            className="thaps-search-autocomplete thaps-form-control" 
            
            type="search" 
            title="Search" 
            data-custom-post-type="" 
            autoComplete="off" 
          />
          <div className="thaps-preloader"></div>
          <input type="hidden" name="post_type" value="product" />
          <span className="label label-default" id="selected_option"></span>
        </div>
      </form>
    </div>
  );
};

export default Thsearchflexi;
