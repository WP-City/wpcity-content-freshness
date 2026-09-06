=== WPCity Content Freshness ===
Contributors: wpcity
Tags: content, freshness, review, editorial, maintenance
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track when your content was last reviewed and see at a glance which pages have gone stale.

== Description ==

Content rots quietly. A pricing page from two years ago still looks fine in the editor, and nothing in WordPress tells you it is out of date.

WPCity Content Freshness gives every post and page a review cycle, records when you last confirmed the content was current, and puts a coloured indicator next to the title so the stale ones stand out.

= What you get =

* A review interval per post or page: 3, 6 or 12 months, or the site default
* A Mark as Reviewed button in the sidebar, saved without a page reload
* A colour-coded column in every post list, sortable by review date
* A dashboard widget listing the content that needs attention first
* A settings page to choose which post types are tracked

= How the status is decided =

The plugin measures from the last review date, or from the post's own modified date when it has never been reviewed. That second part matters: without it, every page on your site would turn red the day you install the plugin.

* Green: reviewed recently
* Orange: due within 30 days
* Red: overdue
* Grey: excluded, you set the interval to Don't track

Editing a post does not reset its freshness. An edit is not a review, and only the button moves the date.

= Privacy =

The plugin makes no external requests, collects no statistics and phones nothing home. Everything it stores lives in your own database as options and post meta.

= For developers =

Six filters and five actions, all prefixed `wpcity_cf_`, cover the post types, the intervals, the stale verdict, the permission gate and the settings screen. Full reference in the plugin documentation.

= Pro =

WPCity Content Freshness Pro adds outgoing webhooks with logging, a REST API, email digests, snooze, bulk actions and per-role review permissions. The free plugin works on its own and degrades cleanly when Pro is not installed.

== Installation ==

1. Upload the `wpcity-content-freshness` folder to `/wp-content/plugins/`, or install it through the Plugins screen
2. Activate the plugin
3. Go to Settings > Content Freshness and choose the post types you want to track

== Frequently Asked Questions ==

= Does editing a post reset its freshness? =

No. An edit is not a review. Only the Mark as Reviewed button moves the date. The exception is a post that has never been reviewed at all, which falls back to its modified date until you review it once.

= Why is everything green right after I install it? =

Because nothing has been reviewed yet, so every post is measured from its own last modified date. Pages you have not touched in years will already show as orange or red.

= Can I exclude a single page? =

Yes. Set its Review Interval to Don't track. It turns grey and drops out of the dashboard widget.

= Does it work with custom post types? =

Yes, as long as the post type is public. Tick it on the settings screen, or add it with the `wpcity_cf_post_types` filter.

= What happens to my data if I delete the plugin? =

Everything the plugin stores is removed: the settings, the review dates on every post, the user meta, the transients and the scheduled events. Deactivating removes nothing.

= Is it available in my language? =

The plugin ships with Dutch, French, German and Spanish translations alongside the English original.

== Screenshots ==

1. The freshness meta box on the post edit screen
2. The colour-coded column in the post list
3. The dashboard widget listing content that needs review
4. The settings screen

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
First public release.
