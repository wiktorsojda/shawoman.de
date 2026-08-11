import re

with open('inc/theme-wyglad.php', 'r') as f:
    content = f.read()

# Add thumbnail to Text Badges
text_badge_header = r"let headerTitle = rule.text \? 'Etykieta: ' \+ rule.text : 'Reguła #' \+ \(index \+ 1\);"
text_badge_html = r"""let headerTitle = rule.text ? 'Etykieta: ' + rule.text : 'Reguła #' + (index + 1);
                    let previewColor = rule.color || predefinedColors[0];
                    let previewText = rule.text || 'Tekst';
                    let previewHTML = `<span style="margin-left: 10px; background: ${previewColor}; color: ${rule.textColor || '#ffffff'}; padding: 2px 8px; border-radius: 4px; font-size: 10px; text-transform: uppercase;">${previewText}</span>`;
                    headerTitle += previewHTML;"""
content = content.replace(text_badge_header, text_badge_html)

# Add thumbnail to Promo Badges
promo_badge_header = r"let headerTitle = rule.text \? 'Rabat: -' \+ rule.text \+ '%' : 'Reguła #' \+ \(index \+ 1\);"
promo_badge_html = r"""let headerTitle = rule.text ? 'Rabat: -' + rule.text + '%' : 'Reguła #' + (index + 1);
                    let previewColor = rule.color || predefinedColors[0];
                    let previewText = rule.text || 'X';
                    let previewHTML = `<span style="margin-left: 10px; background: ${previewColor}; color: ${rule.textColor || '#ffffff'}; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: bold;">-${previewText}%</span>`;
                    headerTitle += previewHTML;"""
content = content.replace(promo_badge_header, promo_badge_html)

# Add thumbnail to SVG Badges
svg_badge_header = r"let headerTitle = rule.text \? 'Odznaka: ' \+ rule.text : 'Odznaka #' \+ \(index \+ 1\);"
svg_badge_html = r"""let headerTitle = rule.text ? 'Odznaka: ' + rule.text : 'Odznaka #' + (index + 1);
                    let previewHTML = `<span style="margin-left: 10px; padding: 2px 8px; background: #eee; border-radius: 4px; font-size: 10px;">Ikona SVG / PNG</span>`;
                    headerTitle += previewHTML;"""
content = content.replace(svg_badge_header, svg_badge_html)


# Fix preset issue (re-render after preset/swatch changes)
# Text badges preset
tb_preset = r"syncTextBadgeDataFromDOM\(\);\n                    }\);"
tb_preset_fix = r"syncTextBadgeDataFromDOM();\n                        renderTextBadgeRows();\n                    });"
content = re.sub(tb_preset, tb_preset_fix, content)

# Text badges swatch
tb_swatch = r"inputField.value = swatch.dataset.color;\n                        }"
tb_swatch_fix = r"inputField.value = swatch.dataset.color;\n                        }\n                        syncTextBadgeDataFromDOM();\n                        renderTextBadgeRows();"
content = re.sub(tb_swatch, tb_swatch_fix, content)


# Promo badges preset/swatch (if it exists)
# Let's just blindly try to replace any syncPromoBadgeDataFromDOM(); followed by });
# Actually Promo badges might not have presets, they only have swatches.
pb_swatch = r"inputField.value = swatch.dataset.color;\n                        }\n                    }\);"
pb_swatch_fix = r"inputField.value = swatch.dataset.color;\n                        }\n                        syncPromoBadgeDataFromDOM();\n                        renderPromoBadgeRows();\n                    });"
content = re.sub(pb_swatch, pb_swatch_fix, content)


with open('inc/theme-wyglad.php', 'w') as f:
    f.write(content)
