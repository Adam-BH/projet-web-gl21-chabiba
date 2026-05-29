# Symfony Migration Plan — for the next Claude Opus 4.7 session

> **Read this entire file before running any command.**
> You are picking up a half-finished migration from raw PHP to Symfony 7.
> The user is a 2nd-year student. They know PHP, basic Symfony, HTML, CSS,
> JS. They do **not** know advanced DI, doctrine internals, or production
> deployment. Write code that a 2nd-year student can read and defend.

---

## Project context (current state of the repo)

Location: `c:\Users\akrem\OneDrive\Desktop\projet web`

```
├── api/                       AJAX endpoints (book, getTrails, search, sites)
├── assets/                    Fonts + Images
├── class/                     Loose PHP classes (DEPRECATED — see src/Classes)
│   └── cache/weather/         Open-Meteo file cache
├── config/paths.php           Constants for path resolution
├── css/                       base/ layout/ pages/ shared/
├── database/                  init.sql + DB.md (schema source of truth)
├── js/                        api/ data/ features/ pages/
├── node_modules/bootstrap     Installed via npm
├── public_html/               Entry pages
│   ├── auth/                  login, logout, signup, processLogin, processSignUp
│   ├── catalogue/             availability, book, details, gridItem, index, listItem
│   ├── lost-and-found/        lost-and-found, add_post, delete_post, itemlist, save_post
│   ├── manual/manual/         fire.html, hiking.html, tent.html (static)
│   ├── search-engine/index.php
│   ├── bookings.php community.php equipment.php hiking-guide.php
│   ├── index.php              Home
│   ├── moon.php               Moon page
│   └── weather.php            Weather page
├── refactor/                  Prior refactor docs (DO NOT confuse with this plan)
├── src/Classes/               PSR-4 partial migration target
│   ├── AddressRepository.php
│   ├── BookingRepository.php
│   ├── CampingSite.php
│   ├── CampingSiteRepository.php
│   ├── ConnexionDB.php        PDO singleton (host=localhost db=hiki user=root)
│   ├── IRepository.php
│   ├── MoonService.php        7-day phase math (port verbatim)
│   ├── PostRepository.php
│   ├── Repository.php
│   ├── TrailRepository.php
│   ├── UserRepository.php
│   └── WeatherService.php     Open-Meteo client (port verbatim)
├── autoloader.php             Glob-based class autoloader
├── index.php                  Root-level home (legacy)
└── database/init.sql          Authoritative schema
```

**Database tables** (from `database/init.sql`):

| Table | Columns | Notes |
|---|---|---|
| `adresses` | id (VARCHAR PK), lat (DOUBLE), lon (DOUBLE) | City directory |
| `users` | id (VARCHAR PK), username (UNIQUE), password (bcrypt), phone, created_at, city (FK adresses.id ON DELETE SET NULL) | id is the email per current login code |
| `posts` | id (INT AI PK), finder (FK users.id CASCADE), item, created_at, place, phone, picture | Lost & found |
| `camping_sites` | id (INT AI PK), name, description, capacity, city, lat, lon, image, created_at | Catalogue |
| `camping_site_images` | id (INT AI PK), site_id (FK camping_sites.id CASCADE), path, sort_order | Photo gallery |
| `bookings` | id (INT AI PK), site_id (FK camping_sites.id CASCADE), user_id (FK users.id CASCADE), start_date, end_date, people, created_at | Reservations |

**Credentials** (per `database/DB.md`):
- host `localhost`, db `hiki`, user `root`, password `golden ratio 1.618`.

**Current login flow** (`public_html/auth/processLogin.php`): the form's
`email` field is actually `users.id` (id stores the email string). Password
is verified with `password_verify`.

---

## Target architecture (Symfony 7.1 with webapp pack)

