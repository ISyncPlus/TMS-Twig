# TMS Twig (Twig-based replica)

This is a minimal Twig-based replica of the Ticket Management System UI. It's intentionally lightweight: it uses the Tailwind CDN and embeds the React project's global CSS so the design matches closely without a full Tailwind build.

Run locally (requirements: PHP >= 7.4 and Composer):

1. Install dependencies:

```bash
composer install
```

2. Run the built-in PHP web server from the project root:

```bash
php -S localhost:8000 -t public
```

Open http://localhost:8000/ in your browser.

Notes:

- Templates are in `templates/`.
- The base template embeds Tailwind via CDN and includes the React globals to achieve visual parity.
- This is a static, minimal router (no database). Signup/login are not functional — they are UI-only unless you wire server-side logic.
