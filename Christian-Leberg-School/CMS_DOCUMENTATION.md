# Content Management System (CMS) Documentation

## Overview

This is a comprehensive, professional Content Management System built for the Christian Leberg School portal. The CMS provides a complete solution for managing public website content including pages, blog posts, events, media library, menus, and site settings.

## Features

### ✨ Core Features

- **Dynamic Pages** - Create unlimited custom pages with various templates
- **Blog/News System** - Full-featured blogging with categories, tags, and featured posts
- **Events Management** - Manage school events with calendar integration
- **Media Library** - Upload and organize images, videos, and documents
- **Menu Management** - Create and manage navigation menus with drag-and-drop ordering
- **SEO Optimization** - Built-in SEO fields for meta titles, descriptions, and Open Graph tags
- **Settings Management** - Centralized site configuration
- **Responsive Design** - Mobile-first, responsive public website
- **User Roles** - Admin-only access to CMS functionality

### 📋 Content Types

#### Pages
- Custom page templates (default, full-width, sidebar)
- Rich text content editor
- Featured images
- SEO meta tags
- Publishing controls (draft, published, scheduled)
- Order management

#### Blog Posts
- Category organization
- Tag system for cross-referencing
- Author attribution
- View counter
- Featured posts
- Comments toggle
- Excerpt and full content
- Publishing controls

#### Events
- Date and time management
- All-day event support
- Location and venue details
- Registration links
- Contact information
- Status tracking (draft, published, cancelled, completed)
- Featured events
- Tag support

#### Media
- Image uploads
- Video support
- Document management
- Album organization
- Metadata (dimensions, file size, etc.)
- Alt text and captions
- Type filtering

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create all necessary CMS tables:
- cms_pages
- cms_posts
- cms_categories
- cms_events
- cms_media
- cms_albums
- cms_menus
- cms_menu_items
- cms_settings
- cms_tags
- cms_taggables

### 2. Seed Sample Data

```bash
php artisan db:seed --class=CMSSeeder
```

This creates:
- Sample pages (About, Admissions, Academics, Contact)
- 5 blog posts with categories and tags
- 4 upcoming events
- Site settings
- Navigation menus
- Photo albums

### 3. Storage Link

Ensure the storage link is created for media files:

```bash
php artisan storage:link
```

### 4. Access the CMS

**Admin Panel:** `/admin/cms/*` (requires admin role)
**Public Website:** `/` (home), `/blog`, `/events`, `/page/{slug}`

## Usage Guide

### Managing Pages

**Create a Page:**
1. Navigate to Admin > CMS > Pages
2. Click "Create New Page"
3. Fill in title, content, and SEO fields
4. Select template and status
5. Upload featured image (optional)
6. Click "Create Page"

**Templates Available:**
- `default` - Standard page layout
- `full-width` - Full-width content (no sidebar)
- `sidebar` - Two-column layout with sidebar

### Managing Blog Posts

**Create a Post:**
1. Navigate to Admin > CMS > Posts
2. Click "Create New Post"
3. Fill in title, excerpt, and content
4. Select category and tags
5. Set featured status and publishing options
6. Upload featured image
7. Configure SEO settings
8. Click "Create Post"

**Featured Posts:**
Check the "Featured" checkbox to display posts prominently on the homepage.

### Managing Events

**Create an Event:**
1. Navigate to Admin > CMS > Events
2. Click "Create New Event"
3. Enter event details (title, description, content)
4. Set start and end dates/times
5. Add location and venue information
6. Include registration link if applicable
7. Add contact information
8. Click "Create Event"

**Event Status:**
- `draft` - Not visible to public
- `published` - Visible on website
- `cancelled` - Marked as cancelled
- `completed` - Past event

### Media Library

**Upload Media:**
1. Navigate to Admin > CMS > Media
2. Click "Upload Media"
3. Select one or multiple files
4. Choose album (optional)
5. Click upload

**Organize Media:**
- Filter by type (images, videos, documents)
- Filter by album
- Search by title
- Edit metadata and captions

