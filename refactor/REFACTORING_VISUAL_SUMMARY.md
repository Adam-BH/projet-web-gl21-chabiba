# HIKI Refactoring: Visual Architecture Summary

## 🏗️ Current vs. New Architecture

### CURRENT STRUCTURE (Multi-Developer Chaos)

```
projet-web-gl21-chabiba/
├── class/                          ← Scattered, hard to find
│   ├── Repository.php
│   ├── WeatherService.php
│   └── ... (11 files all at root of class/)
│
├── pages/                          ← Mixed routing & includes
│   ├── includes/                   ← Separated from logic
│   │   ├── header.php
│   │   └── booking-popup.php
│   ├── auth/
│   ├── catalogue/
│   ├── lost&found/ (SPECIAL CHAR)
│   ├── lost-and-found/ (EMPTY)
│   ├── manual/
│   ├── search-engine/
│   │   └── api/                    ← API scattered here
│   │       ├── book.php
│   │       ├── search.php
│   │       └── sites.php
│   └── [HTML & PHP duplicates]     ← community.html/php, equipment.html/php
│
├── api/                            ← API also here (fragmented)
│   ├── getTrails.php
│   └── search/
│
├── js/
│   ├── pages/                      ← EMPTY
│   ├── [flat page scripts]         ← In root js/ (should be organized)
│   └── ...
│
└── index.php                       ← Root entry point (hardcoded paths)
```

### NEW STRUCTURE (Clean & Scalable)

```
projet-web-gl21-chabiba/
│
├── 🔧 BACKEND CODE
│   └── src/
│       ├── Classes/                ← All PHP logic (PSR-4)
│       │   ├── Repository.php
│       │   ├── UserRepository.php
│       │   ├── WeatherService.php
│       │   └── cache/
│       │
│       └── Includes/               ← Shared layout components
│           ├── header.php
│           └── booking-popup.php
│
├── 🌐 PUBLIC ROUTES (User-facing)
│   └── public_html/
│       ├── index.php               ← Home page
│       ├── weather.php
│       ├── equipment.php
│       ├── moon.php
│       ├── community.php
│       ├── bookings.php
│       │
│       ├── auth/
│       │   ├── login.php
│       │   ├── signup.php
│       │   ├── processLogin.php
│       │   └── processSignUp.php
│       │
│       ├── catalogue/
│       │   ├── index.php
│       │   ├── details.php
│       │   ├── book.php
│       │   ├── gridItem.php
│       │   └── listItem.php
│       │
│       ├── lost-and-found/         ← URL-safe name
│       │   ├── lost-and-found.php
│       │   ├── add_post.php
│       │   ├── save_post.php
│       │   ├── delete_post.php
│       │   └── itemlist.php
│       │
│       └── manual/
│           ├── fire.html
│           ├── hiking.html
│           └── tent.html
│
├── 📡 API ENDPOINTS (All consolidated)
│   └── api/
│       ├── getTrails.php           ← (existing)
│       ├── search.php              ← (consolidated)
│       ├── booking.php             ← (from pages/search-engine/api/book.php)
│       ├── sites.php               ← (from pages/search-engine/api/sites.php)
│       └── search/
│
├── 🎨 FRONTEND ASSETS
│   ├── css/
│   │   ├── base/
│   │   ├── layout/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── shared/
│   │   └── main.css
│   │
│   ├── js/
│   │   ├── api/
│   │   ├── pages/                  ← NOW ORGANIZED (was empty)
│   │   ├── shared/
│   │   ├── features/
│   │   └── data/
│   │
│   └── assets/
│       ├── Images/
│       └── Fonts/
│
├── 💾 DATABASE
│   └── database/
│       ├── init.sql
│       └── DB.md
│
├── ⚙️ CONFIGURATION
│   └── config/
│       └── paths.php               ← NEW: Centralized constants
│
├── 📄 ENTRY POINTS
│   ├── index.php                   ← Redirects to public_html/
│   └── autoloader.php              ← Global autoloader
│
└── 📚 DOCUMENTATION
    ├── REFACTORING_PLAN.md         ← Full execution guide
    ├── REFACTORING_QUICK_REFERENCE.md
    ├── REFACTORING_CODE_EXAMPLES.md
    ├── MIGRATION_LOG.md            ← Track progress
    ├── CONTEXT.md
    └── README.md
```

