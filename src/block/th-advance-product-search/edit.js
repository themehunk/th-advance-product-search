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
	useEffect,useState
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
	setAttributes }) {

// Utility function to generate a unique ID
const generateUniqueId = () => {
  const randomPart = Math.random().toString(36).substring(2, 10); // Generate random alphanumeric string
  return `-${randomPart}`;
};

// Ensure the uniqueID is set if it's not
if (!attributes.uniqueID) {
  setAttributes({ uniqueID: generateUniqueId() });
}
const getDevice = useSelect((select) => {
    // Device type selectors for different editors
    const siteEditorDeviceType = select('core/edit-site')?.getPreviewDeviceType?.();
    const blockEditorDeviceType = select('core/editor')?.getDeviceType?.();
    // Fallback to 'Desktop' if neither device type is available
    return siteEditorDeviceType || blockEditorDeviceType || 'Desktop';
}, []);

       // Detect if we're in the Customizer
        const isCustomizer = typeof wp !== 'undefined' && wp?.customize;

        // Local state to track Customizer device
        // Local state to track Customizer device in uppercase
        const [customizerDevice, setCustomizerDevice] = useState(
            isCustomizer && wp.customize?.previewedDevice
                ? (wp.customize.previewedDevice.get() || 'desktop').charAt(0).toUpperCase() +
                  (wp.customize.previewedDevice.get() || 'desktop').slice(1)
                : 'Desktop'
        );

        // Sync Customizer device changes
          useEffect(() => {
          if (isCustomizer && wp.customize?.previewedDevice) {
              const handleDeviceChange = (newDevice) => {
                  const capitalizedDevice = newDevice.charAt(0).toUpperCase() + newDevice.slice(1);
                  //console.log('Customizer device changed:', capitalizedDevice);
                  setCustomizerDevice(capitalizedDevice);
              };
              wp.customize.previewedDevice.bind(handleDeviceChange);
              return () => {
                  wp.customize.previewedDevice.unbind(handleDeviceChange);
              };
          }
      }, [isCustomizer]);

      let getView = '';

    if (isCustomizer && wp.customize?.previewedDevice) {
        getView = customizerDevice || 'Desktop';
    } else {
        getView = getDevice || 'Desktop';
    }

const isDesktop = getView === 'Desktop';
const isTablet = getView === 'Tablet';
const isMobile = getView === 'Mobile';

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