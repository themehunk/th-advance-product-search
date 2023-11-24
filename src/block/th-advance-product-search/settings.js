import { __ } from '@wordpress/i18n';
import {AlignmentToolbar, __experimentalColorGradientControl as ColorGradientControl,InspectorControls} from '@wordpress/block-editor';

import {
    PanelBody,
    RangeControl,
    SelectControl,
    Placeholder,
    Spinner,
    ToggleControl,
} from '@wordpress/components';

import { useSelect } from '@wordpress/data';
import {
	RichText
} from '@wordpress/block-editor';

import {
    Fragment,
    useState,
    Suspense
} from '@wordpress/element';

const InsSettings = ({
    attributes,
    setAttributes
}) => {

    const adminUrlsearchh = ThBlockDataSearch.adminUrlsearch;

    return (<Fragment>
        <InspectorControls>
        <PanelBody
					title={ __( 'TH Advance Search Setting', 'vayu-blocks' ) }
					initialOpen={true}
					className="th-sEARCH-panel"
				>   
        <SelectControl
								label={ __( 'Search Style', 'th-advance-product-search' ) }
								value={ attributes.searchStyle }
								options={ [
									{ label: __( 'Default', 'th-advance-product-search' ), value: '[th-aps]' },
									{ label: __( 'bar-style', 'th-advance-product-search' ), value: '[th-aps layout="bar_style"]' },
									{ label: __( 'icon-style', 'th-advance-product-search' ), value: '[th-aps layout="icon_style"]' },
								    { label: __( 'flexible-style', 'th-advance-product-search' ), value: '[th-aps layout="flexible-style"]' },
								] }
								onChange={ e => setAttributes({ searchStyle: e }) }
							/>
                             <p>
                        {__(
                            'For the ',
                            'th-advance-product-search'
                        )}
                        <strong>{__('Advanced Options and Styling', 'th-advance-product-search')}</strong>
                           
                        {__(
                            ' navigate to ',
                            'th-advance-product-search'
                        )}
                        <a
                            href={adminUrlsearchh}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {__('Settings', 'th-advance-product-search')}
                        </a>
                    
                    </p>
                            </PanelBody>
        </InspectorControls>
    </Fragment>)   
}

export default InsSettings;
