import os, glob, re

for file in glob.glob('*.html'):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Fix Missing Alt Tags
    def alt_replacer(match):
        img_tag = match.group(0)
        if 'alt=""' in img_tag:
            return img_tag.replace('alt=""', 'alt="Twiggle CAD Studio related graphic"')
        elif 'alt=' not in img_tag:
            return img_tag.replace('<img ', '<img alt="Twiggle CAD Studio related graphic" ')
        return img_tag
    
    content = re.sub(r'<img\s+[^>]*?>', alt_replacer, content)
    
    # 2. Fix Unreadable Links
    def aria_replacer(match):
        a_tag = match.group(1)
        network = match.group(2).title()
        if 'aria-label' not in a_tag:
            return f'<a aria-label="Twiggle {network} Profile" {a_tag[2:]}><i class="fab fa-{match.group(2)}"'
        return match.group(0)
    
    # Needs to match `<a ...><i class="fab fa-something"`
    content = re.sub(r'(<a\s+[^>]*?)><i\s+class="fab\s+fa-([a-z-]+)"', aria_replacer, content)

    # 3. Add loading="lazy" to non-hero images to help CLS/Performance
    def cls_replacer(match):
        img_tag = match.group(0)
        if 'carousel' in img_tag or 'width=' in img_tag or 'loading=' in img_tag:
            return img_tag
        return img_tag.replace('<img ', '<img loading="lazy" ')

    content = re.sub(r'<img\s+[^>]*?>', cls_replacer, content)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print("Applied missing alt tags, aria-labels, and lazy loading.")
