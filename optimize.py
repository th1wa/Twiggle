import os, glob, re

# Minify main.js
with open('js/main.js', 'r', encoding='utf-8') as f:
    js = f.read()

# Very basic strip of single line comments and empty lines
js_lines = []
for line in js.split('\n'):
    stripped = line.strip()
    if stripped and not stripped.startswith('//'):
        js_lines.append(stripped)

with open('js/main.js', 'w', encoding='utf-8') as f:
    f.write(' '.join(js_lines))

# Defer scripts and hide emails across all pages to pass audit globally!
for file in glob.glob('*.html'):
    if file == 'index.html':
        continue # Already handled precisely
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Obfuscate email
    content = content.replace('info@twigglecadstudio.lk', 'info<span style="display:none">null</span>@twigglecadstudio.lk')
    
    # Defer scripts safely
    content = content.replace('<script src="https://cdn.jsdelivr', '<script defer src="https://cdn.jsdelivr')
    content = content.replace('<script src="lib/', '<script defer src="lib/')
    content = content.replace('<script src="js/', '<script defer src="js/')
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
print("Minification and structural batch patches applied successfully.")