```
hiki-symfony/                  ← new sibling folder, NOT inside projet web
├── assets/                    (AssetMapper) CSS + JS + images
├── bin/console
├── config/
│   ├── packages/
│   ├── routes.yaml
│   └── services.yaml
├── migrations/                Doctrine migrations
├── public/
│   └── images/                Static images (or moved into assets/)
├── src/
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── WeatherController.php
│   │   ├── MoonController.php
│   │   ├── AuthController.php
│   │   ├── LostFoundController.php
│   │   ├── CatalogueController.php
│   │   ├── BookingController.php
│   │   ├── CommunityController.php
│   │   ├── EquipmentController.php
│   │   ├── HikingGuideController.php
│   │   ├── ManualController.php
│   │   └── Api/
│   │       ├── TrailController.php
│   │       ├── SearchController.php
│   │       └── SiteController.php
│   ├── Entity/
│   │   ├── Address.php
│   │   ├── User.php           implements UserInterface, PasswordAuthenticatedUserInterface
│   │   ├── Post.php
│   │   ├── CampingSite.php
│   │   ├── CampingSiteImage.php
│   │   └── Booking.php
│   ├── Repository/            Doctrine repositories
│   ├── Form/
│   │   ├── RegistrationFormType.php
│   │   ├── PostType.php       (lost & found)
│   │   └── BookingType.php
│   ├── Security/
│   │   └── LoginFormAuthenticator.php   (or use form_login)
│   └── Service/
│       ├── WeatherService.php
│       └── MoonService.php
├── templates/
│   ├── base.html.twig
│   ├── _partials/
│   │   ├── _nav.html.twig
│   │   ├── _stars.html.twig
│   │   └── _toggle.html.twig
│   ├── home/index.html.twig
│   ├── weather/index.html.twig
│   ├── moon/index.html.twig
│   ├── auth/{login,signup}.html.twig
│   ├── lost_found/{index,new,show}.html.twig
│   ├── catalogue/{index,details}.html.twig
│   ├── bookings/{index,new}.html.twig
│   ├── community/index.html.twig
│   ├── equipment/index.html.twig
│   ├── hiking_guide/index.html.twig
│   └── manual/{fire,hiking,tent}.html.twig
├── .env                       APP_ENV=dev, DATABASE_URL
├── composer.json
└── symfony.lock
```

---

## Ground rules

1. **Do NOT touch the existing `projet web/` files in place.** The new
   project lives in a **sibling folder** so the old code stays usable as a
   reference and can be diff-compared by the user.
2. **Branch strategy**: same repo, new branch `symfony-migration` created
   from `main`. The new `hiki-symfony/` folder is added under the repo root.
   The old code path stays on the branch but is removed in the final phase
   when the new app passes acceptance.
3. **One phase at a time. STOP at every verification gate.** Print the
   gate's checklist and wait for the user to confirm before starting the
   next phase. Do not chain phases.
4. **Use `make:` commands** (`make:entity`, `make:controller`,
   `make:form`, `make:registration-form`, `make:auth`) for scaffolding —
   don't hand-write what the maker generates.
5. **Run the dev server with `symfony serve -d`** so port issues from XAMPP
   are sidestepped. Stop with `symfony server:stop`.
6. **Doctrine owns the schema.** Drop and recreate. Re-import sample data
   from `database/init.sql` (the INSERT statements) via a DataFixture, not
   by hand.
7. **No `php -S` on port 8000** — port collisions on this user's machine
   (Python is squatting it). Use `symfony serve` which picks a free port.
8. **Write code at a 2nd-year-student level**: no event subscribers, no
   custom voters, no DI factories unless absolutely needed. Plain
   controllers, plain services, attributes for routes.

---

## Phase 0 — Pre-flight

**Goal**: confirm environment, take a safety net.

**Commands** (PowerShell, run from `c:\Users\akrem\OneDrive\Desktop\projet web`):

```powershell
# 1. Toolchain (already verified in prior session, re-confirm)
php --version            # expect 8.2.x
composer --version       # expect 2.x
symfony --version        # expect 5.17+
symfony check:requirements

# 2. Database backup
mysqldump -u root -p"golden ratio 1.618" hiki > hiki_backup_$(Get-Date -Format yyyyMMdd_HHmmss).sql

# 3. Git safety
git status               # must be clean OR stash changes first
git checkout main
git pull
git checkout -b symfony-migration
```

**Verification gate**:
- [ ] PHP 8.2+ confirmed.
- [ ] `hiki_backup_*.sql` exists in project root.
- [ ] On branch `symfony-migration`, clean working tree.

Ask the user: *"Verify the three boxes above before I bootstrap the Symfony skeleton."*

---

## Phase 1 — Bootstrap Symfony skeleton

**Goal**: blank Symfony 7 webapp running on `https://127.0.0.1:8000`.

**Location of new project**: `c:\Users\akrem\OneDrive\Desktop\projet web\hiki-symfony\`

**Commands**:

```powershell
# From projet web/
symfony new hiki-symfony --version="7.1.*" --webapp
cd hiki-symfony

