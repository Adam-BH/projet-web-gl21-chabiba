# Migration Summary

**Date**: 2026-05-29
**Completed by**: opencode
**Migration Type**: Non-destructive refactoring

## Changes Made

### Phase 1-2: Directory Structure & Autoloader
- Created `src/Classes/` and `src/Includes/` directories
- Created `public_html/` with subdirectories (auth, catalogue, lost-and-found, manual, search-engine)
- Created `config/` directory with `paths.php`
- Updated root `autoloader.php` to point to `src/Classes/`
- Created `config/paths.php` with route constants

### Phase 4-5: Moved Class Files & Includes
- Moved all class files from `/class/` to `/src/Classes/`
- Moved `header.php` and `booking-popup.php` to `/src/Includes/`
- Renamed `Irepository.php` → `IRepository.php`
- Renamed `AdressRepository.php` → `AddressRepository.php` (fixed typo)
- Updated class name `AdressRepository` → `AddressRepository` inside the file

### Phase 6: Consolidated HTML → PHP
- Deleted duplicate HTML files: community.html, equipment.html, weather.html, moon.html, availability.html, shops.html
- Deleted sites/site-details.html and sites/sites.html

### Phase 7-8: Updated All Path References
- Updated all `require_once` and `include` paths in every PHP file
- Updated header.php navigation links from `/pages/` to `/public_html/`
- Updated all hardcoded URLs in pages (auth links, catalogue links, etc.)
- Updated booking form action to point to new catalogue path
- Updated all image `src` paths to use absolute URLs

### Phase 9: API Consolidation
- API files remain in `/api/` (already in correct location)

### Phase 10: Moved Page Files
- All pages moved to `/public_html/`
- Created `/public_html/index.php` as the home page
- Root `index.php` now redirects to `public_html/index.php`

### Phase 11-12: Post-Move Path Updates
- Updated require paths for subdirectory pages (auth/, catalogue/, lost-and-found/)
- Fixed auth processor redirects to use absolute URLs
- Fixed logout redirect

### Phase 13: CSS Renames
- `css/pages/weather-page.css` → `css/pages/weather.css`
- `css/pages/moon-page.css` → `css/pages/moon.css`
- `css/pages/l&f.css` → `css/pages/lost-and-found.css`
- Updated all page references to use new CSS filenames

### Phase 14: JavaScript Organization
- Created `/js/pages/` directory
- Copied page-specific JS files to `/js/pages/`:
  - moon.js, lost-and-found.js, community.js, equipment.js
  - hiking-guide.js, search-engine.js, auth.js, bookings.js

### Renames
- `lost&found.php` → `lost-and-found.php` (removed `&` from filename)
- `AdressRepository` → `AddressRepository` (fixed typo)

### Cleanup
- Removed `/pages/` directory (empty after moves)
- Removed `/class/` directory (empty after moves)

## Files Modified

### Root
- `autoloader.php` - Updated to point to `src/Classes/`
- `index.php` - Now redirects to `public_html/index.php`

### Config
- `config/paths.php` - NEW: Route constants and path definitions

### Source
- `src/Includes/header.php` - Updated navigation links to `public_html/` paths
- `src/Includes/booking-popup.php` - Updated form action URL
- `src/Classes/AddressRepository.php` - Fixed class name typo

### Public HTML (all pages)
- `public_html/index.php` - NEW: Home page
- `public_html/weather.php` - Updated require paths, image paths
- `public_html/moon.php` - Updated require paths, JS paths
- `public_html/hiking-guide.php` - Updated include paths, image paths
- `public_html/equipment.php` - Updated include paths, image paths
- `public_html/community.php` - Redirects to hiking-guide
- `public_html/bookings.php` - Updated autoloader, include, and link paths
- `public_html/auth/login.php` - Updated include and link paths
- `public_html/auth/signup.php` - Updated include paths
- `public_html/auth/processLogin.php` - Updated autoloader and redirect paths
- `public_html/auth/processSignUp.php` - Updated autoloader, redirect paths, fixed class name
- `public_html/auth/logout.php` - Updated redirect path
- `public_html/catalogue/index.php` - Updated autoloader and include paths
- `public_html/catalogue/details.php` - Updated autoloader, include, and link paths
- `public_html/catalogue/book.php` - Updated autoloader and redirect paths
- `public_html/catalogue/gridItem.php` - Updated detail link paths
- `public_html/catalogue/listItem.php` - Updated detail link paths
- `public_html/lost-and-found/lost-and-found.php` - Renamed from lost&found.php, updated paths
- `public_html/lost-and-found/add_post.php` - Updated CSS/JS paths
- `public_html/lost-and-found/save_post.php` - Updated autoloader and redirect paths
- `public_html/lost-and-found/delete_post.php` - Updated autoloader and redirect paths
- `public_html/lost-and-found/itemlist.php` - Updated autoloader path
- `public_html/search-engine/index.php` - Updated autoloader and include paths

## Known Issues

- The `tmp_refactor.py` helper script is still in the root (can be deleted)
- Some CSS files in `css/pages/` may have naming inconsistencies (community.css, home.css, manual.css, sites.css)
- The `public_html/community.php` currently just redirects to hiking-guide.php
