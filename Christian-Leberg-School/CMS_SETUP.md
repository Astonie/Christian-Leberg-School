# CMS Quick Setup Guide

## Setup Instructions

Follow these steps to get your CMS up and running:

### 1. Run Migrations

```bash
php artisan migrate
```

This creates all the necessary database tables for the CMS.

### 2. Seed Sample Data

```bash
php artisan db:seed --class=CMSSeeder
```

This will populate your database with:
- 4 sample pages (About, Admissions, Academics, Contact)
- 5 blog posts with categories and tags
- 4 upcoming events
- Site settings
- Navigation menus
- Photo albums

### 3. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link to make uploaded files accessible.

### 4. Access the CMS

**Public Website:**
- Homepage: `http://your-domain.com/`
- Blog: `http://your-domain.com/blog`
- Events: `http://your-domain.com/events`

**Admin Panel (requires admin role):**
- Pages: `http://your-domain.com/admin/cms/pages`
- Posts: `http://your-domain.com/admin/cms/posts`
- Events: `http://your-domain.com/admin/cms/events`
- Media: `http://your-domain.com/admin/cms/media`
- Menus: `http://your-domain.com/admin/cms/menus`
- Settings: `http://your-domain.com/admin/cms/settings`

### 5. Default Admin Credentials

The seeder creates an admin account:
- **Email:** admin@christianleberg.com
- **Password:** password

**⚠️ IMPORTANT:** Change this password immediately in production!

## Quick Start Guide

### Creating Your First Page

1. Login as admin
2. Navigate to `/admin/cms/pages`
3. Click "Create New Page"
4. Fill in the form:
   - **Title:** Your page title
   - **Content:** Your page content
   - **Status:** Select "Published" to make it live
   - **Template:** Choose layout style
5. Click "Create Page"
6. View your page at `/page/your-slug`

### Creating a Blog Post

1. Navigate to `/admin/cms/posts`
2. Click "Create New Post"
3. Fill in:
   - Title, excerpt, and content
   - Category (optional)
   - Tags (optional)
   - Featured image (optional)
4. Check "Featured" to display on homepage
5. Click "Create Post"

### Adding an Event

1. Navigate to `/admin/cms/events`
2. Click "Create New Event"
3. Enter event details:
   - Title and description
   - Start date and time
   - Location/venue
   - Registration link (if applicable)
4. Upload featured image
5. Click "Create Event"

### Uploading Media

1. Navigate to `/admin/cms/media`
2. Click "Upload Media"
3. Select files (multiple uploads supported)
4. Choose album (optional)
5. Click upload

### Configuring Site Settings

1. Navigate to `/admin/cms/settings`
2. Update:
   - Site name and tagline
   - Contact information
   - Social media links
   - SEO settings
3. Click "Save Settings"

### Managing Menus

1. Navigate to `/admin/cms/menus`
2. Select or create a menu
3. Add menu items with:
   - Title
   - URL or route
   - Order
4. Save and activate menu

## Features Overview

✅ **Pages:** Static pages with multiple templates  
✅ **Blog:** News/blog system with categories and tags  
✅ **Events:** Event calendar with registration links  
✅ **Media Library:** Organize images, videos, documents  
✅ **Menus:** Customizable navigation menus  
✅ **SEO:** Built-in meta tags and Open Graph support  
✅ **Responsive:** Mobile-first design  
✅ **Secure:** Role-based access control

## File Structure

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── PageController.php
│   │   ├── PostController.php
│   │   ├── EventController.php
│   │   ├── MediaController.php
│   │   ├── MenuController.php
│   │   └── SettingController.php
│   └── WebsiteController.php
├── Models/
│   ├── Page.php
│   ├── Post.php
│   ├── Category.php
│   ├── Event.php
│   ├── Media.php
│   ├── Album.php
│   ├── Menu.php
│   ├── MenuItem.php
│   ├── Setting.php
│   └── Tag.php
└── View/Composers/
    └── WebsiteComposer.php

database/
├── migrations/
│   ├── 2024_12_27_000001_create_cms_pages_table.php
│   ├── 2024_12_27_000002_create_cms_posts_table.php
│   └── ... (8 total migration files)
└── seeders/
    └── CMSSeeder.php

resources/views/
├── admin/cms/
│   ├── pages/
│   ├── posts/
│   ├── events/
│   └── media/
└── website/
    ├── layout.blade.php
    ├── home.blade.php
    ├── page.blade.php
    ├── blog/
    └── events/
```

## Need Help?

For detailed documentation, see [CMS_DOCUMENTATION.md](CMS_DOCUMENTATION.md)

For Laravel-specific help, visit: https://laravel.com/docs

## Troubleshooting

**Problem:** Images not showing  
**Solution:** Run `php artisan storage:link`

**Problem:** 404 on admin pages  
**Solution:** Clear route cache with `php artisan route:clear`

**Problem:** Can't access admin  
**Solution:** Ensure your user has the 'admin' role

**Problem:** Changes not appearing  
**Solution:** Clear cache with `php artisan cache:clear`

---

**Happy Content Managing! 🎉**