# Confirm it boots
symfony serve -d
symfony server:status
# Open the printed https://127.0.0.1:XXXX URL — should see Symfony welcome page
```

**`.env` adjustments** (`hiki-symfony/.env`):

```
APP_ENV=dev
APP_SECRET=<keep the generated one>
DATABASE_URL="mysql://root:golden%20ratio%201.618@127.0.0.1:3306/hiki_symfony?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

Note: URL-encode the space in the password as `%20`. Use a **separate
database name `hiki_symfony`** so the old `hiki` DB stays intact as a
fallback.

**Verification gate**:
- [ ] `symfony serve` running, welcome page loads.
- [ ] `.env` shows `hiki_symfony` and URL-encoded password.

---

## Phase 2 — Database + Doctrine entities

**Goal**: 6 entities matching `database/init.sql`, schema generated, sample data fixtures.

**Commands**:

```powershell
# From hiki-symfony/
php bin/console doctrine:database:create
```

**Entities to create with `make:entity`** (run each, answer prompts):

### `Address`
- table: `adresses`
- properties:
  - `id` string(50) PK, NOT auto-generated (user-supplied) — set in constructor
  - `lat` float NULL
  - `lon` float NULL
- inverse side of `User.address` (OneToMany), `CampingSite.address` if you wire it

### `User`
- table: `users`
- implements `UserInterface`, `PasswordAuthenticatedUserInterface`
- properties:
  - `id` string(50) PK, not auto-generated (current code uses email as id; keep that for compatibility)
  - `username` string(50) UNIQUE NOT NULL
  - `password` string(255) NOT NULL
  - `phone` string(15) NOT NULL
  - `createdAt` `\DateTimeImmutable` (column name `created_at`)
  - `address` ManyToOne(Address) nullable, ON DELETE SET NULL (column name `city`)
  - `roles` array (Symfony requirement, default `['ROLE_USER']`)
- methods: `getUserIdentifier()` returns `id`, `getRoles()`, `getPassword()`, `eraseCredentials()`

### `Post` (lost & found)
- table: `posts`
- properties:
  - `id` int auto PK
  - `finder` ManyToOne(User) NOT NULL, ON DELETE CASCADE (column `finder`)
  - `item` string(100) NULL
  - `createdAt` immutable
  - `place` string(50) NULL
  - `phone` string(15) NOT NULL
  - `picture` string(255) NULL

### `CampingSite`
- table: `camping_sites`
- properties:
  - `id` int auto PK
  - `name` string(150) NOT NULL
  - `description` text NULL
  - `capacity` int default 0
  - `city` string(100) NULL
  - `lat` float NULL
  - `lon` float NULL
  - `image` string(255) NULL
  - `createdAt` immutable
- relations:
  - OneToMany `images` → CampingSiteImage
  - OneToMany `bookings` → Booking

### `CampingSiteImage`
- table: `camping_site_images`
- properties:
  - `id` int auto PK
  - `site` ManyToOne(CampingSite) NOT NULL, ON DELETE CASCADE, column `site_id`
  - `path` string(255) NOT NULL
  - `sortOrder` int default 0, column `sort_order`

### `Booking`
- table: `bookings`
- properties:
  - `id` int auto PK
  - `site` ManyToOne(CampingSite) NOT NULL, ON DELETE CASCADE, column `site_id`
  - `user` ManyToOne(User) NOT NULL, ON DELETE CASCADE, column `user_id`
  - `startDate` `\DateTimeImmutable`, column `start_date`
  - `endDate` `\DateTimeImmutable`, column `end_date`
  - `people` int default 1
  - `createdAt` immutable

**After all entities exist**:

```powershell
php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
```

**Data fixtures** — port the INSERT statements from `database/init.sql` (6
camping sites + their images):

```powershell
composer require --dev orm-fixtures
php bin/console make:fixtures CampingSiteFixtures
# Hand-edit src/DataFixtures/CampingSiteFixtures.php to insert the 6 sites
# and their seed images. DO NOT seed users/bookings/posts — leave the DB
# empty so the user can register their own accounts.
php bin/console doctrine:fixtures:load --no-interaction
```

