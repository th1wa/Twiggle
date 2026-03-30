# Twiggle CAD Studio

Welcome to the **Twiggle CAD Studio** website project repository! 

Twiggle CAD Studio provides professional CAD drafting, floor plans, and 3D visualization services for real estate agents, architects, and builders worldwide. This repository contains the source code for the official website.

## 🌐 Live Website
[https://www.twigglecadstudio.lk/](https://www.twigglecadstudio.lk/)

## ✨ Key Features
- **Responsive Design:** Fully responsive layout optimized for desktops, tablets, and mobile devices using Bootstrap 5.
- **Modern UI/UX:** Clean, architectural-focused design with smooth animations and transitions.
- **Service Showcases:** Dedicated sections for 2D Floor Plans, 3D Visualization, Virtual Staging, and Photo Retouching.
- **Interactive Floor Plan Gallery:** Tabbed navigation to easily view various layout samples.
- **SEO Optimized:** Implements standard SEO meta tags and structured data (Schema.org) for better search engine visibility.
- **Performance Optimized:** Uses lazy loading for images and deferred script loading for faster page load times.

## 🛠️ Technology Stack
- **Structure:** HTML5
- **Styling:** CSS3, SCSS, [Bootstrap 5](https://getbootstrap.com/)
- **Interactivity:** JavaScript (Vanilla & jQuery)
- **Icons & Fonts:** FontAwesome, Bootstrap Icons, Google Fonts (Open Sans, Teko)
- **Libraries used:**
  - [Animate.css](https://animate.style/) (for scroll animations)
  - [Owl Carousel](https://owlcarousel2.github.io/OwlCarousel2/) (for sliders and testimonials)
  - Lottie Web Components (for engaging lightweight animations)

## 📁 Project Structure
```text
/
├── css/                 # Compiled CSS files
├── scss/                # Source SCSS files (if any customization needed)
├── img/                 # Images, icons, and gallery assets
├── js/                  # Main JavaScript logic (main.js)
├── lib/                 # Third-party libraries (WOW.js, OwlCarousel, etc.)
├── scripts/             # Python scripts used for optimization (SEO, pagespeed fixes)
├── .htaccess            # Apache server configuration
├── index.html           # Homepage
├── about.html           # About Us page
├── service.html         # Services page
├── project.html         # Projects / Floor Plans showcase
├── appointment.html     # Appointment booking page
├── contact.html         # Contact page
├── send_appointment.php # Backend script for booking processing
└── subscribe_newsletter.php # Backend script for newsletter subscription
```

## 🚀 Getting Started
This is a relatively static website with basic PHP form handlers. You do not need a complex build pipeline to run it.

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd Twiggle
   ```

2. **Run it locally:**
   - **Static Pages:** You can simply open `index.html` in your web browser.
   - **With PHP Scripts:** If you want to test the contact forms or appointment booking, you need to run the site on a local PHP server (like XAMPP, WAMP, or via the built-in PHP server):
     ```bash
     php -S localhost:8000
     ```
     Then open `http://localhost:8000` in your browser.

## 📧 Contact Information
For any inquiries related to CAD services or this website, you can reach out via:
- **Email:** info@twigglecadstudio.lk
- **Phone:** +94 76 070 8494
- **Address:** 58/19 A D.M Colomboge Mawatha, Kirulapana, Colombo 05, Sri Lanka
