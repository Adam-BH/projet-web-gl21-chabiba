# Hiki — Hiking & Camping Website

## Overview

Hiki is a PHP-based website for hiking and camping enthusiasts. It provides campsite listings, bookings, trail info, community posts (lost & found), weather and moon info, and a search/catalogue interface.

## Features

- Campsites: browse camping sites with images and details.
- Bookings: create and manage reservations for camping sites.
- Trails: trail listings and details.
- Catalogue & Equipment: browse equipment and availability.
- Community: posts and lost & found management.
- Search engine: site search for trails, sites, and equipment.
- Weather & Moon pages: local weather forecasts and moon/astronomy info (uses cached JSON in `cache/weather`).
- Authentication: signup/login flows for users.

## Tech Stack

- PHP (server-side)
- MySQL (database)
- JavaScript, CSS, and static assets in `assets/` and `js/`
- Designed to run on XAMPP (Apache + MySQL) on Windows

## Requirements

- XAMPP (Apache + MySQL)
- PHP 7.4+ (bundled with recent XAMPP)
- A browser to access the site

## Quick start — Run with XAMPP (Windows)

1. Install XAMPP from https://www.apachefriends.org/
2. Place the project folder inside XAMPP's `htdocs` directory. Example (your workspace already shows this):
   - `d:\xampp\htdocs\projet-web-gl21-chabiba`

3. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. Create the database and tables:
   - Open `http://localhost/phpmyadmin` and import `database/init.sql`.
   - Or from a terminal run:

     `mysql -u root < database/init.sql`

   The SQL file creates the database named `hiki` and seeds sample data. See [database/init.sql](database/init.sql#L1-L3).

5. Update database connection if needed:
   - The connection settings are in [class/ConnexionDB.php](class/ConnexionDB.php). By default XAMPP's MySQL user is `root` with no password — change the settings if your environment differs.

6. Open the site in your browser:

   `http://localhost/projet-web-gl21-chabiba/`

   If Apache uses a different port (e.g., 8080), use `http://localhost:8080/projet-web-gl21-chabiba/`.

## Notes & Troubleshooting

- If pages show database errors, verify MySQL is running and credentials in [class/ConnexionDB.php](class/ConnexionDB.php) are correct.
- Static images referenced in the database are expected under `assets/Images`.
- Cached weather forecasts are stored in `cache/weather/forecast_tunis.json`.

## Useful Files

- Database init: [database/init.sql](database/init.sql#L1-L3)
- DB connection: [class/ConnexionDB.php](class/ConnexionDB.php)
- Entry point: `index.php`

## Next steps (optional)

- I can commit this README and create a small troubleshooting guide or a sample `.env` template for DB credentials — tell me which you'd prefer.

# Tunisian Hiking & Camping Companion: **HIKI**

**Status:** Work In Progress (Pre-Alpha)

## Project Overview

This website will be the comapanion for both beginner and experienced hikers/ campers promoting this sport we consider a great pastime and think will be much fun to work on in future.

**Core Objectives:**

- **Safety:** Provide real-time forecasts (fog, rain, daylight), danger ratings, and best practices to reduce accidents.
- **Discovery:** Catalog camping sites, local landscapes, and availability.
- **Education:** Offer manuals on camping basics and stargazing (moon cycles/constellations).
  **Real Value:**solves a real world problem and need.

---

## Feature Roadmap

We intend to start small than scale the project as we grow ourselves.

**Phase 1: Core Value (Current Focus)**

- [ ] **Home Page:** Introduction and featured spots.
- [ ] **Camping Manual:** Static guides (Fire, Tent setup, Tips).
- [ ] **Camping Sites Explorer:** Directory with descriptions and **Danger/Experience Ratios**.
- [ ] **Weather Integration:** API connection for rainfall, temp, and wind.
- [ ] **Astronomy:** Moon cycles and constellation data.

**Phase 2: User Engagement**

- [ ] **Authentication:** User Login/Sign-up.
- [ ] **Rating System:** User reviews for sites.
- [ ] **Gear Links:** Equipment recommendations per site.
- [ ] **Shops Directory:** Map of local outdoor equipment sellers.

**Phase 3: Advanced Logic**

- [ ] **Community Chat:** Real-time discussion.
- [ ] **Recommendation Engine:** Algorithmic suggestions based on user skill/weather.
- [ ] **Availability:** Crowdsourced status of campsites.

---

## ⚙️ Workflow & Git Strategy

We use a strict workflow to maintain code quality and organization. For that we consider using github Projects.

### 1. General Workflow

- Work is divided into specific tasks/features.
- One developer is responsible for one task at a time.
- **Cycle:**
  1.  Pull latest `develop`.
  2.  Create a branch from `develop`.
  3.  Code and commit regularly.
  4.  Push to GitHub.
  5.  Merge into `develop` upon completion.

### 2. Branching Model (Git Flow)

- **Main Branches:**
  - `main`: Stable, production-ready code. **No direct commits.**
  - `develop`: Latest completed features. Integration branch.
- **Supporting Branches:**
  - **Feature:** `feature/<feature-name>` (e.g., `feature/login-page`)
  - **Bugfix:** `bugfix/<bug-name>` (e.g., `bugfix/form-validation`)

### 3. Commit Convention

Use the following format for commit messages:
`<type>: <short description>`

**Types:**

- `feat`: New feature
- `fix`: Bug fix
- `refactor`: Code restructuring without behavioral change
- `docs`: Documentation updates
- `chore`: Config, dependencies, tooling

**Examples:**

> feat: add login form
> fix: correct password validation

### 4. Repository Hygiene

- **Always** pull from `develop` before starting.
- **Never commit:** `.env` files, API keys, `node_modules/`.
- **Conflict Resolution:** Communicate with the team, resolve locally, and test before pushing.

---

## 💻 Coding Standards

### File & Folder Naming

- **Folders:** `lowercase`, `kebab-case` if needed.
- **Files:**
  - PHP/HTML: `login.php`, `dashboard.php`
  - CSS: `main.css`, `navbar.css`
  - JS: `form-validation.js`
- **Variables/Functions:** `camelCase` (e.g., `handleLoginSubmit()`). Use descriptive names.

---

## 🛠️ Tech Stack & Tools

**Development Environment:**

- **Editor:** VS Code (Recommended: Live Server extension).
- **Version Control:** GitHub + Git.
- **Task Tracking:** GitHub Projects / Issues.
- **Testing:** Postman (for API testing).

**Technologies:**

- **Frontend:** HTML, CSS, JavaScript (Node.js/npm for tooling).
- **Backend:** we still don't get this part too much ...
- **Formatting:** Prettier.

---

## 🚀 Getting Started

1.  **Clone the repo:**
    ```bash
    git clone [https://github.com/Adam-BH/projet-web-gl21-chabiba.git](https://github.com/Adam-BH/projet-web-gl21-chabiba.git)
    ```
2.  **Install dependencies (if applicable):**
    ```bash
    npm install
    ```
3.  **Setup Environment:**
    - Create a `.env` file based on `.env.example`.
    - Add necessary API keys.