**Verification gate**:
- [ ] `php bin/console doctrine:schema:validate` reports both schema and mapping in sync.
- [ ] `php bin/console dbal:run-sql "SHOW TABLES FROM hiki_symfony"` lists all 6 tables.
- [ ] `... "SELECT COUNT(*) FROM camping_sites"` returns 6.
- [ ] User can run `symfony console doctrine:fixtures:load` again without errors (idempotent).

---

## Phase 3 — Asset pipeline + base layout

**Goal**: a `base.html.twig` that reproduces the current `hiki-page.css` shell (dark bg, stars canvas, nav, theme toggle) using AssetMapper.

**Steps**:

1. **Copy assets** from old project into new:
   ```powershell
   # From hiki-symfony/
   Copy-Item -Recurse "../assets/Fonts"  "assets/fonts"
   Copy-Item -Recurse "../assets/Images" "assets/images"
   Copy-Item -Recurse "../css"           "assets/styles"
   Copy-Item -Recurse "../js"            "assets/javascript"
   ```

   AssetMapper convention: everything under `assets/` is exposed via
   `{{ asset('...') }}` and versioned automatically.

2. **Register Bootstrap** (already in node_modules of old project, but
   re-add cleanly):
   ```powershell
   php bin/console importmap:require bootstrap
   ```

3. **`templates/base.html.twig`** — the shell every page extends. Includes:
   - `<canvas id="starCanvas" class="star-canvas">` for stars
   - `_nav.html.twig` partial with the 6 nav links + brand
   - `{% block body %}{% endblock %}`
   - `_toggle.html.twig` partial (theme toggle, fixed bottom-right)
   - `{{ importmap('app') }}` to load bootstrap + stars-bg.js

4. **`assets/app.js`** — entrypoint that imports bootstrap CSS/JS and
   `./javascript/stars-bg.js`.

5. **Port the shell CSS** (`css/shared/hiki-page.css`) verbatim into
   `assets/styles/shared/hiki-page.css`. Reference from `base.html.twig`
   via `{{ asset('styles/shared/hiki-page.css') }}`.

**Acceptance**: hitting `https://127.0.0.1:8000/` (after the home
controller exists in next phase) shows the dark bg with stars and nav.

**Verification gate**:
- [ ] `symfony console asset-map:compile` runs without errors.
- [ ] Browser DevTools network tab shows fonts loading from `/assets/fonts/...`.
- [ ] No 404s on CSS/JS in dev tools.

---

## Phase 4 — Stateless services port

**Goal**: drop in `WeatherService` and `MoonService` as Symfony services.

**Steps**:

1. **Copy** `src/Classes/WeatherService.php` → `hiki-symfony/src/Service/WeatherService.php`.
   - Change namespace to `App\Service`.
   - Replace `file_get_contents` with Symfony's `HttpClientInterface`
     (constructor-injected). This gives free retries, timeouts, and is
     mockable in tests.
   - Replace the file cache with Symfony's `CacheInterface` (PSR-6).
     Same 1-hour TTL. Drop the `class/cache/weather/` folder concept.
   - Keep the WMO code → icon mapping identical (current mapping points
     at `moon-cloud-fast-wind.png`, `sun-cloud-mid-rain.png`,
     `sun-cloud-angled-rain.png` — those files exist in `assets/images`).
2. **Copy** `src/Classes/MoonService.php` → `hiki-symfony/src/Service/MoonService.php`.
   - Change namespace, otherwise verbatim — it's pure math, no IO.
3. **Autowiring**: Symfony auto-registers services under `src/`, no
   `services.yaml` edit needed.

**Verification gate**:
- [ ] `symfony console debug:autowiring Weather` shows `App\Service\WeatherService`.
- [ ] `symfony console debug:autowiring Moon` shows `App\Service\MoonService`.
- [ ] `php -r "require 'vendor/autoload.php'; ..."` smoke test (or wait for the controller in Phase 5).

---

## Phase 5 — Public pages (home, weather, moon)

**Goal**: these three routes render the same UI the user has in
`public_html/{index,weather,moon}.php`.

### Routes

```
GET  /              HomeController::index
GET  /weather       WeatherController::index    (?city=, ?day=)
GET  /moon          MoonController::index       (?d=0..6)
```

### Controllers

```powershell
php bin/console make:controller HomeController
php bin/console make:controller WeatherController
php bin/console make:controller MoonController
```

Each controller injects the relevant service and passes data to the Twig
template. **Do not put any logic in Twig.**

### Templates

