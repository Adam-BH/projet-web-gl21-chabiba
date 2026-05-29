# HIKI Refactoring: Path Migration Code Examples

This document shows exact before/after code for every file that needs path updates during the migration.

---

## Navigation & Include Paths

### 1. `/src/Includes/header.php` (formerly `/pages/includes/header.php`)

**PHASE 7: Update this file BEFORE moving**

**BEFORE** (in `/pages/includes/header.php`):

```php
<?php
$pageTitle = $pageTitle ?? 'HIKI';
$pageActive = $pageActive ?? '';
$extraStyles = $extraStyles ?? [];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$navItems = [
    ['key' => 'home', 'label' => 'home', 'href' => '/projet-web-gl21-chabiba/index.php'],
    ['key' => 'weather', 'label' => 'weather', 'href' => '/projet-web-gl21-chabiba/pages/weather.php'],
    ['key' => 'guide', 'label' => 'hiking guide', 'href' => '/projet-web-gl21-chabiba/pages/hiking-guide.php'],
    ['key' => 'equipment', 'label' => 'equipment', 'href' => '/projet-web-gl21-chabiba/pages/equipment.php'],
    ['key' => 'moon', 'label' => 'moon', 'href' => '/projet-web-gl21-chabiba/pages/moon.php'],
    ['key' => 'catalogue', 'label' => 'camping sites', 'href' => '/projet-web-gl21-chabiba/pages/catalogue/index.php'],
];

if (!empty($_SESSION['is_logged'])) {
    $navItems[] = ['key' => 'bookings', 'label' => 'my bookings', 'href' => '/projet-web-gl21-chabiba/pages/bookings.php'];
}

$navItems[] = ['key' => 'lostfound', 'label' => 'Lost & found', 'href' => '/projet-web-gl21-chabiba/pages/lost&found/lost&found.php'];
?>
<!doctype html>
...
            <a href="/projet-web-gl21-chabiba/pages/auth/logout.php" class="nav-btn outline">Logout</a>
...
            <a href="/projet-web-gl21-chabiba/pages/auth/login.php" class="nav-btn outline me-2">Login</a>
            <a href="/projet-web-gl21-chabiba/pages/auth/signup.php" class="nav-btn primary">Sign Up</a>
...
    <div class="page-shell">
        <?php include __DIR__ . '/booking-popup.php'; ?>
```

**AFTER** (in `/src/Includes/header.php`, PHASE 8):

```php
<?php
$pageTitle = $pageTitle ?? 'HIKI';
$pageActive = $pageActive ?? '';
$extraStyles = $extraStyles ?? [];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$navItems = [
    ['key' => 'home', 'label' => 'home', 'href' => '/projet-web-gl21-chabiba/public_html/index.php'],                    // CHANGED: pages/ → public_html/
    ['key' => 'weather', 'label' => 'weather', 'href' => '/projet-web-gl21-chabiba/public_html/weather.php'],           // CHANGED: pages/ → public_html/
    ['key' => 'guide', 'label' => 'hiking guide', 'href' => '/projet-web-gl21-chabiba/public_html/hiking-guide.php'],   // CHANGED: pages/ → public_html/
    ['key' => 'equipment', 'label' => 'equipment', 'href' => '/projet-web-gl21-chabiba/public_html/equipment.php'],     // CHANGED: pages/ → public_html/
    ['key' => 'moon', 'label' => 'moon', 'href' => '/projet-web-gl21-chabiba/public_html/moon.php'],                   // CHANGED: pages/ → public_html/
    ['key' => 'catalogue', 'label' => 'camping sites', 'href' => '/projet-web-gl21-chabiba/public_html/catalogue/index.php'],  // CHANGED: pages/ → public_html/
];

if (!empty($_SESSION['is_logged'])) {
    $navItems[] = ['key' => 'bookings', 'label' => 'my bookings', 'href' => '/projet-web-gl21-chabiba/public_html/bookings.php'];  // CHANGED: pages/ → public_html/
}

$navItems[] = ['key' => 'lostfound', 'label' => 'Lost & found', 'href' => '/projet-web-gl21-chabiba/public_html/lost-and-found/lost-and-found.php'];  // CHANGED: pages/ → public_html/ AND lost&found → lost-and-found
?>
<!doctype html>
...
            <a href="/projet-web-gl21-chabiba/public_html/auth/logout.php" class="nav-btn outline">Logout</a>           <!-- CHANGED: pages/ → public_html/ -->
...
            <a href="/projet-web-gl21-chabiba/public_html/auth/login.php" class="nav-btn outline me-2">Login</a>         <!-- CHANGED: pages/ → public_html/ -->
            <a href="/projet-web-gl21-chabiba/public_html/auth/signup.php" class="nav-btn primary">Sign Up</a>           <!-- CHANGED: pages/ → public_html/ -->
...
    <div class="page-shell">
        <?php include __DIR__ . '/booking-popup.php'; ?>  <!-- Same path (stays in src/Includes/) -->
```