---

## 🔄 Key Transformations

### Transformation 1: Classes Organization

```
BEFORE                          AFTER
────────────────────────────────────────
/class/
  Repository.php       →        /src/Classes/
  Irepository.php      →          Repository.php
  UserRepository.php   →          IRepository.php (fixed case)
  ConnexionDB.php      →          UserRepository.php
  WeatherService.php   →          ConnexionDB.php
  ...etc (11 files)    →          WeatherService.php
                                  ...etc (organized in one place)
```

**Benefits:**
✅ PSR-4 standard compliance  
✅ Easier to add new repositories  
✅ Single source for all business logic

---

### Transformation 2: Page Routing

```
BEFORE                          AFTER
────────────────────────────────────────
/pages/
  weather.php          →        /public_html/
  auth/                →          weather.php
  catalogue/           →          auth/
  lost&found/ ❌       →          catalogue/
  ...etc               →          lost-and-found/ ✅
                                  ...etc

ISSUE: Mixed concerns            SOLUTION: Clear separation
- Pages & includes both in        - Views in /public_html/
  /pages/                         - Logic in /src/
- No clear public/private         - Public routes obvious
  boundary                        - Private code hidden
```

**Benefits:**
✅ Clear public/private separation  
✅ URL-safe folder names  
✅ No duplicate HTML/PHP files

---

### Transformation 3: API Consolidation

```
BEFORE                          AFTER
────────────────────────────────────────
/api/
  getTrails.php        →        /api/
  search/              →          getTrails.php
                                  search.php (consolidated)
/pages/search-engine/api/        booking.php (moved)
  book.php             →          sites.php (moved)
  search.php           →          search/
  sites.php            →

ISSUE: APIs split across          SOLUTION: Single /api/
two locations                     folder for all endpoints
```

**Benefits:**
✅ Single API namespace  
✅ Easier API documentation  
✅ Consistent endpoint patterns

---

### Transformation 4: JavaScript Organization

```
BEFORE                          AFTER
────────────────────────────────────────
/js/
  weather.js           →        /js/
  community.js         →          pages/
  equipment.js         →            weather.js
  hiking-guide.js      →            community.js
  moon-page.js         →            equipment.js
  l&f.js               →            hiking-guide.js
  pages/               →            moon.js
    (empty!)           →            lost-and-found.js
  auth.js              →            auth.js
  booking.js           →            bookings.js
  main.js              →          shared/
  ...etc               →            main.js
                                    stars-bg.js
                                  ...etc

ISSUE: Page scripts scattered     SOLUTION: Organized by page
in root, /pages/ empty            matching file structure
```

**Benefits:**
✅ Easier to locate page-specific JS  
✅ Clear distinction: shared vs. page-specific  
✅ Reduced cognitive load

---

## 🔗 Path Reference Matrix

### From Different Locations (After Migration)

#### Accessing Classes

```
FROM: /public_html/weather.php
TO:   /src/Classes/WeatherService.php
PATTERN: require_once __DIR__ . '/../src/Classes/WeatherService.php';

FROM: /public_html/auth/login.php
TO:   /src/Classes/ConnexionDB.php
PATTERN: require_once __DIR__ . '/../../src/Classes/ConnexionDB.php';

FROM: /src/Includes/header.php
TO:   /src/Classes/ (autoloaded via /autoloader.php)
PATTERN: new WeatherService(); // via autoloader
```

#### Accessing Includes

```
FROM: /public_html/weather.php
TO:   /src/Includes/header.php
PATTERN: include __DIR__ . '/../src/Includes/header.php';

FROM: /public_html/auth/login.php
TO:   /src/Includes/header.php
PATTERN: include __DIR__ . '/../../src/Includes/header.php';

FROM: /public_html/catalogue/details.php
TO:   /src/Includes/booking-popup.php (included by header)
PATTERN: include __DIR__ . '/../../src/Includes/header.php';
```