- `templates/home/index.html.twig` — port `public_html/index.php` minus
  the PHP includes; use `{% include '_partials/_nav.html.twig' %}`.
- `templates/weather/index.html.twig` — port `public_html/weather.php`
  layout. The 7-day pill strip uses `{% for day in forecast.days %}`.
  Forms post to the same route as `?city=`.
- `templates/moon/index.html.twig` — port `public_html/moon.php` with the
  CSS-only moon disc rendering. Reuse the `data-ellipse-scale` /
  `data-ellipse-fill` attributes; `assets/javascript/moon-page.js` reads
  them.

### JS

- Copy `js/weather-page.js` (day-pill click → no reload + URL update) into
  `assets/javascript/weather-page.js`. Load it via importmap in the
  weather template only:
  ```twig
  {% block javascripts %}
      {{ parent() }}
      <script type="module" src="{{ asset('javascript/weather-page.js') }}"></script>
  {% endblock %}
  ```

**Verification gate**:
- [ ] `/weather?city=Tunis` renders, shows 7 day pills, real temperatures.
- [ ] Clicking a day pill changes the card without a page reload (network
      tab: no document request).
- [ ] `/moon` shows the featured phase + 7-day strip with curved
      terminators (not flat half-discs).

---

## Phase 6 — Security: login + register

**Goal**: form-based auth using the existing `users` table. The form's
"email" field maps to `User.id` (not a separate email column), matching
the legacy login flow.

**Steps**:

```powershell
php bin/console make:user            # answer: yes use existing, class User
                                     # the maker will detect the existing entity
php bin/console make:registration-form
php bin/console make:auth            # choose: Login form authenticator
```

**Hand-edits required after the makers**:

1. `config/packages/security.yaml`:
   - Set `password_hashers.App\Entity\User: 'auto'`.
   - The `form_login` provider's `username_path` to `email` and configure
     `User::getUserIdentifier()` to return `$this->id`.
   - Add access control: `/lost-and-found/new` and `/bookings/new` require
     `ROLE_USER`. Public: `/`, `/weather`, `/moon`, `/catalogue`,
     `/auth/login`, `/auth/signup`.

2. `RegistrationFormType.php`: the form should collect
   `id` (email), `username`, `phone`, `password` (RepeatedType),
   `city` (EntityType on Address, autocomplete optional). Add a
   `NotCompromisedPassword` constraint.

3. `RegistrationController::register` — when saving:
   - hash password with `UserPasswordHasherInterface`
   - persist
   - auto-login via `UserAuthenticatorInterface::authenticateUser`
   - redirect to `/`

4. Login template at `templates/auth/login.html.twig` — port the layout
   from `public_html/auth/login.php`. The form field is `email` (which
   becomes `_username` under the hood — set `username_path: email` in
   `security.yaml`).

5. Logout: configure `logout.path: app_logout` and create the route.

**Verification gate**:
- [ ] Register a new account at `/auth/signup` → redirected to `/` logged in.
- [ ] Log out → redirected to `/`.
- [ ] Log back in at `/auth/login` with the same credentials.
- [ ] `dbal:run-sql "SELECT id, username, LEFT(password, 7) FROM users"` shows the password starts with `$2y$` (bcrypt) or `$argon` (argon2id).

---

## Phase 7 — Lost & Found CRUD

**Goal**: replace `public_html/lost-and-found/*.php` with a Symfony CRUD.

**Routes**:
```
GET  /lost-and-found              list      → LostFoundController::index
GET  /lost-and-found/new          form      → ::new       (ROLE_USER)
POST /lost-and-found/new          submit    → ::new       (ROLE_USER)
GET  /lost-and-found/{id}         show      → ::show
POST /lost-and-found/{id}/delete  delete    → ::delete    (ROLE_USER, must own)
```

**Steps**:

1. `php bin/console make:crud Post` — generates controller, form, templates.
2. Trim the maker output to **only** the routes above (remove edit;
   delete must verify `$post->getFinder() === $this->getUser()`).
3. Picture upload:
   - Add `picture` field as `FileType` in `PostType` (not mapped to entity).
   - Store under `public/uploads/posts/`, keep filename via
     `Uid\UuidV4`.
   - Save the public path string to `Post.picture`.
4. Port the existing CSS (`assets/styles/pages/lost-and-found.css`) — no
   changes needed.