---

### 2. `/src/Includes/booking-popup.php`

**PHASE 8: Update form action**

**BEFORE** (in `/pages/includes/booking-popup.php`):

```php
<form id="bookingForm" method="POST" action="/projet-web-gl21-chabiba/pages/catalogue/book.php">
    ...
    <a href="/projet-web-gl21-chabiba/pages/auth/login.php" id="bookingLogin" class="btn btn-primary">Log in to book</a>
</form>
```

**AFTER** (in `/src/Includes/booking-popup.php`):

```php
<form id="bookingForm" method="POST" action="/projet-web-gl21-chabiba/api/booking.php">  <!-- CHANGED: pages/catalogue/book.php → api/booking.php -->
    ...
    <a href="/projet-web-gl21-chabiba/public_html/auth/login.php" id="bookingLogin" class="btn btn-primary">Log in to book</a>  <!-- CHANGED: pages/ → public_html/ -->
</form>
```

---

## Page-Specific File Updates

### 3. `/public_html/weather.php` (formerly `/pages/weather.php`)

**PHASE 7: Update paths BEFORE moving**

**BEFORE** (while still in `/pages/weather.php`):

```php
<?php
require_once __DIR__ . '/../class/WeatherService.php';

$requestedCity = trim((string)($_GET['city'] ?? 'Tunis'));
$forecast = (new WeatherService())->getForecast($requestedCity);
...
$pageTitle = 'HIKI — Weather';
$pageActive = 'weather';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/weather-page.css'];
include __DIR__ . '/includes/header.php';
?>
```

**PHASE 7 (UPDATED, still in `/pages/`):**

```php
<?php
require_once __DIR__ . '/../src/Classes/WeatherService.php';  // CHANGED: class/ → src/Classes/

$requestedCity = trim((string)($_GET['city'] ?? 'Tunis'));
$forecast = (new WeatherService())->getForecast($requestedCity);
...
$pageTitle = 'HIKI — Weather';
$pageActive = 'weather';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/weather.css'];  // CHANGED: weather-page.css → weather.css (optional rename)
include __DIR__ . '/../src/Includes/header.php';  // CHANGED: includes/ → ../src/Includes/
?>
```

**PHASE 11 (AFTER moved to `/public_html/weather.php`):**

```php
<?php
require_once __DIR__ . '/../src/Classes/WeatherService.php';  // SAME (depth unchanged - both are one level down)

$requestedCity = trim((string)($_GET['city'] ?? 'Tunis'));
$forecast = (new WeatherService())->getForecast($requestedCity);
...
$pageTitle = 'HIKI — Weather';
$pageActive = 'weather';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/weather.css'];
include __DIR__ . '/../src/Includes/header.php';  // SAME (depth unchanged)
?>
```

---

### 4. `/public_html/auth/login.php` (formerly `/pages/auth/login.php`)

**PHASE 7: Update paths BEFORE moving**

**BEFORE** (in `/pages/auth/login.php`):

```php
<?php
session_start();
$hideNavbar = false;
$pageTitle = 'HIKI - Log In';
$pageActive = 'login';
$extraStyles = ['css/pages/auth.css'];
include __DIR__ . '/includes/header.php';  // ❌ WRONG PATH (goes to /pages/includes/)
?>

<form action="processLogin.php" method="POST">
    ...
    <p>Don't have an account? <a href="/projet-web-gl21-chabiba/pages/auth/signup.php">Sign up</a></p>
</form>
```

**PHASE 7 (UPDATED, still in `/pages/auth/`):**

```php
<?php
session_start();
$hideNavbar = false;
$pageTitle = 'HIKI - Log In';
$pageActive = 'login';
$extraStyles = ['css/pages/auth.css'];
include __DIR__ . '/../src/Includes/header.php';  // CHANGED: /includes/ → /../src/Includes/
?>

<form action="processLogin.php" method="POST">  <!-- Can stay relative (same directory) -->
    ...
    <p>Don't have an account? <a href="/projet-web-gl21-chabiba/public_html/auth/signup.php">Sign up</a></p>  <!-- CHANGED: pages/ → public_html/ -->
</form>
```

