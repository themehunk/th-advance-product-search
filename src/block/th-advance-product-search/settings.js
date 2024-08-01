/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import {InspectorAdvancedControls , __experimentalColorGradientControl as ColorGradientControl,InspectorControls,PanelColorSettings} from '@wordpress/block-editor';
import {
    PanelBody,
    RangeControl,
    SelectControl,
    Placeholder,
    Spinner,
    ToggleControl,TextControl,
    __experimentalBorderBoxControl as BorderBoxControl
} from '@wordpress/components';

import { useSelect } from '@wordpress/data';

import {
    Fragment,
    useState,
    Suspense
} from '@wordpress/element';

/**
* Internal dependencies
*/
import {
	InsSettingHeader,
	ResponsiveControl,
	UnitChooser,
} from './components/index.js';

const InsSettings = ({
    attributes,
    setAttributes
}) => {

    const adminUrlsearchh = ThBlockDataSearch.adminUrlsearch;
    const [ tab, setTab ] = useState( 'setting' );
    const getView = useSelect( select => {

        const { getView } = select( 'th-advance-product-search/data' );
    
        const { __experimentalGetPreviewDeviceType } = select( 'core/edit-post' ) ? select( 'core/edit-post' ) : false;
    
        return __experimentalGetPreviewDeviceType ? __experimentalGetPreviewDeviceType() : getView();
    
        }, []);

        const getsearchWidthUnitValue = () => {
            switch (getView) {
              case 'Desktop':
                return attributes.searchWidthUnit;
              case 'Tablet':
                return attributes.searchWidthUnitTablet;
              case 'Mobile':
                return attributes.searchWidthUnitMobile;
              default:
                break;
            }
          };
    
          const handlesearchWidthUnitChange = (searchWidthUnit) => {
            switch (getView) {
              case 'Desktop':
                setAttributes({ ...attributes, searchWidthUnit: searchWidthUnit });
                break;
              case 'Tablet':
                setAttributes({ ...attributes, searchWidthUnitTablet: searchWidthUnit });
                break;
              case 'Mobile':
                setAttributes({ ...attributes, searchWidthUnitMobile: searchWidthUnit });
                break;
              default:
                break;
            }
          };
    
          const getsearchWidth = () => {
            switch (getView) {
              case 'Desktop':
                return attributes.searchWidth;
              case 'Tablet':
                return attributes.searchWidthTablet;
              case 'Mobile':
                return attributes.searchWidthMobile !== undefined ? attributes.searchWidthMobile : 100; // Default value for mobile view
              default:
                return undefined;
            }
          };
          
          const changesearchWidth = (value) => {
            switch (getView) {
              case 'Desktop':
                setAttributes({ searchWidth: value });
                break;
              case 'Tablet':
                setAttributes({ searchWidthTablet: value });
                break;
              case 'Mobile':
                setAttributes({ searchWidthMobile: value });
                break;
              default:
                break;
            }
          };
    
            let maxsearchWidth;
      
            if (getView === 'Desktop') {
            if (attributes.searchWidthUnit === 'px') {
                maxsearchWidth = 2500;
            } else if (attributes.fsearchWidthUnit === 'em') {
                maxsearchWidth = 50;
            } else if (attributes.searchWidthUnit === '%') {
                maxsearchWidth = 100;
            }
            } else if (getView === 'Tablet') {
      // Set maxBoxedcontentWidth for Tablet
    
      if (attributes.searchWidthUnitTablet === 'px') {
          maxsearchWidth = 2500;
      } else if (attributes.searchWidthTablet === 'em') {
          maxsearchWidth = 50;
      } else if (attributes.searchWidthTablet === '%') {
          maxsearchWidth = 100;
      }
      } else if (getView === 'Mobile') {
      // Set maxBoxedcontentWidth for Mobile
    
      if (attributes.searchWidthUnitMobile === 'px') {
          maxsearchWidth = 2500;
      } else if (attributes.searchWidthUnitMobile === 'em') {
          maxsearchWidth = 50;
      } else if (attributes.searchWidthMobile === '%') {
          maxsearchWidth = 100;
      }
    }
    
        const customTooltipsearchWidth = value => `${value}${attributes.searchWidthUnit}`;	
    
        // border radius
          const [barborderRadiusUnit, setbarborderRadiusUnit] = useState('px');
          const maxbarborderRadiusUnit = barborderRadiusUnit === 'px' ? 20 : barborderRadiusUnit === 'em' ? 5 : barborderRadiusUnit === '%' ? 100:'';
          const customTooltipbarborderRadius = value => `${value}${attributes.barborderRadiusUnit}`;
    
          const getbarborderRadius = () => {
            switch ( getView ) {
            case 'Desktop':
              return attributes.barborderRadius;
            case 'Tablet':
              return attributes.barborderRadiusTablet;
            case 'Mobile':
              return attributes.barborderRadiusMobile;
            default:
              return undefined;
            }
          };
          const changebarborderRadius = value => {
            if ( 'Desktop' === getView ) {
              setAttributes({ barborderRadius: value, barborderRadiusTablet: value, barborderRadiusMobile: value });
            } else if ( 'Tablet' === getView ) {
              setAttributes({ barborderRadiusTablet: value, barborderRadiusMobile: value });
            } else if ( 'Mobile' === getView ) {
              setAttributes({ barborderRadiusMobile: value });
            }
          };    

    return (<Fragment>
        <InspectorControls>
        <InsSettingHeader value={ tab }
					options={[
						{
							label: __( 'Setting', 'th-advance-product-search-pro' ),
							value: 'setting',
                            icon:''
							
						},
						{
							label: __( 'Style', 'th-advance-product-search-pro' ),
							value: 'style',
                            icon:''
							
						}
					]}
					onChange={ setTab }
            />
        {'setting' === tab && (    
        <Fragment>       
        <PanelBody
					title={ __( 'TH Advance Search Setting', 'vayu-blocks' ) }
					initialOpen={true}
					className="th-sEARCH-panel"
				>   
                <SelectControl
								label={ __( 'Search Style', 'th-advance-product-search' ) }
								value={ attributes.searchStyle }
								options={ [
									{ label: __( 'Default', 'th-advance-product-search' ), value: 'default' },
									{ label: __( 'bar-style', 'th-advance-product-search' ), value: 'bar' },
									{ label: __( 'icon-style', 'th-advance-product-search' ), value: 'icon' },
								    { label: __( 'flexible-style', 'th-advance-product-search' ), value: 'flexi' },
								] }
								onChange={ e => setAttributes({ searchStyle: e }) }
							/>

<ResponsiveControl
                            label={ __( 'Search Width', 'th-advance-product-search-pro' ) }
                            >	
                            <UnitChooser
                            value={getsearchWidthUnitValue()}
                            onClick={(searchWidthUnit) => {
                                handlesearchWidthUnitChange(searchWidthUnit);
                            }}
                            units={['px', 'em', '%']}
                            />
                            <RangeControl
                            renderTooltipContent={ customTooltipsearchWidth }
							              initialPosition={getsearchWidth()}
                            value={ getsearchWidth() || '' }
                            onChange={ changesearchWidth }
                            step={ 1 }
                            min={ 1 }
                            max={maxsearchWidth}
                            allowReset={ true }
                            />
                            </ResponsiveControl>
                            <ResponsiveControl
                                label={ __( 'Bar border radius', 'th-advance-product-search-pro' ) }
                              >
                                <UnitChooser
                                value={ attributes.barborderRadiusUnit }
                                onClick={barborderRadiusUnit => {
                                  setAttributes({ barborderRadiusUnit });
                                  setbarborderRadiusUnit(barborderRadiusUnit);
                                  }}
                      
                                units={ [ 'px', 'em', '%' ] }
                                    />
                              <RangeControl
                                    renderTooltipContent={ customTooltipbarborderRadius }
                                  value={ getbarborderRadius() || '' }
                                  onChange={ changebarborderRadius }
                                  step={ 0.1 }
                                  min={ 0 }
                                  max={ maxbarborderRadiusUnit }
                                  allowReset={ true }
                                />
                              </ResponsiveControl>       
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
                            </Fragment>
                             ) || 'style' === tab && ( 

              <PanelBody
              title={ __( 'TH Advance Search Style', 'th-advance-product-search' ) }
              initialOpen={true}
              className="th-search-panel"> 
              <PanelColorSettings
                    title={__('Search Color', 'th-advance-product-search')}
                    initialOpen={true}
                    colorSettings={[
                        {
                            value: attributes.searchBarClr,
                            onChange: (searchBarClr) => setAttributes({ searchBarClr: searchBarClr}),
                            label: __('Search bar', 'th-advance-product-search'),
                        },
                        {
                          value: attributes.searchborder,
                          onChange: (searchborder) => setAttributes({ searchborder: searchborder}),
                          label: __('Search Border', 'th-advance-product-search'),
                        } ,
                        {
                          value: attributes.searchTextClr,
                          onChange: (searchTextClr) => setAttributes({ searchTextClr: searchTextClr }),
                          label: __('Search Text', 'th-advance-product-search'),
                        },
                        {
                          value: attributes.searchIconClr,
                          onChange: (searchIconClr) => setAttributes({ searchIconClr: searchIconClr}),
                          label: __('Search Icon', 'th-advance-product-search'),
                        }  
                    ]}
                />
                <PanelColorSettings
                    title={__('Search Button', 'th-advance-product-search')}
                    initialOpen={true}
                    colorSettings={[
                        {
                            value: attributes.searchBtnBgClr,
                            onChange: (searchBtnBgClr) => setAttributes({ searchBtnBgClr:searchBtnBgClr}),
                            label: __('Background', 'th-advance-product-search'),
                        },
                        {
                          value: attributes.searchBtnTextClr,
                          onChange: (searchBtnTextClr) => setAttributes({ searchBtnTextClr: searchBtnTextClr }),
                          label: __('Text', 'th-advance-product-search'),
                        },
                        {
                          value: attributes.searchBtnHvrBgClr,
                          onChange: (searchBtnHvrBgClr) => setAttributes({ searchBtnHvrBgClr: searchBtnHvrBgClr}),
                          label: __('Hover Background', 'th-advance-product-search'),
                        },
                        {
                          value: attributes.searchBtnHvrTextClr,
                          onChange: (searchBtnHvrTextClr) => setAttributes({ searchBtnHvrTextClr: searchBtnHvrTextClr}),
                          label: __('Hover Text', 'th-advance-product-search'),
                        }
                        
                    ]}
                />
                
                </PanelBody>
                             )}
        </InspectorControls>
    </Fragment>)   
}

export default InsSettings;