**Verification gate**:
- [ ] List view shows existing seed posts (none — empty state should render).
- [ ] Posting a new lost item with a photo succeeds; file appears under `public/uploads/posts/`.
- [ ] Anonymous user cannot reach `/lost-and-found/new` (redirected to login).
- [ ] Logged-in user A cannot delete user B's post (403).

---

## Phase 8 — Catalogue + bookings

**Goal**: replace `public_html/catalogue/*.php` and `public_html/bookings.php`.

**Routes**:
```
GET  /catalogue                 grid+filters     → CatalogueController::index
GET  /catalogue/{id}            details          → ::show
GET  /catalogue/{id}/book       booking form     → BookingController::new  (ROLE_USER)
POST /catalogue/{id}/book       submit booking   → ::new                   (ROLE_USER)
GET  /bookings                  my bookings      → BookingController::mine (ROLE_USER)
POST /bookings/{id}/cancel      cancel           → ::cancel                (ROLE_USER, must own)
```

**Steps**:

1. `make:controller CatalogueController` + `BookingController`.
2. `make:form BookingType` with `startDate`, `endDate`, `people`.
3. Availability check (port `api/book.php` logic): a booking is allowed
   if no existing booking on the same site overlaps the requested range.
   Implement as `BookingRepository::isAvailable(CampingSite, start, end)`.
4. On submit: validate dates (start ≥ today, end > start), call
   `isAvailable`, persist, flash message, redirect to `/bookings`.
5. Port existing CSS (`pages/catalogue.css`, `pages/bookings.css`) as-is.

**Verification gate**:
- [ ] `/catalogue` shows 6 seed sites with images.
- [ ] `/catalogue/1` shows Lake View Camp details.
- [ ] Logged-in user can book a future date range.
- [ ] Same user cannot double-book overlapping dates.
- [ ] `/bookings` shows the user's bookings only.

---

## Phase 9 — JSON API endpoints

**Goal**: replace `api/*.php` with controllers returning `JsonResponse`.

**Routes**:
```
GET /api/trails                  → Api/TrailController::list
GET /api/sites                   → Api/SiteController::list      ?city=&capacity_min=
GET /api/sites/{id}              → Api/SiteController::show
GET /api/search?q=               → Api/SearchController::query
```

**Steps**:

1. `make:controller Api/TrailController` (and the other two).
2. Annotate each method with `#[Route('/api/...', methods: ['GET'])]`.
3. Return `$this->json($data)`.
4. For pagination, accept `?page=` and `?limit=` (default 1, 20).

**Verification gate**:
- [ ] `curl https://127.0.0.1:8000/api/sites | jq '.[].name'` lists 6 names.
- [ ] `curl https://127.0.0.1:8000/api/sites/1` returns Lake View Camp JSON.
- [ ] `?city=Lakecity` filters.

---

## Phase 10 — Remaining pages

**Pages to port** (mostly static or simple):

| Old | New route | Template | Notes |
|---|---|---|---|
| `public_html/community.php` | `/community` | `community/index.html.twig` | Static for now |
| `public_html/equipment.php` | `/equipment` | `equipment/index.html.twig` | Reads `js/data/equipment.data.js` — consider porting that data into a `EquipmentService` returning an array |
| `public_html/hiking-guide.php` | `/hiking-guide` | `hiking_guide/index.html.twig` | Static |
| `public_html/manual/manual/{fire,hiking,tent}.html` | `/manual/{fire,hiking,tent}` | `manual/fire.html.twig` etc. | Static |
| `public_html/search-engine/index.php` | `/search` | `search/index.html.twig` | Front-end calls `/api/search` |

One controller for the static set is fine: `ManualController` with three
actions, `CommunityController`, `EquipmentController`, `HikingGuideController`,
`SearchController`.

**Verification gate**: each route returns 200 and shows the ported layout.

---

## Phase 11 — Cleanup, polish, README

**Steps**:

1. **Move `hiki-symfony/` contents to repo root** OR leave it as a
   subfolder — ask the user. Recommended: move to root in a final commit
   so `composer install && symfony serve` works at repo root, matching
   Symfony deployment conventions.
2. **Delete old code paths** (still on git history):
   - `public_html/`, `api/`, `class/`, `src/Classes/` (the old ones —
     keep `src/` which is the Symfony tree if moved to root),
     `autoloader.php`, `config/paths.php`, `index.php` at root.
