import { __ } from '@wordpress/i18n';

import {useState} from '@wordpress/element';
const Thsearchicon = ({placeholder}) => {
  const [isOpen, setIsOpen] = useState(false);

  const handleIconClick = () => {
    setIsOpen(!isOpen);
  };

  return (
    <div id="thaps-search-box" className={`thaps-search-box icon_style ${isOpen ? 'thaps-box-open' : ''}`}>
      <span className="th-icon th-icon-vector-search click-icon" style={{ color: '' }} onClick={handleIconClick}></span>
      <div className="thaps-icon-arrow" style={{}}></div>
      <form 
        className="thaps-search-form" 
        id="thaps-search-form" 
        role="search" 
        style={{ left: '-174px' }}
      >
        <div className="thaps-from-wrap">
          <span className="th-icon th-icon-vector-search icon-style" style={{ color: '' }}></span>
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

export default Thsearchicon;