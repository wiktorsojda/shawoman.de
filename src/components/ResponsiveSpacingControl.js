import { __experimentalBoxControl as BoxControl, ButtonGroup, Button, PanelBody } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function ResponsiveSpacingControl({ attributes, setAttributes }) {
    const { marginDesktop, paddingDesktop, marginMobile, paddingMobile } = attributes;
    const [device, setDevice] = useState('desktop'); // 'desktop' or 'mobile'

    const handleMarginChange = (newValues) => {
        if (device === 'desktop') setAttributes({ marginDesktop: newValues });
        else setAttributes({ marginMobile: newValues });
    };

    const handlePaddingChange = (newValues) => {
        if (device === 'desktop') setAttributes({ paddingDesktop: newValues });
        else setAttributes({ paddingMobile: newValues });
    };

    const currentMargin = device === 'desktop' ? marginDesktop : marginMobile;
    const currentPadding = device === 'desktop' ? paddingDesktop : paddingMobile;

    return (
        <PanelBody title="Layout (Odstępy)" initialOpen={false}>
            <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '15px' }}>
                <ButtonGroup>
                    <Button 
                        isPrimary={device === 'desktop'} 
                        isSecondary={device !== 'desktop'} 
                        onClick={() => setDevice('desktop')}
                    >
                        🖥 Desktop
                    </Button>
                    <Button 
                        isPrimary={device === 'mobile'} 
                        isSecondary={device !== 'mobile'} 
                        onClick={() => setDevice('mobile')}
                    >
                        📱 Mobile
                    </Button>
                </ButtonGroup>
            </div>

            <div style={{ marginBottom: '20px' }}>
                <BoxControl
                    label="Margines"
                    values={currentMargin || {}}
                    onChange={handleMarginChange}
                />
            </div>
            
            <div>
                <BoxControl
                    label="Padding"
                    values={currentPadding || {}}
                    onChange={handlePaddingChange}
                />
            </div>
        </PanelBody>
    );
}
