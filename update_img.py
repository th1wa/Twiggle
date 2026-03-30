import glob

for file in glob.glob('*.html'):
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Replace About image sources dynamically
    content = content.replace('src="img/about-1.jpg"', 'src="img/about-1.png"')
    content = content.replace('src="img/about-2.jpg"', 'src="img/about-2.png"')
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)

print('Updated HTML files successfully with the new modern images.')
