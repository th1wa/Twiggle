import os, glob, re

# 1. Update CSS Color Contrast Ratio
for file in glob.glob('css/*.css'):
    with open(file, 'r', encoding='utf-8') as f:
        css = f.read()
    # Darken grey shades to satisfy AAA accessibility contrast guidelines
    css = css.replace('#999999', '#555555')
    css = css.replace('#666666', '#444444')
    css = css.replace('#777777', '#4b4b4b')
    with open(file, 'w', encoding='utf-8') as f:
        f.write(css)

# 2. Update HTML Accessibility for forms and single-quote image alts
for file in glob.glob('*.html'):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Inject ARIA labels dynamically based on placeholder names
    def input_replacer(match):
        tag = match.group(0)
        if 'aria-label=' not in tag and 'type="hidden"' not in tag:
            p_match = re.search(r'placeholder=["\']([^"\']+)["\']', tag)
            label = p_match.group(1) if p_match else "Form input"
            return tag.replace('<input ', f'<input aria-label="{label}" ')
        return tag

    content = re.sub(r'<input\s+[^>]+>', input_replacer, content)

    # Inject ARIA labels for textareas
    def tex_replacer(match):
        tag = match.group(0)
        if 'aria-label=' not in tag:
            p_match = re.search(r'placeholder=["\']([^"\']+)["\']', tag)
            label = p_match.group(1) if p_match else "Form text area"
            return tag.replace('<textarea ', f'<textarea aria-label="{label}" ')
        return tag
        
    content = re.sub(r'<textarea\s+[^>]+>', tex_replacer, content)

    # Fix the remaining 3 images missing ALT attributes due to single-quote formatting
    content = content.replace("alt=''", "alt='Twiggle CAD Studio gallery graphic'")
    content = content.replace('alt=""', 'alt="Twiggle CAD Studio gallery graphic"')
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print("Accessibility fixes (Form labels, Color Contrast, and Single-quote Alts) successfully executed.")
