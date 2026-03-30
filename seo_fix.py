import os, glob, re

for file in glob.glob('*.html'):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Determine canonical URL
    if file == 'index.html':
        canonical_url = 'https://www.twigglecadstudio.lk/'
    else:
        canonical_url = f'https://www.twigglecadstudio.lk/{file}'

    # Check if canonical tag already exists
    if '<link rel="canonical"' not in content:
        # Insert before </head>
        canonical_tag = f'    <link rel="canonical" href="{canonical_url}">\n</head>'
        content = content.replace('</head>', canonical_tag)

    # Shorten meta description if it's too long
    # We will use regex to find the meta description and trim it
    desc_match = re.search(r'<meta name="description" content="([^"]+)">', content)
    if desc_match:
        original_desc = desc_match.group(1)
        if len(original_desc) > 160:
            # Simple summarization for index.html length issue
            if "Twiggle CAD Studio provides professional CAD drafting" in original_desc:
                new_desc = "Twiggle CAD Studio provides professional CAD drafting, floor plans, and 3D visualization for real estate agents, architects, and builders worldwide."
                new_meta = f'<meta name="description" content="{new_desc}">'
                content = content.replace(desc_match.group(0), new_meta)
            else:
                # Just truncate and add ellipsis for safety if it's over 155
                truncated = original_desc[:152] + "..."
                new_meta = f'<meta name="description" content="{truncated}">'
                content = content.replace(desc_match.group(0), new_meta)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
print("Applied canonical tags and shortened meta descriptions successfully.")