**PHASE 11 (AFTER moved to `/public_html/auth/login.php`):**

```php
<?php
session_start();
$hideNavbar = false;
$pageTitle = 'HIKI - Log In';
$pageActive = 'login';
$extraStyles = ['css/pages/auth.css'];
include __DIR__ . '/../../src/Includes/header.php';  // CHANGED: /../src/Includes/ → /../../src/Includes/ (add one more ../)
?>

<form action="processLogin.php" method="POST">  <!-- SAME (stays relative) -->
    ...
    <p>Don't have an account? <a href="/projet-web-gl21-chabiba/public_html/auth/signup.php">Sign up</a></p>  <!-- SAME -->
</form>
```

---

### 5. `/public_html/auth/processLogin.php` (formerly `/pages/auth/processLogin.php`)

**PHASE 7: Update paths & redirects**

**BEFORE**:

```php
<?php
session_start();
require_once __DIR__ . '/../../class/ConnexionDB.php';
require_once __DIR__ . '/../../class/UserRepository.php';
...
// After successful login:
header('Location: /projet-web-gl21-chabiba/pages/search-engine/index.php');
exit();
```

**PHASE 7 (UPDATED)**:

```php
<?php
session_start();
require_once __DIR__ . '/../../src/Classes/ConnexionDB.php';         // CHANGED: class/ → src/Classes/
require_once __DIR__ . '/../../src/Classes/UserRepository.php';     // CHANGED: class/ → src/Classes/
...
// After successful login:
header('Location: /projet-web-gl21-chabiba/public_html/index.php');  // CHANGED: pages/search-engine → public_html/
exit();
```

**PHASE 11 (AFTER moved to `/public_html/auth/processLogin.php`)**:

```php
<?php
session_start();
require_once __DIR__ . '/../../../src/Classes/ConnexionDB.php';      // CHANGED: ../../ → ../../../ (add one more ../)
require_once __DIR__ . '/../../../src/Classes/UserRepository.php';  // CHANGED: ../../ → ../../../
...
// After successful login:
header('Location: /projet-web-gl21-chabiba/public_html/index.php');  // SAME
exit();
```

---

### 6. `/public_html/catalogue/book.php` → `/api/booking.php`

**PHASE 9: Move and rename, update paths**

**BEFORE** (in `/pages/catalogue/book.php`):

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
require_once __DIR__ . '/../../class/UserRepository.php';
require_once __DIR__ . '/../../class/BookingRepository.php';
...
if (!$userRow){
    header('Location: /projet-web-gl21-chabiba/pages/auth/login.php');
    exit();
}
```

**PHASE 9 (UPDATED, moved to `/api/booking.php`)**:

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';  // From root, goes to /autoloader.php
require_once __DIR__ . '/../src/Classes/UserRepository.php';   // CHANGED: ../../class/ → /../src/Classes/
require_once __DIR__ . '/../src/Classes/BookingRepository.php'; // CHANGED: ../../class/ → /../src/Classes/
...
if (!$userRow){
    header('Location: /projet-web-gl21-chabiba/public_html/auth/login.php');  // CHANGED: pages/ → public_html/
    exit();
}
```

---

### 7. `/public_html/lost-and-found/lost-and-found.php` (formerly `/pages/lost&found/lost&found.php`)

**PHASE 7: Update paths BEFORE moving**

**BEFORE** (in `/pages/lost&found/lost&found.php`):

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/l&f.css'];
include __DIR__ . '/../includes/header.php';  // ❌ WRONG PATH
?>
```

**PHASE 7 (UPDATED, still in `/pages/lost&found/`):**

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/lost-and-found.css'];  // CHANGED: l&f.css → lost-and-found.css
include __DIR__ . '/../src/Includes/header.php';  // CHANGED: /includes/ → /../src/Includes/
?>
```

**PHASE 10-11 (AFTER moved to `/public_html/lost-and-found/lost-and-found.php`):**

```php
<?php
session_start();
require_once __DIR__ . '/../../autoloader.php';                    // CHANGED: /../ → /../../ (add one more ../)
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/lost-and-found.css'];
include __DIR__ . '/../../src/Includes/header.php';               // CHANGED: /../src/ → /../../src/
?>
```

---

### 8. `/public_html/lost-and-found/add_post.php` (formerly `/pages/lost&found/add_post.php`)

**PHASE 7: Update paths & form action**

**BEFORE** (in `/pages/lost&found/add_post.php`):

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
...
?>

<form action="save_post.php" enctype="multipart/form-data" method="POST">
    ...
