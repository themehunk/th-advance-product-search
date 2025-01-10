/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, Dropdown, Icon } from '@wordpress/components';
import { useInstanceId, useViewportMatch } from '@wordpress/compose';
import { useSelect, useDispatch } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { checkIcon } from '../icon.js';

const ResponsiveControl = ({ label, className, children }) => {
    const instanceId = useInstanceId(ResponsiveControl);

    const isLarger = useViewportMatch('large', '>=');
    const isLarge = useViewportMatch('large', '<=');
    const isSmall = useViewportMatch('small', '>=');
    const isSmaller = useViewportMatch('small', '<=');
    const isMobile = !isLarger && !isLarge && !isSmall && !isSmaller;

    // Get current device view
    const getView = useSelect((select) => {
        const siteEditorDeviceType = select('core/edit-site')?.getPreviewDeviceType?.();
        const blockEditorDeviceType = select('core/edit-post')?.__experimentalGetPreviewDeviceType?.();
        return siteEditorDeviceType || blockEditorDeviceType || 'Desktop'; // Default to 'Desktop'
    }, []);

    // Update the current device view
    const { __experimentalSetPreviewDeviceType: setBlockEditorDeviceType } = useDispatch('core/edit-post') || {};
    const { setPreviewDeviceType: setSiteEditorDeviceType } = useDispatch('core/edit-site') || {};
    const setView = (deviceType) => {
        if (setBlockEditorDeviceType) {
            setBlockEditorDeviceType(deviceType);
        } else if (setSiteEditorDeviceType) {
            setSiteEditorDeviceType(deviceType);
        }
    };

    const id = `inspector-responsive-control-${instanceId}`;

    return (
        <div id={id} className={classnames('o-responsive-control', className)}>
            <div className="components-base-control__field">
                <div className="components-base-control__title">
                    <label className="components-base-control__label">{label}</label>
                    <div className="floating-controls">
                        <Dropdown
                            position="top left"
                            renderToggle={({ isOpen, onToggle }) => (
                                <Button
                                    icon={'Mobile' === getView ? 'smartphone' : getView.toLowerCase()}
                                    label={__('Responsiveness Settings', 'vayu-blocks')}
                                    showTooltip={true}
                                    className="is-button"
                                    onClick={onToggle}
                                    aria-expanded={isOpen}
                                />
                            )}
                            renderContent={() => (
                                <div className="o-responsive-control-settings">
                                    <div className="o-responsive-control-settings-title">
                                        {__('View', 'vayu-blocks')}
                                    </div>

                                    {['Desktop', 'Tablet', 'Mobile'].map((device) => (
                                        <Button
                                            key={device}
                                            className={classnames('o-responsive-control-settings-item', {
                                                'is-selected': device === getView,
                                            })}
                                            onClick={() => setView(device)}
                                        >
                                            {device === getView && <Icon icon={checkIcon} />}
                                            <span className="popover-title">{__(device, 'vayu-blocks')}</span>
                                        </Button>
                                    ))}
                                </div>
                            )}
                        />
                    </div>
                </div>
                {children}
            </div>
        </div>
    );
};

export default ResponsiveControl;
