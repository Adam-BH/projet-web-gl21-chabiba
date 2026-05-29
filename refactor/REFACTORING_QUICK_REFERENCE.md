# HIKI Refactoring: Quick Reference Guide

## 📋 New Directory Structure (One-Page Overview)

```
projet-web-gl21-chabiba/
├── api/                          # JSON endpoints (consolidate here)
├── assets/                        # Fonts, Images
├── src/                           # NEW: Backend code
│   ├── Classes/                   # Repositories, Services, ConnexionDB
│   └── Includes/                  # header.php, booking-popup.php
├── css/                           # Unchanged structure (keep as-is)
├── js/                            # Populate /js/pages/ (currently empty)
├── database/                      # init.sql, DB.md
├── public_html/                   # NEW: All .php page routes
│   ├── auth/
│   ├── catalogue/
│   ├── lost-and-found/            # Renamed from lost&found
│   └── [other pages].php
├── config/                        # NEW: paths.php for constants
└── index.php                      # Entry point (redirects to public_html/index.php)
```

---

## 🔑 Key Changes At-a-Glance

| Aspect              | Old                                                | New                      | Why                          |
| ------------------- | -------------------------------------------------- | ------------------------ | ---------------------------- |
| **Classes**         | `/class/`                                          | `/src/Classes/`          | PSR-4 organization           |
| **Global Includes** | `/pages/includes/`                                 | `/src/Includes/`         | Separate logic from views    |
| **Page Routes**     | `/pages/*.php`                                     | `/public_html/*.php`     | Clear separation of concerns |
| **API Endpoints**   | Split across `/api/` & `/pages/search-engine/api/` | All in `/api/`           | Single source of truth       |
| **Special Chars**   | `lost&found/`                                      | `lost-and-found/`        | URL-safe naming              |
| **HTML Pages**      | `/pages/*.html` (duplicates)                       | Merged into `.php`       | Single source per page       |
| **JS Pages**        | `/js/` (flat)                                      | `/js/pages/` (organized) | Match page structure         |

---

## ⚡ Path Conversion Cheat Sheet

### Require Paths (Class Files)

| Context                                             | Old                                                      | New                                                               |
| --------------------------------------------------- | -------------------------------------------------------- | ----------------------------------------------------------------- |
| **From `/pages/weather.php`**                       | `require_once __DIR__ . '/../class/WeatherService.php';` | `require_once __DIR__ . '/../src/Classes/WeatherService.php';`    |
| **From `/pages/auth/login.php`**                    | `require_once __DIR__ . '/../../class/ConnexionDB.php';` | `require_once __DIR__ . '/../../src/Classes/ConnexionDB.php';`    |
| **From `/public_html/auth/login.php` (AFTER move)** | ← (different context)                                    | `require_once __DIR__ . '/../../../src/Classes/ConnexionDB.php';` |

### Include Paths (Views/Partials)

| Context                                             | Old                                         | New                                                   |
| --------------------------------------------------- | ------------------------------------------- | ----------------------------------------------------- |
| **From `/pages/weather.php`**                       | `include __DIR__ . '/includes/header.php';` | `include __DIR__ . '/../src/Includes/header.php';`    |
| **From `/pages/auth/login.php`**                    | `include __DIR__ . '/includes/header.php';` | `include __DIR__ . '/../src/Includes/header.php';`    |
| **From `/public_html/auth/login.php` (AFTER move)** | ←                                           | `include __DIR__ . '/../../src/Includes/header.php';` |

### Asset Paths (Images, CSS, JS)

| Type       | Old                                            | New                                                     |
| ---------- | ---------------------------------------------- | ------------------------------------------------------- |
| **Images** | `src="../assets/Images/icon.png"`              | `src="/projet-web-gl21-chabiba/assets/Images/icon.png"` |
| **CSS**    | `href="/projet-web-gl21-chabiba/css/page.css"` | ← Same (no change)                                      |
| **JS**     | `src="/projet-web-gl21-chabiba/js/main.js"`    | ← Same (no change)                                      |

### Navigation Links

| Type             | Old                                                               | New                                                                             |
| ---------------- | ----------------------------------------------------------------- | ------------------------------------------------------------------------------- |
| **Weather**      | `href="/projet-web-gl21-chabiba/pages/weather.php"`               | `href="/projet-web-gl21-chabiba/public_html/weather.php"`                       |
| **Catalogue**    | `href="/projet-web-gl21-chabiba/pages/catalogue/index.php"`       | `href="/projet-web-gl21-chabiba/public_html/catalogue/index.php"`               |
| **Lost & Found** | `href="/projet-web-gl21-chabiba/pages/lost&found/lost&found.php"` | `href="/projet-web-gl21-chabiba/public_html/lost-and-found/lost-and-found.php"` |

### API Endpoints

| Endpoint         | Old                                                          | New                                                 |
| ---------------- | ------------------------------------------------------------ | --------------------------------------------------- |
| **Book Site**    | `action="/projet-web-gl21-chabiba/pages/catalogue/book.php"` | `action="/projet-web-gl21-chabiba/api/booking.php"` |
| **Search Sites** | `POST to /pages/search-engine/api/search.php`                | `POST to /api/search.php`                           |
| **Get Sites**    | `GET /pages/search-engine/api/sites.php`                     | `GET /api/sites.php`                                |

---

## 📂 Complete File Mapping

### Files to Move (Path Updates Required)

