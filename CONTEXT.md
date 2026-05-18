# HIKI Project Context

## Overview
HIKI is a web companion for hiking and camping in Tunisia. The current status is pre-alpha with a mix of static content pages and PHP-backed flows (notably authentication and Lost & Found).

Primary goals:
- Safety guidance (weather-aware and best practices)
- Discovery of places and resources
- Beginner education through practical manuals

## Tech Stack
- Frontend: HTML, CSS, JavaScript (vanilla)
- Styling: Bootstrap 5.3.8 plus custom CSS
- Backend: PHP (plain PHP with repository pattern)
- Database: MySQL
- Tooling: npm for dependency management, Sass available in devDependencies

## Runtime / Local Setup
- Expected local environment: XAMPP (Apache + MySQL)
- Project root in web server: htdocs/projet-web-gl21-chabiba
- Install frontend dependencies once:
  - npm install

Useful local URLs:
- Home: /projet-web-gl21-chabiba/
- Login: /projet-web-gl21-chabiba/pages/auth/login.php
- Signup: /projet-web-gl21-chabiba/pages/auth/signup.php
- Lost & Found: /projet-web-gl21-chabiba/pages/lost&found/lost&found.php

## High-Level Structure
- assets/: fonts and images
- class/: PHP data layer (DB connection, repositories, interface)
- css/: global and page-level styles
- js/: global frontend scripts, API stubs, data stubs, feature stubs
- pages/: routeable HTML/PHP pages and auth handlers

## Backend Architecture (PHP)
- class/ConnexionDB.php:
  - Singleton PDO connection to MySQL database hiki
- class/Repository.php + class/Irepository.php:
  - Base CRUD abstraction and common repository interface
- class/UserRepository.php, class/PostRepository.php, class/AdressRepository.php:
  - Table-specific data access
- pages/autoloader.php:
  - Spl autoload hook for classes in class/

## Main Functional Flows
### Authentication
- Signup form posts to pages/auth/processSignUp.php
- Login form posts to pages/auth/processLogin.php
- Session is used to store current user identifier in $_SESSION['user']
- Passwords are hashed with password_hash and validated with password_verify

### Lost & Found
- PHP page for posting/finding items exists under pages/lost&found/
- Data is intended to persist in posts table via repositories

### Frontend Interactions
- js/main.js provides star-canvas background animation
- js/main.js also handles mascot click tips on pages where mascot exists

## Database Snapshot
The included schema notes describe:
- users:
  - id (email-like identifier), username, password hash, phone, city, created_at
- adresses:
  - id (city), lat, lon
- posts:
  - finder, item, place, phone, picture, created_at

Reference file: database_structure.txt

## Current Integration State
Implemented:
- Base project scaffolding and visual identity
- Authentication pages and processors
- Repository/data access pattern

Partially implemented or stubbed:
- Weather and astronomy API modules in js/api/
- Recommendations/ratings/availability logic in js/features/
- Static content pages that still need richer dynamic data

## Known Gaps / Risks
- Mixed routing patterns (direct page links vs hash-style links in some templates)
- Some pages are placeholders and need consistent style/content completion
- processLogin.php and processSignUp.php redirect to search.php, which is not present in the current workspace snapshot
- API integration modules are present but return stubs/placeholders

## Frontend Notes
- Home page visual style combines custom animation and Bootstrap base utilities
- Custom CSS is currently concentrated in css/style.css
- For consistent scaling, future changes should gradually extract shared tokens/components into modular css/base and css/layout files

## Dependencies
From package.json:
- dependencies:
  - bootstrap ^5.3.8
- devDependencies:
  - sass ^1.97.3