#### Accessing Assets

```
FROM: Any .php file (use absolute paths)
TO:   /css/pages/weather.css
PATTERN: <link rel="stylesheet" href="/projet-web-gl21-chabiba/css/pages/weather.css">

FROM: Any .php file
TO:   /assets/Images/icon.png
PATTERN: <img src="/projet-web-gl21-chabiba/assets/Images/icon.png">

FROM: Any .php file
TO:   /js/pages/weather.js
PATTERN: <script src="/projet-web-gl21-chabiba/js/pages/weather.js"></script>
```

#### Accessing API

```
FROM: Booking popup form
TO:   /api/booking.php
PATTERN: <form action="/projet-web-gl21-chabiba/api/booking.php" method="POST">

FROM: Lost & Found add form
TO:   /api/posts/save.php
PATTERN: <form action="/projet-web-gl21-chabiba/api/posts/save.php" method="POST">

FROM: JavaScript in browser
TO:   /api/search.php
PATTERN: fetch('/projet-web-gl21-chabiba/api/search.php', {...})
```

---

## 📊 Migration Timeline & Impact

### Phase Grouping

| Phase Group         | Phases | Duration     | Risk              | Breakage                         |
| ------------------- | ------ | ------------ | ----------------- | -------------------------------- |
| **Setup**           | 0-3    | 45 min       | 🟢 Low            | 🟢 None                          |
| **Class Migration** | 4-5    | 20 min       | 🟡 Medium         | 🟡 Pages may fail                |
| **Consolidation**   | 6      | 30 min       | 🟢 Low            | 🟢 None                          |
| **Path Updates**    | 7-9    | 2 hrs        | 🔴 High           | 🔴 Most likely breakage          |
| **File Moves**      | 10-11  | 30 min       | 🔴 High           | 🔴 Everything breaks until fixed |
| **Finalization**    | 12-18  | 1.5 hrs      | 🟢 Low            | 🟢 Recovery time                 |
| **TOTAL**           |        | **~5-6 hrs** | **🟡 Manageable** | **~5 hours downtime**            |

### Post-Migration Benefits

```
Before: 👎
├─ 4 developers each with their own patterns
├─ No consistent autoloading
├─ 28+ hardcoded path strings
├─ HTML/PHP duplication
├─ API endpoints scattered
└─ Empty /js/pages/ folder causing confusion

After: 👍
├─ Single unified structure
├─ PSR-4 autoloading
├─ 6 centralized path constants (in config/paths.php)
├─ PHP-only (no HTML copies)
├─ All APIs in one folder
├─ /js/pages/ fully organized
├─ URL-safe folder names (no &)
├─ Clear public/private separation
└─ Scalable for team growth
```

---

## 🎯 Success Criteria

### Phase-by-Phase Verification

| Phase        | Success Criteria                                             |
| ------------ | ------------------------------------------------------------ |
| **Phase 0**  | ✅ Backup created; baseline tested                           |
| **Phase 1**  | ✅ All directories exist                                     |
| **Phase 2**  | ✅ Root `/autoloader.php` works; `/config/paths.php` created |
| **Phase 4**  | ✅ Home page loads; classes auto-loaded                      |
| **Phase 7**  | ✅ All path updates complete; no 404s in console             |
| **Phase 10** | ✅ Files moved; all pages still load (with updated requires) |
| **Phase 11** | ✅ All requires adjusted for new depth                       |
| **Phase 15** | ✅ Full browser test passes; no console errors               |
| **Phase 18** | ✅ Git history clean; documentation updated                  |

---

## 🚨 Rollback Decision Tree