</form>
```

**PHASE 7 (UPDATED)**:

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';  // SAME for now
...
?>

<form action="/projet-web-gl21-chabiba/api/posts/save.php" enctype="multipart/form-data" method="POST">  <!-- CHANGED: relative save_post.php → absolute API path -->
    ...
</form>
```

**PHASE 11 (AFTER moved to `/public_html/lost-and-found/add_post.php`)**:

```php
<?php
session_start();
require_once __DIR__ . '/../../autoloader.php';  // CHANGED: /../ → /../../
...
?>

<form action="/projet-web-gl21-chabiba/api/posts/save.php" enctype="multipart/form-data" method="POST">  <!-- SAME -->
    ...
</form>
```

---

### 9. `/public_html/catalogue/details.php` (formerly `/pages/catalogue/details.php`)

**PHASE 7: Update paths**

**BEFORE** (in `/pages/catalogue/details.php`):

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
require_once __DIR__ . '/../../class/CampingSiteRepository.php';
...
$extraStyles = ['css/pages/sites.css', 'css/pages/catalogue.css'];
include __DIR__ . '/../includes/header.php';
?>
...
<a href="/projet-web-gl21-chabiba/pages/catalogue/index.php" style="display:inline-block;margin-bottom:12px;">← Back to catalogue</a>
```

**PHASE 7 (UPDATED)**:

```php
<?php
session_start();
require_once __DIR__ . '/../autoloader.php';
require_once __DIR__ . '/../../src/Classes/CampingSiteRepository.php';  // CHANGED: class/ → src/Classes/
...
$extraStyles = ['css/pages/sites.css', 'css/pages/catalogue.css'];
include __DIR__ . '/../src/Includes/header.php';  // CHANGED: includes/ → src/Includes/
?>
...
<a href="/projet-web-gl21-chabiba/public_html/catalogue/index.php" style="display:inline-block;margin-bottom:12px;">← Back to catalogue</a>  <!-- CHANGED: pages/ → public_html/ -->
```

**PHASE 11 (AFTER moved to `/public_html/catalogue/details.php`)**:

```php
<?php
session_start();
require_once __DIR__ . '/../../autoloader.php';                           // CHANGED: ../ → /../../ (add one more ../)
require_once __DIR__ . '/../../src/Classes/CampingSiteRepository.php';   // CHANGED: ../../ → /../../
...
$extraStyles = ['css/pages/sites.css', 'css/pages/catalogue.css'];
include __DIR__ . '/../../src/Includes/header.php';                      // CHANGED: /../src/ → /../../src/
?>
...
<a href="/projet-web-gl21-chabiba/public_html/catalogue/index.php" style="display:inline-block;margin-bottom:12px;">← Back to catalogue</a>  <!-- SAME -->
```

---

## Root Level Files

### 10. `/index.php` (Root Entry Point) - NEW FILE

**PHASE 12: Create this new file**

```php
<?php
/**
 * Root Entry Point
 *
 * Redirects users to the public_html/index.php
 * This keeps the /projet-web-gl21-chabiba/ URL working
 */
header('Location: /projet-web-gl21-chabiba/public_html/index.php');
exit();
?>
```

---

### 11. `/autoloader.php` - NEW/UPDATED FILE

**PHASE 2: Create at root level**

```php
<?php
/**
 * Global Autoloader for HIKI Project
 *
 * Handles PSR-4 style loading for src/Classes/
 */

spl_autoload_register(function($className) {
    // Remove leading backslash if present
    $className = ltrim($className, '\\');

    // Try to load from src/Classes/
    $file = __DIR__ . '/src/Classes/' . $className . '.php';

    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    return false;
});

// For backward compatibility, also make it work from /pages/autoloader.php
if (!defined('AUTOLOADER_LOADED')) {
    define('AUTOLOADER_LOADED', true);
}
?>
```

---

### 12. `/config/paths.php` - NEW FILE

**PHASE 2: Create configuration constants**

```php
<?php
/**
 * HIKI Project Path Configuration
 *
 * Centralized constants for all path references
 */

// Root path of the project (for absolute URLs)
const PROJECT_ROOT = '/projet-web-gl21-chabiba';

// Filesystem paths
define('BASE_DIR', __DIR__ . '/..');
define('SRC_DIR', BASE_DIR . '/src');
define('CLASSES_DIR', SRC_DIR . '/Classes');
define('INCLUDES_DIR', SRC_DIR . '/Includes');
define('API_DIR', BASE_DIR . '/api');
define('CSS_DIR', BASE_DIR . '/css');
define('JS_DIR', BASE_DIR . '/js');
define('ASSETS_DIR', BASE_DIR . '/assets');
define('DATABASE_DIR', BASE_DIR . '/database');