```
OLD PATH                                 NEW PATH                                   DEPTH CHANGE
────────────────────────────────────────────────────────────────────────────────────────────────
/class/*.php                         →   /src/Classes/*.php                        (same level, different folder)
/pages/includes/header.php           →   /src/Includes/header.php                  (same level, different folder)
/pages/includes/booking-popup.php    →   /src/Includes/booking-popup.php           (same level, different folder)

/pages/weather.php                   →   /public_html/weather.php                  (same depth: one level up)
/pages/hiking-guide.php              →   /public_html/hiking-guide.php             (same depth)
/pages/equipment.php                 →   /public_html/equipment.php                (same depth)
/pages/moon.php                      →   /public_html/moon.php                     (same depth)
/pages/community.php                 →   /public_html/community.php                (same depth)
/pages/bookings.php                  →   /public_html/bookings.php                 (same depth)

/pages/auth/*.php                    →   /public_html/auth/*.php                   (depth +1: add one /)
/pages/catalogue/*.php               →   /public_html/catalogue/*.php              (depth +1: add one /)
/pages/lost&found/ → lost-and-found/ →   /public_html/lost-and-found/              (depth +1: add one /)

/api/getTrails.php                   →   /api/getTrails.php                        (no change)
/pages/search-engine/api/book.php    →   /api/booking.php                          (consolidate)
/pages/search-engine/api/sites.php   →   /api/sites.php                            (consolidate)
```

### Files to Consolidate (Merge & Delete)

```
MERGE INTO                           SOURCE TO MERGE                   ACTION
────────────────────────────────────────────────────────────────────────────────────────
/pages/community.php                 /pages/community.html             Extract HTML, merge into PHP, delete .html
/pages/equipment.php                 /pages/equipment.html             Extract HTML, merge into PHP, delete .html
/pages/weather.php                   /pages/weather.html               Delete .html (already has PHP)
/pages/moon.php                      /pages/moon.html                  Delete .html (already has PHP)
/pages/bookings.php                  /pages/availability.html          Merge if content differs, delete .html
────────────────────────────────────────────────────────────────────────────────────────
DELETE (Deprecated)
────────────────────────────────────────────────────────────────────────────────────────
/pages/shops.html                    (Commented out in nav, unused)
/pages/sites/                        (Content duplicated in /pages/catalogue/)
/pages/lost-and-found/               (Empty folder; actual content in /pages/lost&found/)
```

---

## 🎯 Execution Phases (Summary)

| Phase     | Goal                            | Est. Time      | Risk                          |
| --------- | ------------------------------- | -------------- | ----------------------------- |
| **0**     | Backup & Prepare                | 15 min         | Low                           |
| **1-3**   | Setup autoloader & paths        | 30 min         | Low                           |
| **4**     | Move class files                | 15 min         | **Medium** (test immediately) |
| **5**     | Move includes                   | 5 min          | Low                           |
| **6**     | Consolidate HTML/PHP            | 30 min         | Low                           |
| **7-8**   | Update all paths & links        | 2 hrs          | **High** (most error-prone)   |
| **9**     | Consolidate API                 | 20 min         | Medium                        |
| **10-11** | Move pages to `/public_html/`   | 30 min         | **High** (files in transit)   |
| **12-14** | Create entry point, organize JS | 20 min         | Low                           |
| **15-17** | Test & cleanup                  | 1 hr           | Low                           |
| **18**    | Commit & document               | 15 min         | Low                           |
| **TOTAL** |                                 | **~5-6 hours** | Manageable                    |

---

## ✅ Testing Checklist

After each major phase:

- [ ] Visit home: `http://localhost/projet-web-gl21-chabiba/`
- [ ] Click all nav links
- [ ] Test signup/login
- [ ] Test booking flow
- [ ] Test lost & found
- [ ] Browser console: no 404s
- [ ] Network tab: all assets load
- [ ] Database: auth works
- [ ] Session: persists across pages

---

## 🆘 Troubleshooting

| Issue                              | Likely Cause                       | Fix                                                                |
| ---------------------------------- | ---------------------------------- | ------------------------------------------------------------------ |
| **"Fatal error: Class not found"** | Autoloader path wrong              | Check `/autoloader.php` and ensure `/src/Classes/` path is correct |
| **"Failed to include header.php"** | Include path has wrong `../` depth | Check require depth from current file location                     |
| **"404 on image/CSS"**             | Asset path changed                 | Use absolute paths like `/projet-web-gl21-chabiba/assets/...`      |
| **"Lost & Found link broken"**     | URL has `&` character              | Update to `/public_html/lost-and-found/`                           |
| **"Auth failed after move"**       | Redirect path outdated             | Update `/public_html/auth/processLogin.php` redirects              |
| **"Booking form not submitting"**  | API endpoint path wrong            | Update `action=` in booking popup to `/api/booking.php`            |

---

## 📝 During Execution: Update This Line As You Progress

```
CURRENT PHASE: [ ]  COMPLETED: 0/18 phases  ESTIMATED TIME: 5-6 hours
LAST UPDATED: [DATE]  ISSUES FOUND: 0
```

---

## 🎓 Best Practices During Migration

1. **Commit after each phase** to git

   ```bash
   git commit -m "Phase X: [description]"
   ```

2. **Test in browser immediately** after major changes
   - Don't batch multiple changes without testing

3. **Use grep/find to update paths systematically**

   ```bash
   # Example: Find all files requiring classes
   grep -r "require.*class/" pages/ --include="*.php"
   ```

4. **Keep the full REFACTORING_PLAN.md open** while executing
   - Reference the detailed steps in PART 4

5. **Document any deviations** in MIGRATION_LOG.md
   - "Phase 7: Found additional require in file X, updated"

---

## 📞 Quick Links

- **Full Plan**: See `REFACTORING_PLAN.md` (detailed step-by-step)
- **Progress Tracker**: See `MIGRATION_LOG.md` (to be created)
- **Backup Location**: `backup/pre-refactor` (git branch)

---

**Version**: 1.0 | **Status**: Ready to Execute | **Date**: 2026-05-29
