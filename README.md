# WPCity Content Freshness

Track when your content was last reviewed and see at a glance which pages have gone stale.

## Features

- Review interval per post or page: 3, 6 or 12 months, or the site default
- Mark as Reviewed button in the sidebar, saved over AJAX without a reload
- Colour-coded freshness column in every post list, sortable by review date
- Dashboard widget listing the content that needs attention first
- Settings page to choose which post types are tracked
- Dutch, French, German and Spanish translations

## Requirements

- WordPress 6.4 or newer
- PHP 8.1 or newer

## Installation

1. Upload the `wpcity-content-freshness` folder to `/wp-content/plugins/`
2. Activate the plugin
3. Go to **Settings > Content Freshness** and pick your post types

## Documentation

Full documentation lives in [`docs/`](docs/), in English plus Dutch, French, German and Spanish:

- [Getting started](docs/getting-started.md)
- [Settings](docs/settings.md)
- [Freshness tracking](docs/freshness.md)
- [Hooks and filters](docs/hooks.md)
- [FAQ](docs/faq.md)

## Pro

[WPCity Content Freshness Pro](https://wpcity.dev/plugins/content-freshness-pro) adds outgoing webhooks with logging, a REST API, email digests, snooze, bulk actions and per-role review permissions. This plugin works on its own and degrades cleanly when Pro is not installed.

## License

GPL-2.0-or-later