// Web paths (use in href, src, action, etc.)
const ROUTES = [
    'root' => PROJECT_ROOT,
    'home' => PROJECT_ROOT . '/public_html/index.php',
    'weather' => PROJECT_ROOT . '/public_html/weather.php',
    'hiking_guide' => PROJECT_ROOT . '/public_html/hiking-guide.php',
    'equipment' => PROJECT_ROOT . '/public_html/equipment.php',
    'moon' => PROJECT_ROOT . '/public_html/moon.php',
    'community' => PROJECT_ROOT . '/public_html/community.php',
    'catalogue' => PROJECT_ROOT . '/public_html/catalogue/index.php',
    'bookings' => PROJECT_ROOT . '/public_html/bookings.php',
    'lost_found' => PROJECT_ROOT . '/public_html/lost-and-found/lost-and-found.php',
    'login' => PROJECT_ROOT . '/public_html/auth/login.php',
    'signup' => PROJECT_ROOT . '/public_html/auth/signup.php',
    'logout' => PROJECT_ROOT . '/public_html/auth/logout.php',
];

// API paths
const API_ENDPOINTS = [
    'trails' => PROJECT_ROOT . '/api/getTrails.php',
    'search' => PROJECT_ROOT . '/api/search.php',
    'booking' => PROJECT_ROOT . '/api/booking.php',
    'sites' => PROJECT_ROOT . '/api/sites.php',
    'posts' => PROJECT_ROOT . '/api/posts.php',
];

// Asset paths
const ASSET_PATHS = [
    'css' => PROJECT_ROOT . '/css',
    'js' => PROJECT_ROOT . '/js',
    'images' => PROJECT_ROOT . '/assets/Images',
    'fonts' => PROJECT_ROOT . '/assets/Fonts',
];

?>
```

---

## Migration Cheat Sheet

### How to Update Any File

**Step 1: Identify what type of include it is**

| Type           | Pattern                                                  | New Location                                                   |
| -------------- | -------------------------------------------------------- | -------------------------------------------------------------- |
| **Class**      | `require_once __DIR__ . '/../class/ClassName.php'`       | `require_once __DIR__ . '/../src/Classes/ClassName.php'`       |
| **Include**    | `include __DIR__ . '/includes/file.php'`                 | `include __DIR__ . '/../src/Includes/file.php'`                |
| **Autoloader** | `require_once __DIR__ . '/../pages/autoloader.php'`      | `require_once __DIR__ . '/../autoloader.php'`                  |
| **Redirect**   | `header('Location: /projet-web-gl21-chabiba/pages/...')` | `header('Location: /projet-web-gl21-chabiba/public_html/...')` |

**Step 2: Apply the substitution**

- Find all instances of the pattern
- Replace systematically

**Step 3: Test in browser**

- Try accessing the page
- Check browser console for errors
- Check 404 errors in Network tab

---

## Common Mistake Patterns to Avoid

| ❌ WRONG                                    | ✅ CORRECT                                                | Reason                                        |
| ------------------------------------------- | --------------------------------------------------------- | --------------------------------------------- |
| `include 'includes/header.php'`             | `include __DIR__ . '/../src/Includes/header.php'`         | Relative paths from wrong CWD                 |
| `include '/includes/header.php'`            | `include __DIR__ . '/../src/Includes/header.php'`         | Doesn't start from **DIR**                    |
| `include '../../../includes/header.php'`    | `include __DIR__ . '/../src/Includes/header.php'`         | Wrong number of ../; use **DIR**              |
| `href="/pages/weather.php"`                 | `href="/projet-web-gl21-chabiba/public_html/weather.php"` | Missing full path                             |
| `action="save.php"` (from API)              | `action="/projet-web-gl21-chabiba/api/save.php"`          | Relative paths won't work from outside folder |
| `require_once $baseDir . '/class/User.php'` | `require_once __DIR__ . '/../src/Classes/User.php'`       | Use **DIR** always                            |

---

## Verification Queries (Use After Updating)

```bash
# Find all remaining references to /class/
grep -r "class/" --include="*.php" .

# Find all remaining references to /pages/includes/
grep -r "pages/includes" --include="*.php" .

# Find all references to /pages/ (should only be in old/backup)
grep -r '"/pages/' --include="*.php" public_html/ src/

# Find unquoted paths (might indicate missing updates)
grep -r "require.*class\\\\" --include="*.php" .
```

---

**Document Version**: 1.0 | **Status**: Reference for Phase Execution
