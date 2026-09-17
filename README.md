# TrackSmart

TrackSmart is a PHP/MySQL web application for managing student attendance. It has separate admin, HOD, and faculty areas, with AJAX endpoints for attendance and user-management actions.

## Stack

- PHP with MySQLi
- MySQL or MariaDB
- Composer, with [PHPMailer](https://github.com/PHPMailer/PHPMailer) for SMTP email
- Bootstrap/jQuery-based static front-end assets

## Project layout

- `admin/` — administrator screens and actions
- `hod/` — head-of-department screens and actions
- `faculty/` — faculty attendance screens
- `ajax/` — AJAX request handlers
- `includes/` — shared UI, authentication, and database connection code
- `assets/` — application styles, scripts, images, and bundled browser libraries

## Local setup

1. Install PHP 7.4+ with the `mysqli` extension, Composer, and MySQL/MariaDB.
2. Run `composer install` in the project root to install PHPMailer.
3. Create a database and import a **sanitized/private** schema or backup. See `database/README.md`.
4. Set the `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` environment variables in your web-server or hosting configuration. `.env.example` lists every supported setting; `.env` files are not loaded automatically.
5. Serve this folder through Apache, Nginx + PHP-FPM, or PHP's development server.

For the optional `testemail.php` SMTP check and absence-email alerts, also configure the `SMTP_*` environment variables in `.env.example`. Do not expose that test endpoint on a public production site. SMS notifications require the `SMS_*` settings; match the values and request format to your SMS provider.

## Security and Git

The original database export and its live database/SMTP credentials were deliberately left out. This repository uses server environment variables instead. Keep real credentials in your hosting provider's secret/settings panel, not in Git. `vendor/`, database exports, logs, user uploads, and local configuration are ignored; regenerate PHP dependencies with `composer install`.

## Publishing to GitHub

```bash
git init
git add .
git commit -m "Initial TrackSmart import"
git branch -M main
git remote add origin https://github.com/YOUR-ACCOUNT/tracksmart.git
git push -u origin main
```

Create an empty GitHub repository first, and do not initialize it with a README, `.gitignore`, or license before pushing.
