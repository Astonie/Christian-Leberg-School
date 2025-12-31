# CMS Dynamic Content Management Guide

## Overview
The school website is now fully dynamic! All content on the homepage can be managed through the CMS settings without needing to edit code.

## How to Edit Content

### Accessing Settings
1. Login to the admin dashboard
2. Navigate to **Settings** section
3. Filter by group: **Homepage** to see all landing page settings

---

## Homepage Sections

### 1. Hero Section
The main banner at the top of the homepage.

**Settings:**
- `hero_badge_text` - The small badge text (e.g., "Admissions Open 2025")
- `site_name` - School name displayed in large text
- `site_tagline` - Subtitle/description below school name
- `hero_image_1` - Top-left image (e.g., "images/clss.jpg")
- `hero_image_2` - Bottom-left image
- `hero_image_3` - Top-right image
- `hero_image_4` - Bottom-right image

**Image Paths:**
- Use relative paths like: `images/your-photo.jpg`
- Images should be placed in the `public/images/` folder

---

### 2. Why Choose Us (Features Section)
Four feature cards highlighting school strengths.

**Section Headers:**
- `features_title` - Main section title (default: "Why Choose Us")
- `features_subtitle` - Section description

**Feature Cards (1-4):**
Each feature has these settings:
- `feature_X_title` - Card title
- `feature_X_description` - Card description
- `feature_X_icon` - Icon name (options: `book`, `users`, `shield`, `lightning`)
- `feature_X_color` - Color scheme (options: `blue`, `purple`, `green`, `orange`)

**Example:**
- Feature 1: Quality Education (book icon, blue color)
- Feature 2: Experienced Staff (users icon, purple color)
- Feature 3: Safe Environment (shield icon, green color)
- Feature 4: Modern Facilities (lightning icon, orange color)

---

### 3. Statistics Section
Four stat cards with achievement numbers.

**Section Headers:**
- `stats_title` - Main section title (default: "Our Achievements")
- `stats_subtitle` - Section description

**Stat Cards (1-4):**
Each stat has:
- `stat_X_value` - The number/value (e.g., "1000+", "95%")
- `stat_X_label` - Label below the number (e.g., "Students", "Success Rate")

**Current Stats:**
1. Students: 1000+
2. Teachers: 50+
3. Years: 20+
4. Success Rate: 95%

---

### 4. Call to Action (CTA) Section
Prominent section encouraging visitors to take action.

**Settings:**
- `cta_title` - Main headline (default: "Ready to Join Us?")
- `cta_subtitle` - Description text
- `cta_button_1_text` - First button text (default: "Apply Now")
- `cta_button_1_link` - First button link (default: "/page/admissions")
- `cta_button_2_text` - Second button text (default: "Contact Us")
- `cta_button_2_link` - Second button link (default: "/page/contact")

**Link Formats:**
- Internal pages: `/page/about`, `/page/contact`
- External links: `https://example.com`
- Routes: Use full paths like above

---

## Dynamic Content Sections (Already Dynamic)

These sections pull content automatically from your CMS:

### Latest News
- Automatically displays the 3 most recent **featured posts**
- Managed in: **Posts** → Mark posts as "Featured"

### Upcoming Events
- Automatically displays the 4 next **upcoming events**
- Managed in: **Events** → Create and publish events

---

## General Settings

### Site Information
- `site_name` - School name (used throughout the site)
- `site_description` - Meta description for SEO
- `site_tagline` - School motto/tagline
- `site_keywords` - SEO keywords

### Contact Information
- `contact_email` - School email address
- `contact_phone` - School phone number
- `contact_address` - School physical address

### Social Media
- `social_facebook` - Facebook page URL
- `social_twitter` - Twitter profile URL
- `social_instagram` - Instagram profile URL

---

## Best Practices

### Content Guidelines
1. **Keep text concise** - Feature descriptions should be 1-2 sentences
2. **Use compelling stats** - Make numbers memorable (1000+ vs 1,023)
3. **Update regularly** - Keep hero badge current (e.g., update year annually)
4. **Test images** - Ensure hero images are high quality and properly cropped

### Image Guidelines
1. **Hero Images:** 
   - Recommended size: 800x600px minimum
   - Format: JPG or PNG
   - Keep file size under 500KB for fast loading

2. **Aspect Ratio:**
   - Top images: Landscape orientation
   - Bottom images: Can be more square

### Performance Tips
1. Compress images before uploading
2. Use descriptive file names (school-building.jpg, not IMG_1234.jpg)
3. Update settings during off-peak hours

---

## Quick Start Checklist

After fresh installation:

- [ ] Update `site_name` with your school name
- [ ] Update `site_tagline` with your motto
- [ ] Update `hero_badge_text` with current message
- [ ] Replace hero images with your school photos
- [ ] Customize feature titles and descriptions
- [ ] Update statistics with accurate numbers
- [ ] Update contact information
- [ ] Add social media links
- [ ] Create and feature some blog posts
- [ ] Create upcoming events

---

## Troubleshooting

**Images not showing?**
- Verify image path is correct (e.g., `images/photo.jpg`)
- Check file exists in `public/images/` folder
- Ensure proper file permissions

**Setting not updating?**
- Clear cache: `php artisan cache:clear`
- Refresh browser (Ctrl+F5 or Cmd+Shift+R)

**Content looks broken?**
- Verify all required settings are filled
- Check for special characters that need escaping
- Contact system administrator

---

## Support

For technical support or questions about managing content:
- Email: admin@school.edu
- Documentation: See CMS_DOCUMENTATION.md
- Training: Contact your system administrator

---

**Last Updated:** December 28, 2025
**Version:** 1.0
