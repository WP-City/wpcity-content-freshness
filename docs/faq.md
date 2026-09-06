# FAQ

### Does editing a post reset its freshness?

No, and that is deliberate. An edit is not a review. Only the **Mark as Reviewed** button, or writing `wpcity_cf_last_reviewed` yourself, moves the date.

The one exception is a post that has never been reviewed at all. It has no date to work from, so the plugin falls back to the post's last modified date until you review it for the first time.

### Why is everything green right after I install the plugin?

Because nothing has been reviewed yet, so every post is measured from its own last modified date. Recently edited content therefore reads as fresh. Pages you have not touched in years will already be orange or red.

### Can I stop tracking a single page?

Yes. Set its **Review Interval** to "Don't track". The status turns grey, the post drops out of the dashboard widget, and the column shows a dash.

### Where did my posts go when I sorted by the freshness column?

They did not go anywhere. Older versions dropped every never-reviewed post from the list when you sorted on that column, which is fixed: sorting now keeps the full list.

### I changed the default interval and nothing happened.

The default only applies to posts whose own interval is "Use default". Any post you gave a fixed 3, 6 or 12 month cycle keeps that cycle.

### Does the plugin send anything anywhere?

No. The free plugin makes no external requests at all. Webhooks and email digests are part of the Pro add-on and are off until you configure them.

### Does it work with custom post types?

Yes, as long as the post type is public. Tick it under **Settings > Content Freshness**, or add it in code through the `wpcity_cf_post_types` filter.

### What happens to my data if I delete the plugin?

The four things it stores are removed: the two settings, and the review interval and review date on every post. Nothing else. If you also run Pro, its settings and its licence stay where they are and go when you delete Pro. Deactivating this plugin removes nothing at all.

### Is the review date stored in site time or UTC?

Site time. It is written with `current_time( 'mysql' )` and displayed with the site's own date format.

### Can I mark many posts as reviewed at once?

Not in the free plugin. Bulk actions, a REST API, snooze, webhooks and email digests are part of WPCity Content Freshness Pro.
