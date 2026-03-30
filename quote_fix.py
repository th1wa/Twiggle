import glob, re

for file in glob.glob('*.html'):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    def fix_data_dot(match):
        prefix = 'data-dot="'
        suffix = '"'
        inner = match.group(0)[len(prefix):-len(suffix)]
        # replace all double quotes with single quotes in the inner string to fix HTML attribute parsing
        inner_fixed = inner.replace('"', "'")
        return prefix + inner_fixed + suffix

    # Match data-dot="<img ... >"
    content = re.sub(r'data-dot="<img[^>]*>"', fix_data_dot, content)
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print("Fixed HTML quote conflicts successfully.")