3. **Update `README.md`**: getting started, env setup, running tests.
4. **`composer.json` scripts**:
   ```json
   "scripts": {
     "dev": "symfony serve",
     "fixtures": "php bin/console doctrine:fixtures:load --no-interaction"
   }
   ```
5. **One smoke test per controller** (`make:test`).
6. **Final commit** on `symfony-migration`, then PR to `main`.

**Verification gate**:
- [ ] `composer install && symfony serve` from a fresh clone works.
- [ ] All 11 routes from Phases 5–10 render.
- [ ] `php bin/console doctrine:schema:validate` passes.
- [ ] `php bin/phpunit` passes.
- [ ] User signs off.

---

## Quick reference — what to port verbatim vs rewrite

| Source | Destination | Mode |
|---|---|---|
| `src/Classes/WeatherService.php` | `src/Service/WeatherService.php` | Rewrite IO layer (HttpClient + CacheInterface), keep WMO mapping verbatim |
| `src/Classes/MoonService.php` | `src/Service/MoonService.php` | Verbatim (pure math) |
| `src/Classes/*Repository.php` | `src/Repository/*Repository.php` | Rewrite — Doctrine repositories, not PDO |
| `src/Classes/ConnexionDB.php` | DELETE | Doctrine handles connections |
| `css/shared/hiki-page.css` | `assets/styles/shared/hiki-page.css` | Verbatim |
| `css/pages/*.css` | `assets/styles/pages/*.css` | Verbatim |
| `js/stars-bg.js` | `assets/javascript/stars-bg.js` | Verbatim |
| `js/moon-page.js` | `assets/javascript/moon-page.js` | Verbatim |
| `js/weather-page.js` | `assets/javascript/weather-page.js` | Verbatim |
| `public_html/*.php` (markup) | `templates/*/index.html.twig` | Translate PHP → Twig syntax |
| `public_html/auth/process*.php` | DELETE | Symfony Security handles auth |
| `database/init.sql` schema | Doctrine entities + migration | Rewrite as entities |
| `database/init.sql` INSERTs | `src/DataFixtures/*.php` | Port the seed rows |

---

## Known traps

1. **The space in the DB password.** `golden ratio 1.618` must be URL-encoded
   in `DATABASE_URL` as `golden%20ratio%201.618`. Don't quote it.
2. **`users.id` is the email.** Don't add an `email` column. Keep using
   the id field as the login identifier. Set `username_path: email` in
   `security.yaml` so the form's `email` input maps to the user
   identifier.
3. **AssetMapper is not Webpack Encore.** If the user has tutorials based
   on Encore, those won't apply. Use `php bin/console importmap:require`
   not `npm install`.
4. **MariaDB version in DATABASE_URL.** The user likely uses XAMPP's
   MariaDB 10.4.x. Set `serverVersion=10.4.32-MariaDB` (or whatever
   `SELECT VERSION()` returns) — wrong serverVersion causes Doctrine to
   pick the wrong platform and migrations break.
5. **The `mysqli` warning** is harmless; don't waste time fixing it during
   migration. Symfony uses PDO, not mysqli.
6. **The CSS `weather-card__moon` references `moon.png`**, not
   `night-moon-component.png`. The latter has a yellow card baked into the
   PNG — using it produces a duplicate-card glitch on the weather page.
   Keep that fix when porting `weather/index.html.twig`.
7. **The theme toggle uses pure CSS** in the current code
   (`css/shared/hiki-page.css`) — there's no `LandSwitch-Dark.png`
   background. Don't reintroduce the image; it has a thumb baked in and
   collides with the JS-rendered thumb.

---

## Out of scope (do not start without asking the user)

- Production deployment (Platform.sh, Heroku, OVH, anything).
- Docker / Docker Compose setup.
- Migrating to PostgreSQL.
- Adding a REST API beyond the existing 4 endpoints.
- WebSocket / live community chat.
- Email sending for password reset.
- Front-end framework (Vue/React/Stimulus beyond what Symfony ships).

---

## When you're done

1. Update `MIGRATION_LOG.md` with what was completed per phase and any
   deviations from this plan.
2. Open `CONTEXT.md` (top-level file) and add a "Symfony" section
   pointing at the new entrypoints.
3. Hand the user a short summary: "Phases 0–11 done. New project at repo
   root. Run `symfony serve` to start. Old code preserved on branch
   `pre-symfony` if you need to compare."
