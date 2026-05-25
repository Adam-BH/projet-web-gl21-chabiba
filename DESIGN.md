# HIKI Design System

## Design Tokens (Single Source of Truth)

All CSS custom properties are defined in `css/site-shell.css` `:root`. Page CSS files MUST NOT redeclare these variables. Add new tokens only to `site-shell.css`.

### Colors
| Token | Value | Usage |
|-------|-------|-------|
| `--bg-night` | `#0b001b` | Page background base |
| `--bg-night-soft` | `#14062d` | Gradient endpoint |
| `--text-primary` | `#f9f9f9` | Main text |
| `--text-muted` | `#d7d2e8` | Secondary text |
| `--accent-warm` | `#c1754b` | Warm accent (CTA gradient) |
| `--accent-cool` | `#9de7f3` | Cool accent (links, highlights) |
| `--surface-border` | `rgba(255,255,255,0.16)` | Card/panel borders |
| `--surface-bg` | `rgba(12,8,22,0.7)` | Card/panel background |
| `--color-white` | `#f9f9f9` | Alias for text-primary |

### Buttons
| Token | Value | Usage |
|-------|-------|-------|
| `--button-bg` | `rgba(255,255,255,0.05)` | Default button bg |
| `--button-border` | `rgba(255,255,255,0.16)` | Default button border |
| `--button-hover` | `rgba(143,211,255,0.12)` | Button hover bg |
| `--button-accent` | `linear-gradient(135deg, #8fd3ff, #ffa86d)` | Primary button gradient |
| `--button-accent-hover` | `linear-gradient(135deg, #a1e0ff, #ffbc87)` | Primary button hover |

### Typography
| Token | Value | Usage |
|-------|-------|-------|
| Font (body) | `'Rethink Sans', sans-serif` | Set on `body` in site-shell.css |
| Font (display) | `'Righteous', sans-serif` | Headings, titles |
| Font (ui) | `'Nunito', sans-serif` | Only on pages that explicitly set `hiki-page` body class |

### Spacing
| Token | Value | Usage |
|-------|-------|-------|
| `--space-xs` | `6px` | Tight spacing |
| `--space-sm` | `12px` | Small gaps |
| `--space-md` | `20px` | Default section spacing |
| `--space-lg` | `32px` | Large section spacing |

---

## Component Standards

### Buttons
Use these classes. Do NOT create custom button classes per page.

| Class | Usage | Style |
|-------|-------|-------|
| `.btn` | Base button | Pill shape, 44px min-height, glass border |
| `.btn-primary` | Primary action | Gradient background, dark text |
| `.btn-secondary` | Secondary action | Transparent bg, light border |
| `.btn-danger` | Destructive action | Red border, red text |
| `.nav-btn.primary` | Navbar CTA | Warm gradient (orange) |
| `.nav-btn.outline` | Navbar secondary | Transparent, light border |

**Rule:** Every button in the project MUST use one of the above classes. Page-specific button styles (`.btn-login`, `.glowy-btn`) should be exceptions only for unique hero/CTA elements, and must be defined in the page CSS file (not inline).

### Form Inputs
Use these classes. Do NOT use inline styles on form elements.

| Class | Usage |
|-------|-------|
| `.form-input` | Standard text/select input (dark bg, light border, focus ring) |
| `.form-label` | Label above input (uppercase, small, accent color) |
| `.form-group` | Wrapper for label + input pair |
| `.form-row` | Horizontal layout for multiple form groups |

**Note:** The `.glass-input` class is deprecated. Use `.form-input` instead (defined in site-shell.css).

### Cards & Panels
| Class | Usage |
|-------|-------|
| `.content-card` | Generic content panel (border, glass bg, blur) |
| `.panel` | Alias for content-card |
| `.site-card` | Camping site card (catalogue) |
| `.booking-card` | Booking card (bookings page) |
| `.post-card` | Lost & found post card |

**Rule:** All cards use `border-radius: 18px`, `background: var(--surface-bg)`, `border: 1px solid var(--surface-border)`, `backdrop-filter: blur(12px)`.

### Page Layout
| Class | Usage |
|-------|-------|
| `.page-shell` | Outer wrapper (opened by header.php, closed by each page) |
| `.content-page` | Standard page content wrapper (max-width: 1180px, centered) |

---

## Page Structure Rules

### Header/Footer Includes
- **Header:** Always use `src/Includes/header.php`. Pass `$pageTitle`, `$pageActive`, `$extraStyles`.
- **Footer:** There is no shared footer include. Each page closes with:
  ```php
  </div><!-- .page-shell -->
  </body>
  </html>
  ```
- **Theme toggle:** Place BEFORE the closing `</div>` (inside `.page-shell`), NOT inside a `<footer>`.

### CSS Loading
1. `header.php` loads Bootstrap + `site-shell.css` on every page.
2. Page-specific CSS goes in `$extraStyles` array.
3. Page CSS files SHOULD `@import url('../style.css')` only if they need the home page campfire animation. Most pages should NOT import style.css.

### Inline Styles
**ZERO INLINE STYLES.** All layout, spacing, colors, and typography go in CSS files. If a style is only used on one page, add it to that page's CSS file.

### Closing Tags
Every PHP page MUST close the `.page-shell` div opened in header.php:
```html
</div>
</body>
</html>
```

---

## File Naming
- Page CSS: `css/pages/<pagename>.css`
- Shared CSS: `css/shared/<name>.css`
- Design tokens + base: `css/site-shell.css`
- Home page extras: `css/style.css` (campfire animation only)

---

## Pages Audit (Issues Found & Fixed)

| Page | Issues | Status |
|------|--------|--------|
| `index.php` | Theme toggle in `<footer>` instead of standard placement | Fixed |
| `weather.php` | Uses `hiki-page.css` variables instead of site-shell tokens | Fixed |
| `moon.php` | Uses `hiki-page.css` variables instead of site-shell tokens | Fixed |
| `hiking-guide.php` | Inline styles, `.button.primary` instead of `.btn-primary` | Fixed |
| `hiking-guide.css` | Missing closing `}` on `:root` block | Fixed |
| `equipment.php` | OK - uses equipment.css properly | Clean |
| `bookings.php` | Missing closing `</div>` for page-shell | Fixed |
| `catalogue/index.php` | Heavy inline styles on controls | Fixed |
| `catalogue/details.php` | Heavy inline styles on entire layout | Fixed |
| `search-engine/index.php` | Mixed Bootstrap + custom classes, inline styles | Fixed |
| `lost-and-found/lost-and-found.php` | Inline font-size on h1 | Fixed |
| `lost-and-found/add_post.php` | Inline margins on form groups | Fixed |
| `auth/login.php` | OK | Clean |
| `auth/signup.php` | XSS vulnerability, inline error style | Fixed |
| `style.css` | Duplicates `:root` from site-shell.css, duplicate @font-face | Fixed |
| `hiki-page.css` | Duplicate `:root`, duplicate @font-face | Fixed |
| `auth.css` | Duplicate @font-face | Fixed |
