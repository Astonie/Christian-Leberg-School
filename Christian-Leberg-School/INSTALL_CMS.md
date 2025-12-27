# CMS Installation Commands

Run these commands in order to set up your CMS:

## 1. Regenerate Autoloader
```bash
composer dump-autoload
```

## 2. Run Database Migrations
```bash
php artisan migrate
```

## 3. Seed Sample Content
```bash
php artisan db:seed --class=CMSSeeder
```

## 4. Create Storage Link
```bash
php artisan storage:link
```

## 5. Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 6. Test Access

### Public Website:
- Homepage: http://localhost:8000/
- Blog: http://localhost:8000/blog
- Events: http://localhost:8000/events
- About Page: http://localhost:8000/page/about

### Admin Panel:
- Login: http://localhost:8000/login
  - Email: admin@christianleberg.com
  - Password: password

- CMS Dashboard:
  - Pages: http://localhost:8000/admin/cms/pages
  - Posts: http://localhost:8000/admin/cms/posts
  - Events: http://localhost:8000/admin/cms/events
  - Media: http://localhost:8000/admin/cms/media
  - Menus: http://localhost:8000/admin/cms/menus
  - Settings: http://localhost:8000/admin/cms/settings

## Troubleshooting

### If images don't show:
```bash
php artisan storage:link
```

### If routes don't work:
```bash
php artisan route:clear
php artisan route:cache
```

### If views show errors:
```bash
php artisan view:clear
composer dump-autoload
```

### If settings don't load:
```bash
composer dump-autoload
php artisan config:clear
```

## All Done! 🎉

Your CMS is now ready to use. Visit the admin panel to start creating content!
