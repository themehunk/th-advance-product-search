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

      const blockProps = useBlockProps({
                           id:`th-advance-product-search-${attributes.uniqueID}`,
                          className: 'th-advance-product-search-wrapper',
                          });
        return (
			    <>
            <InsSettings
              attributes={attributes}
              setAttributes={setAttributes}
            />
            <div { ...blockProps }>
            {attributes.searchStyle}
            </div>
            
            </>
        )
}