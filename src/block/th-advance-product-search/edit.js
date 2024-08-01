/**
 * WordPress dependencies.
 */
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import { useViewportMatch, useMediaQuery} from '@wordpress/compose';
import { useSelect, useDispatch  } from '@wordpress/data';

import hexToRgba from 'hex-rgba';
import {
	useEffect
} from '@wordpress/element';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import InsSettings from './settings.js';
import Thsearchdefault from './thsearchdefault.js';
import Thsearchbar from './thsearchbar.js';
import Thsearchicon from './thsearchicon.js';
import Thsearchflexi from './thsearchflexi.js';
export default function Edit({ attributes, 
	setAttributes, 
	clientId,
	uniqueID }) {

let counter = 0;
function getUniqueId( uniqueID, clientId, isUniqueID, isUniqueBlock ) {
    
    let smallID = '_' + clientId.substr( 2, 9 );
    if ( ! uniqueID ) {
        //new block
        if ( ! isUniqueID( smallID ) ) {
            smallID = generateUniqueId(smallID);
        }
        return smallID;
    } else if ( ! isUniqueID( uniqueID ) ) {
        // This checks if we are just switching views, client ID the same means we don't need to update.
        if ( ! isUniqueBlock( uniqueID, clientId ) ) {
            return smallID
        }
    }
    //normal block loading 
    return uniqueID;
}

function generateUniqueId(smallID) {
  counter += 1;
  return `${smallID}${counter}`;
}
      const { id } = attributes;
			const { addUniqueID } = useDispatch( 'th-advance-product-search/data' );
			const { isUniqueID, isUniqueBlock} = useSelect(
				( select ) => {
					return {
						isUniqueID: ( value ) => select( 'th-advance-product-search/data' ).isUniqueID( value ),
						isUniqueBlock: ( value, clientId ) => select( 'th-advance-product-search/data' ).isUniqueBlock( value, clientId ),
						
					};
				},
				[ clientId ]
			);

			useEffect( () => {
			const uniqueId = getUniqueId( uniqueID, clientId, isUniqueID, isUniqueBlock );
			if ( uniqueId !== uniqueID ) {
				attributes.uniqueID = uniqueId;
				setAttributes( { uniqueID: uniqueId } );
				addUniqueID( uniqueId, clientId );
			} else {
				addUniqueID( uniqueId, clientId );
			}
			}, [] );

      const {
        isViewportAvailable,
        isPreviewDesktop,
        isPreviewTablet,
        isPreviewMobile
      } = useSelect( select => {
        const { __experimentalGetPreviewDeviceType } = select( 'core/edit-post' ) ? select( 'core/edit-post' ) : false;
    
        return {
          isViewportAvailable: __experimentalGetPreviewDeviceType ? true : false,
          isPreviewDesktop: __experimentalGetPreviewDeviceType ? 'Desktop' === __experimentalGetPreviewDeviceType() : false,
          isPreviewTablet: __experimentalGetPreviewDeviceType ? 'Tablet' === __experimentalGetPreviewDeviceType() : false,
          isPreviewMobile: __experimentalGetPreviewDeviceType ? 'Mobile' === __experimentalGetPreviewDeviceType() : false
        };
      }, []);
    
      const isLarger = useViewportMatch( 'large', '>=' );
    
      const isLarge = useViewportMatch( 'large', '<=' );
    
      const isSmall = useViewportMatch( 'small', '>=' );
    
      const isSmaller = useViewportMatch( 'small', '<=' );
    
      let isDesktop = isLarger && ! isLarge && isSmall && ! isSmaller;
    
      let isTablet = ! isLarger && ! isLarge && isSmall && ! isSmaller;
    
      let isMobile = ! isLarger && ! isLarge && ! isSmall && ! isSmaller;
    
        if ( isViewportAvailable && ! isMobile ) {
        isDesktop = isPreviewDesktop;
        isTablet = isPreviewTablet;
        isMobile = isPreviewMobile;
      }

      const deviceAttributeMap = {
        desktop: {
          searchWidth: attributes.searchWidth + attributes.searchWidthUnit,
          barborderRadius: attributes.barborderRadius + attributes.barborderRadiusUnit,
        },
        tablet: {
          searchWidth: attributes.searchWidthTablet + attributes.searchWidthUnitTablet,
          barborderRadius: attributes.barborderRadiusTablet + attributes.barborderRadiusUnit,
        },
        mobile: {
          searchWidth: attributes.searchWidthMobile + attributes.searchWidthUnitMobile,
          barborderRadius: attributes.barborderRadiusMobile + attributes.barborderRadiusUnit,
        }

      }

      const deviceType  = isDesktop ? 'desktop' : isTablet ? 'tablet' : 'mobile';
      const searchWidth = deviceAttributeMap[deviceType].searchWidth;
      const barborderRadius = deviceAttributeMap[deviceType].barborderRadius;
      
      let iconClr = '';

      if (attributes.searchStyle === 'default') {
          iconClr = attributes.searchIconClr || '#fff';
      } else {
          iconClr = attributes.searchIconClr || '#111';
      }
      
      let tapspStyle;
      tapspStyle = {
        '--tapsp-width':searchWidth,
        '--tapsp-bar-radius':barborderRadius,
        '--tapsp-bar-bg-clr':attributes.searchBarClr,
        '--tapsp-bar-text-clr':attributes.searchTextClr,
        '--tapsp-icon-clr':iconClr,
        '--tapsp-button-bg-clr':attributes.searchBtnBgClr,
        '--tapsp-button-txt-clr':attributes.searchBtnTextClr,
        '--tapsp-button-hvr-bg-clr':attributes.searchBtnHvrBgClr,
        '--tapsp-button-hvr-txt-clr':attributes.searchBtnHvrTextClr,
        '--tapsp-search-border-clr':attributes.searchborder
       
      }

      const omitBy = (object, condition) => (
        Object.fromEntries(
          Object.entries(object).filter(([key, value]) => !condition(value))
        )
      );

      const style = omitBy({
        ...tapspStyle,
      }, x => x?.includes?.( 'undefined' ));

      const blockProps = useBlockProps({
        id:`th-advance-product-search-${attributes.uniqueID}`,
        className: 'th-advance-product-search-wrapper',
        style
        });
        return (
         <>
           <InsSettings attributes={attributes} setAttributes={setAttributes} />
           <div {...blockProps}>
             {attributes.searchStyle === 'default' && 
             <Thsearchdefault placeholder={attributes.placeholderText}
                              submitLabel={attributes.submitText}
                              disableSubmit={attributes.disableSubmit}/>}
             {attributes.searchStyle === 'bar' && <Thsearchbar placeholder={attributes.placeholderText} />}
             {attributes.searchStyle === 'icon' && <Thsearchicon placeholder={attributes.placeholderText} />}
             {attributes.searchStyle === 'flexi' && <Thsearchflexi placeholder={attributes.placeholderText} />}
             {!['default', 'bar', 'icon', 'flexi'].includes(attributes.searchStyle) && <Thsearchdefault />}
           </div>
         </>
       );
     }