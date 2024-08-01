
import { __ } from '@wordpress/i18n';

const Thsearchdefault = ({placeholder,submitLabel,disableSubmit}) => {
 
  return (
    <div id="thaps-search-box" className='thaps-search-box submit-active default_style'>
      <form role="search" className="thaps-search-form">
        <div className="thaps-from-wrap">
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
          <div className="thaps-preloader" style={{ right: '79.125px' }}>
          <span className="thaps_loader-css"></span>
          </div>
          <button id="thaps-search-button" value="Submit" type="submit">
          {disableSubmit ? (
            <span className="th-icon th-icon-vector-search icon-style" style={{color: ''}}></span>
          ) : (
            submitLabel ? submitLabel : 'Submit'
          )}
          </button>
          <input type="hidden" name="post_type" value="" />
          <span className="label label-default" id="selected_option"></span>
        </div>
      </form>
    </div>
  );
};

export default Thsearchdefault;
