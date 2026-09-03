import { ButtonGroup, Button, PanelBody, Tooltip, Dashicon } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

// Elementor-like Box Control Component
const ElementorBoxControl = ({ label, values, onChange }) => {
    // Rozdzielenie na wartość liczbową i jednostkę
    const parseValue = (val) => {
        if (!val) return { num: '', unit: 'px' };
        const match = val.match(/^(-?\d*\.?\d+)(px|em|%|rem)?$/);
        if (match) {
            return { num: match[1], unit: match[2] || 'px' };
        }
        return { num: val, unit: 'px' };
    };

    const initialTop = parseValue(values?.top);
    const initialRight = parseValue(values?.right);
    const initialBottom = parseValue(values?.bottom);
    const initialLeft = parseValue(values?.left);

    // Bierzemy jednostkę z pierwszego niepustego pola, domyślnie px
    const [unit, setUnit] = useState(initialTop.num ? initialTop.unit : (initialRight.num ? initialRight.unit : (initialBottom.num ? initialBottom.unit : (initialLeft.num ? initialLeft.unit : 'px'))));
    
    const [top, setTop] = useState(initialTop.num);
    const [right, setRight] = useState(initialRight.num);
    const [bottom, setBottom] = useState(initialBottom.num);
    const [left, setLeft] = useState(initialLeft.num);

    const [isLinked, setIsLinked] = useState(false); // Zmienione na false, aby zapobiec psuciu marginesów bocznych

    // Synchronizacja ze zmianami z zewnątrz (np. przy przełączeniu Mobile/Desktop)
    useEffect(() => {
        const t = parseValue(values?.top);
        const r = parseValue(values?.right);
        const b = parseValue(values?.bottom);
        const l = parseValue(values?.left);
        setTop(t.num);
        setRight(r.num);
        setBottom(b.num);
        setLeft(l.num);
        // Sprawdzamy czy wartości są różne. Jeśli tak - odlinkowujemy
        if (t.num && r.num && b.num && l.num && (t.num !== r.num || t.num !== b.num || t.num !== l.num)) {
            setIsLinked(false);
        }
    }, [values]);

    const triggerChange = (t, r, b, l, u) => {
        onChange({
            top: t ? `${t}${u}` : undefined,
            right: r ? `${r}${u}` : undefined,
            bottom: b ? `${b}${u}` : undefined,
            left: l ? `${l}${u}` : undefined,
        });
    };

    const handleLinkToggle = () => {
        const newLinked = !isLinked;
        setIsLinked(newLinked);
        if (newLinked && top !== '') {
            // Po zlinkowaniu, kopiuj wartość top do wszystkich
            setRight(top);
            setBottom(top);
            setLeft(top);
            triggerChange(top, top, top, top, unit);
        }
    };

    const handleChange = (field, val) => {
        let t = top, r = right, b = bottom, l = left;
        
        if (isLinked) {
            t = val; r = val; b = val; l = val;
            setTop(val); setRight(val); setBottom(val); setLeft(val);
        } else {
            if (field === 'top') { t = val; setTop(val); }
            if (field === 'right') { r = val; setRight(val); }
            if (field === 'bottom') { b = val; setBottom(val); }
            if (field === 'left') { l = val; setLeft(val); }
        }
        triggerChange(t, r, b, l, unit);
    };

    const handleUnitChange = (newUnit) => {
        setUnit(newUnit);
        triggerChange(top, right, bottom, left, newUnit);
    };

    const unitStyles = (current) => ({
        cursor: 'pointer',
        fontSize: '10px',
        fontWeight: 'bold',
        color: unit === current ? '#252525' : '#a5a5a5',
        marginLeft: '4px',
        userSelect: 'none'
    });

    const inputStyles = {
        width: '100%',
        textAlign: 'center',
        padding: '5px 2px',
        border: '1px solid #d9d9d9',
        borderRight: 'none',
        borderRadius: 0,
        fontSize: '12px',
        outline: 'none',
        boxSizing: 'border-box'
    };

    return (
        <div style={{ marginBottom: '24px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' }}>
                <span style={{ fontSize: '13px', fontWeight: '500', color: '#585858' }}>{label}</span>
                <div style={{ display: 'flex', alignItems: 'center' }}>
                    <span style={unitStyles('px')} onClick={() => handleUnitChange('px')}>PX</span>
                    <span style={unitStyles('em')} onClick={() => handleUnitChange('em')}>EM</span>
                    <span style={unitStyles('%')} onClick={() => handleUnitChange('%')}>%</span>
                    <span style={unitStyles('rem')} onClick={() => handleUnitChange('rem')}>REM</span>
                </div>
            </div>
            <div style={{ display: 'flex', width: '100%' }}>
                <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                    <input type="number" style={{...inputStyles, borderTopLeftRadius: '3px', borderBottomLeftRadius: '3px'}} value={top} onChange={(e) => handleChange('top', e.target.value)} />
                    <span style={{ fontSize: '9px', textAlign: 'center', color: '#bcbcbc', marginTop: '4px' }}>TOP</span>
                </div>
                <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                    <input type="number" style={inputStyles} value={right} onChange={(e) => handleChange('right', e.target.value)} />
                    <span style={{ fontSize: '9px', textAlign: 'center', color: '#bcbcbc', marginTop: '4px' }}>RIGHT</span>
                </div>
                <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                    <input type="number" style={inputStyles} value={bottom} onChange={(e) => handleChange('bottom', e.target.value)} />
                    <span style={{ fontSize: '9px', textAlign: 'center', color: '#bcbcbc', marginTop: '4px' }}>BOTTOM</span>
                </div>
                <div style={{ flex: 1, display: 'flex', flexDirection: 'column' }}>
                    <input type="number" style={{...inputStyles, borderRight: '1px solid #d9d9d9'}} value={left} onChange={(e) => handleChange('left', e.target.value)} />
                    <span style={{ fontSize: '9px', textAlign: 'center', color: '#bcbcbc', marginTop: '4px' }}>LEFT</span>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', width: '36px' }}>
                    <Tooltip text={isLinked ? 'Odłącz wartości' : 'Połącz wartości'}>
                        <button 
                            onClick={handleLinkToggle}
                            style={{
                                width: '100%',
                                height: '27px',
                                border: 'none',
                                background: isLinked ? '#e0e0e0' : '#f2f2f2',
                                color: isLinked ? '#252525' : '#a5a5a5',
                                cursor: 'pointer',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                borderTopRightRadius: '3px',
                                borderBottomRightRadius: '3px'
                            }}
                        >
                            <Dashicon icon="admin-links" size={16} />
                        </button>
                    </Tooltip>
                </div>
            </div>
        </div>
    );
};

export default function ResponsiveSpacingControl({ attributes, setAttributes }) {
    const { marginDesktop, paddingDesktop, marginMobile, paddingMobile } = attributes;
    const [device, setDevice] = useState('desktop');

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
        <PanelBody title="Layout" initialOpen={true}>
            <div style={{ display: 'flex', justifyContent: 'flex-start', marginBottom: '20px', gap: '8px' }}>
                <Button 
                    isPrimary={device === 'desktop'} 
                    isSecondary={device !== 'desktop'} 
                    onClick={() => setDevice('desktop')}
                    icon="desktop"
                    style={{ padding: '0 8px', height: '32px' }}
                />
                <Button 
                    isPrimary={device === 'mobile'} 
                    isSecondary={device !== 'mobile'} 
                    onClick={() => setDevice('mobile')}
                    icon="smartphone"
                    style={{ padding: '0 8px', height: '32px' }}
                />
            </div>

            <ElementorBoxControl
                label="Margin"
                values={currentMargin || {}}
                onChange={handleMarginChange}
            />
            
            <ElementorBoxControl
                label="Padding"
                values={currentPadding || {}}
                onChange={handlePaddingChange}
            />
        </PanelBody>
    );
}