```
Something broke! Now what?

1. Is it a single file?
   ├─ YES → Fix that file's paths
   └─ NO → Go to step 2

2. Did we just move files?
   ├─ YES → Revert the move and check paths
   └─ NO → Go to step 3

3. Is it a database/session error?
   ├─ YES → Check redirect paths in processLogin.php
   └─ NO → Go to step 4

4. Do multiple pages have errors?
   ├─ YES → Check /autoloader.php or /src/Includes/header.php
   └─ NO → Check individual file paths

5. Still stuck?
   ├─ Do git rollback:
   │   git reset --hard HEAD~1
   │   (or restore from backup branch)
   └─ Re-examine the error from scratch
```

---

## 📈 Future-Proofing

### After Migration, This Becomes Easy:

```
✅ Add new page:
   1. Create /public_html/[page].php
   2. Require necessary /src/Classes/
   3. Include /src/Includes/header.php
   4. Done!

✅ Add new repository:
   1. Create /src/Classes/[Entity]Repository.php
   2. Extend /src/Classes/Repository.php
   3. Autoloader handles the rest

✅ Add new API endpoint:
   1. Create /api/[endpoint].php
   2. Require necessary classes
   3. Output JSON
   4. Done!

✅ Add page-specific JS:
   1. Create /js/pages/[page].js
   2. Include in page's $extraStyles
   3. Header auto-loads it

✅ Add page-specific CSS:
   1. Create /css/pages/[page].css
   2. Include in page's $extraStyles
   3. Header auto-loads it
```

### Code Reusability Pattern

```php
// New /public_html/hiking.php follows the standard pattern:

<?php
require_once __DIR__ . '/../autoloader.php';
require_once __DIR__ . '/../src/Classes/TrailRepository.php';

$pageTitle = 'HIKI - Hiking Guide';
$pageActive = 'hiking_guide';
$extraStyles = ['css/pages/hiking-guide.css'];

// Autoloader automatically loads these via include:
// - /src/Includes/header.php (includes /src/Includes/booking-popup.php)
// - Bootstrap and other global styles
include __DIR__ . '/../src/Includes/header.php';

// Page logic
$trails = (new TrailRepository())->getAll();
?>

<!-- HTML output below -->
<main>
  ...
</main>
```

---

## 🎓 Lessons Learned (For Next Time)

### To Avoid This Mess in the Future:

1. **Define structure BEFORE code** ✅ Document folder layout in onboarding
2. **Use autoloaders early** ✅ PSR-4 standard adoption
3. **Separate logic from views** ✅ MVC pattern or similar
4. **Use constants for paths** ✅ `/config/paths.php` approach
5. **Code review checklist** ✅ "Does this file have hardcoded paths?"
6. **URL-safe naming** ✅ No special characters in folder names
7. **Avoid duplicates** ✅ Single source of truth
8. **Team communication** ✅ Shared style guide

---

## 📞 Quick Problem Solving

| Problem                           | Cause                    | Solution                                                  |
| --------------------------------- | ------------------------ | --------------------------------------------------------- |
| **Pages load but CSS/JS missing** | Asset paths broken       | Verify `/projet-web-gl21-chabiba/` prefix in header.php   |
| **"Class not found" error**       | Autoloader not working   | Check `/autoloader.php` and `/src/Classes/` folder exists |
| **Login redirects to wrong page** | Redirect paths outdated  | Update `processLogin.php` redirects to `/public_html/`    |
| **Lost & Found URL broken**       | Still using `lost&found` | Ensure navigation uses `lost-and-found`                   |
| **Images 404ing**                 | Wrong image path         | Check for uppercase vs. lowercase in `/assets/Images/`    |
| **Session lost between pages**    | Session dir permissions  | Ensure session can be written; check .htaccess            |
| **API not responding**            | Form action wrong        | Verify form `action="/api/[endpoint].php"`                |

---

**This transformation is like renovating a house:**  
Old: Every room different style, different plumbing, confusing layout  
New: Standardized design, organized spaces, clearly labeled areas, scalable for additions

**Status**: Ready for execution  
**Estimated Timeline**: 5-6 hours of focused work  
**Team Size Recommended**: 1-2 developers for execution + 1 for testing

---

**Document Version**: 1.0 | **Created**: 2026-05-29
