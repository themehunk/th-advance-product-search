
import { __ } from '@wordpress/i18n';

const Thsearchbar = ({placeholder}) => {
  return (
    <div id="thaps-search-box" className="thaps-search-box bar_style">
      <form className="thaps-search-form"  id="thaps-search-form" role="search" >
        <div className="thaps-from-wrap">
          <span className="th-icon th-icon-vector-search icon-style"></span>
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

export default Thsearchbar;
