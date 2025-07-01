/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, Dropdown, Icon } from '@wordpress/components';
import { useInstanceId } from '@wordpress/compose';
import { useSelect, useDispatch } from '@wordpress/data';
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { checkIcon } from '../icon.js';

const ResponsiveControl = ({ label, className, children }) => {
    const instanceId = useInstanceId(ResponsiveControl);

    // Detect if we're in the Customizer
    const isCustomizer = typeof wp !== 'undefined' && wp?.customize;

    // Get current device view
    const getView = useSelect((select) => {
        if (isCustomizer && wp.customize?.previewedDevice) {
            // Get device from Customizer
            return wp.customize.previewedDevice.get() || 'desktop';
        }
        // Use core/editor for block editor device type
        const deviceType = select('core/editor')?.getDeviceType?.();
        return deviceType || 'Desktop';
    }, []);

    // Local state to track device view
    const [currentView, setCurrentView] = useState(getView);

    // Update the current device view
    const { setDeviceType } = useDispatch('core/editor') || {};
    const { setPreviewDeviceType: setSiteEditorDeviceType } = useDispatch('core/edit-site') || {};

    const setView = (deviceType) => {
        // Normalize device type for Customizer (lowercase)
        const normalizedDevice = deviceType.toLowerCase();

        if (isCustomizer && wp.customize?.previewedDevice) {
            // Set device in Customizer
            wp.customize.previewedDevice.set(normalizedDevice);
        } else {
            // Set device in block/site editor
            if (setDeviceType) {
                setDeviceType(deviceType);
            } else if (setSiteEditorDeviceType) {
                setSiteEditorDeviceType(deviceType);
            }
        }
        setCurrentView(deviceType);
    };

    // Sync Customizer device changes with local state
    useEffect(() => {
        if (isCustomizer && wp.customize?.previewedDevice) {
            const handleDeviceChange = (newDevice) => {
                setCurrentView(newDevice.charAt(0).toUpperCase() + newDevice.slice(1));
            };
            wp.customize.previewedDevice.bind(handleDeviceChange);
            return () => {
            wp.customize.previewedDevice.unbind(handleDeviceChange);
            };
        }
    }, [isCustomizer]);

    // Sync block editor device changes with local state
    useEffect(() => {
        setCurrentView(getView);
    }, [getView]);


    const id = `inspector-responsive-control-${instanceId}`;

    // Map device types to icons for consistency
    const deviceIcons = {
        Desktop: 'desktop',
        Tablet: 'tablet',
        Mobile: 'smartphone',
    };

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
                                    icon={deviceIcons[currentView] || 'desktop'}
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
                                                'is-selected': device === currentView,
                                            })}
                                            onClick={() => setView(device)}
                                        >
                                            {device === currentView && <Icon icon={checkIcon} />}
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