### Menu Management

**Create a Menu:**
1. Navigate to Admin > CMS > Menus
2. Click "Create New Menu"
3. Enter menu name and location
4. Add menu items with links
5. Organize items with drag-and-drop (if implemented)
6. Set menu as active

**Menu Locations:**
- `header` - Main navigation
- `footer` - Footer links
- `sidebar` - Sidebar navigation

### Site Settings

Navigate to Admin > CMS > Settings to configure:
- Site name and tagline
- Contact information
- Social media links
- SEO defaults
- General configuration

## Public Website Routes

| Route | Purpose |
|-------|---------|
| `/` | Homepage with featured content |
| `/page/{slug}` | Dynamic page display |
| `/blog` | Blog post listing |
| `/blog/{slug}` | Individual blog post |
| `/events` | Upcoming events listing |
| `/events/{slug}` | Event details |
| `/search?q=term` | Search across content |

## Admin Routes

All admin routes are prefixed with `/admin/cms/` and require admin role:

| Route | Purpose |
|-------|---------|
| `pages` | Manage pages |
| `posts` | Manage blog posts |
| `events` | Manage events |
| `media` | Media library |
| `menus` | Menu management |
| `settings` | Site configuration |

## Models & Relationships

### Page Model
- `creator()` - User who created the page
- `updater()` - User who last updated
- Scopes: `published()`, `ordered()`

### Post Model
- `author()` - Post author (User)
- `category()` - Post category
- `tags()` - Related tags (polymorphic)
- Scopes: `published()`, `featured()`, `recent()`

### Event Model
- `creator()` - Event creator (User)
- `tags()` - Related tags (polymorphic)
- Scopes: `published()`, `upcoming()`, `past()`, `featured()`

### Media Model
- `album()` - Parent album
- `uploader()` - User who uploaded
- Scopes: `images()`, `videos()`, `documents()`

### Menu & MenuItem Models
- Menu `items()` - Top-level menu items
- MenuItem `children()` - Sub-menu items
- MenuItem `parent()` - Parent menu item

## SEO Features

Each content type includes SEO fields:
- Meta title
- Meta description
- Meta keywords
- Open Graph image

The public website layout automatically includes these in the HTML head.

## Security

- All admin routes protected by `role:admin` middleware
- File upload validation and type checking
- CSRF protection on all forms
- SQL injection protection via Eloquent ORM
- XSS protection with Blade templating

## Customization

### Adding New Templates

1. Add template option in page controller validation
2. Create corresponding Blade view
3. Update template dropdown in create/edit forms

### Extending Content Types

Follow the existing pattern:
1. Create migration
2. Create model with relationships
3. Create admin controller
4. Create admin views
5. Create public controller methods
6. Add routes

## Best Practices

1. **Images**: Optimize before uploading (recommended max 2MB)
2. **SEO**: Always fill in meta descriptions (150-160 characters)
3. **Slugs**: Let the system auto-generate from titles for consistency
4. **Publishing**: Use draft status while working, schedule for future releases
5. **Tags**: Use consistent tagging for better content discovery
6. **Menus**: Keep navigation simple and intuitive

## Troubleshooting

**Images not displaying:**
- Verify storage link exists: `php artisan storage:link`
- Check file permissions on storage directory

**404 on public pages:**
- Ensure routes are registered
- Check page status is "published"
- Verify slug is correct

**Can't access admin:**
- Verify user has 'admin' role
- Check middleware configuration

## Future Enhancements

Potential additions:
- Rich text WYSIWYG editor integration
- Advanced media gallery with lightbox
- Comment system for blog posts
- Newsletter subscription
- Contact form builder
- Analytics dashboard
- Multi-language support
- Advanced search with filters
- Content versioning

## Support

For issues or questions about the CMS, contact the development team or refer to Laravel documentation at https://laravel.com/docs

---

**Version:** 1.0  
**Last Updated:** December 2024  
**Maintained by:** Christian Leberg School Development Team
