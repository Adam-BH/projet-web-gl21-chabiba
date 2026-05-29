# Weather + Moon pages — work summary

## What was asked

- Build the **weather** and **moon** pages exactly like the Figma screenshots.
- Use only **HTML / CSS / Bootstrap / JS / PHP** (no Symfony for now).
- 2nd-year-student level — no over-engineered code.
- Real working **7-day weather forecast**.
- Imagined full **moon page** showing a 7-day lunar cycle, with realistic
  phase rendering and better phase names than basic "crescent".
- No scroll, stars background, follow Figma fonts, theme toggle bottom-right.
- **Don't change existing code** — only add new files.

## What I built

### New files only — nothing existing was modified

```
class/
  WeatherService.php       7-day forecast from Open-Meteo (no API key needed)
  MoonService.php          Moon phase math (synodic month, 8 named phases)

pages/
  weather.php              Weather page (replaces empty weather.html stub)
  moon.php                 Moon page (replaces empty moon.html stub)

css/
  shared/
    hiki-page.css          Shared chrome: bg, fonts, nav, stars canvas, toggle
  pages/
    weather-page.css       Weather page layout (title, yellow card, pills,
                           search bar)
    moon-page.css          Moon page layout + the CSS-only moon disc renderer

js/
  stars-bg.js              Twinkling star field (extracted from main.js so
                           sub-pages don't pull in the home-page Sparky code)
  moon-page.js             Reads each moon disc's data-* attributes and
                           applies them as CSS custom properties on the
                           ellipse element

SUMMARY.md                 This file
```

The old `pages/weather.html`, `pages/moon.html`, `index.php`, `style.css`,
`main.js`, etc. were **not touched**.

## Where to view the result

```
# from project root
php -S 127.0.0.1:8000

# then open:
http://127.0.0.1:8000/pages/weather.php
http://127.0.0.1:8000/pages/moon.php
```

The weather page accepts `?city=Sousse` (or any city name) to switch
locations, and `?day=FRI` (or another short day) to pick a forecast day.
The moon page accepts `?d=0..6` to select which day of the week is featured.

## Weather page — how it works

1. `WeatherService::getForecast($city)` does two HTTPS calls to Open-Meteo:
   - Geocoding lookup → lat/lon for the city name.
   - Daily forecast → 7 days of `weather_code`, max/min temp, precipitation
     probability, max wind speed.
2. WMO weather codes are mapped to human labels ("Light rain", "Thunderstorm",
   …) and to one of the icon PNGs in `assets/Images/`.
3. If the API call fails (offline, blocked, etc.) the service returns a
   fallback 7-day array so the layout still renders.
4. The view picks the selected day (default = today) and renders:
   - The big yellow card with temperature, country, city, wind.
   - The 7-day pill strip on the right (clicking a pill becomes a GET that
     re-renders with that day selected).
   - The search form posts via GET to `weather.php?city=…`.

The 3D moon graphic in the yellow card uses your
`assets/Images/night-moon-component.png` and overlaps the card edge exactly
like the Figma.

## Moon page — how it works

`MoonService` computes the moon's age in days from a reference new moon
(JD 2451550.1, 2000-01-06 18:14 UTC) using the synodic month
(29.530588853 days). For each day it returns:

- `name` — one of the 8 proper astronomical phase names
- `illumination` — % illuminated (0 → 100)
- `age_days`, `waxing`, `shadow_side`, `phase_type`, `ellipse_scale`,
  `ellipse_fill`, `visibility` (a short observing hint)

### The 8 phase names used (better than "tiny / small / big crescent")

```
New Moon → Waxing Crescent → First Quarter → Waxing Gibbous
       → Full Moon → Waning Gibbous → Last Quarter → Waning Crescent
```

These are the astronomical names every astronomy app uses.

### How the moon disc is rendered (the "realistic crescent" part)

Pure CSS + 3 stacked layers, no images, no SVG:

1. A circular bright disc with a subtle radial gradient + inner shadow so it
   looks 3D.
2. A flat half-rectangle covering the shadowed side of the disc.
3. An ellipse, scaled horizontally based on illumination, that draws the
   curved terminator:
   - For a **crescent** (< 50% illuminated): the ellipse is **shadow-coloured**
     and sits on the **bright** side, scaling inward as illumination grows.
     This eats a curved bite into the bright half.
   - For a **gibbous** (> 50% illuminated): the ellipse is **bright-coloured**
     and sits on the **shadow** side, scaling outward as illumination grows.
     This restores light up to the curved terminator.

That single ellipse + half-rectangle combo produces every phase shape
correctly, with the curve narrowing as you approach New or Full and widening
as you approach First/Last Quarter.

The per-disc values (which side, how much, which colour) are set as CSS
custom properties via `js/moon-page.js` — markup uses `data-*` attributes so
**no inline `style="…"` strings exist in the HTML source**.

## Design fidelity vs the Figma

Matched:

- Dark navy background `#0b001b` (taken from Figma's frame background colour).
- Stars canvas in the background (reused the home-page star pattern).
- Righteous font for big titles and `20°` temperature, Nunito for body.
- Layout grid: HIKI brand left, nav links right, active link in yellow
  (`#fcb415`).
- Yellow forecast card on the left with the cute 3D moon overflowing.
- 7 dark purple rounded pills on the right; **active day painted yellow**
  with shadow.
- Glassy white search input with a yellow circular search button.
- Theme toggle pill fixed to the bottom-right.
- Designed to fit a 1280 × 832 viewport with no scroll. A `@media
  (max-height: 760px)` block tightens spacing on shorter screens.

Diverged (with intent):

- The Figma weather pill labelled **WEBS** is a typo for **WED**. I kept the
  real abbreviation because the forecast is real data and Wednesday's short
  form is `WED`.
- The Figma moon page is empty — I filled it with a featured-phase card +
  7-day strip in the same grid rhythm as the weather page so the two pages
  feel like a set.

## Honoring the constraints

| Rule | How it's honored |
|---|---|
| HTML / CSS / Bootstrap / JS / PHP only | Yes. No Symfony, no other framework. |
| 2nd-year-student level | Plain classes, straight PHP, no DI, no abstractions. |
| No inline CSS | All visual rules live in `.css` files. Dynamic per-instance values are passed via `data-*` attributes and applied by JS as CSS custom properties. |
| Modular, clean | Concerns split: services → `class/`, views → `pages/`, styling → `css/{shared,pages}/`, behaviour → `js/`. |
| Don't change existing code | Nothing existing was edited. All new code is in new files. The old `.html` stubs in `pages/` are still there, untouched. |
| Stars in the background | `js/stars-bg.js` runs on both pages. |
| No scroll | `.hiki-page { overflow: hidden; }` + tight grid sizing. |
| Each UI control works | Forecast pills navigate (GET), search submits (GET), moon pills navigate (GET). |
| Better moon phase names | 8 proper astronomical names. |
| Realistic crescent shape | Half-rectangle + scaled ellipse renders true crescents and gibbous shapes. |
| Right fonts | Righteous for titles, Nunito for body — loaded from `assets/Fonts/`. |
| Coherent with the rest of the project | Reused the project's nav structure, colour palette, theme toggle, and stars effect from the home page. |
| Weather actually works | Open-Meteo (free, no API key) — verified live: returned current Tunis temperatures and 7-day rain probabilities during testing. |

## A note on `index.php` — not changed

`index.php:13` references `css/node_modules/bootstrap/dist/css/bootstrap.min.css`,
which is the wrong path now that npm installed bootstrap at
`node_modules/bootstrap/…` (no `css/` prefix). I did **not** change it —
that's existing code. My new pages use the correct path. If you ever want to
fix the home page's bootstrap link, change that one line; otherwise no
action needed.

## How to run

```bash
# install dependencies (already done)
npm install

# start PHP dev server from the project root
php -S 127.0.0.1:8000

# visit
http://127.0.0.1:8000/pages/weather.php
http://127.0.0.1:8000/pages/moon.php
```

No `CHANGES.md` was needed — zero existing files were modified.
