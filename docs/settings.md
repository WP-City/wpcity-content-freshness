# Settings

All settings live under **Settings > Content Freshness**.

## Post Types

Which post types get a freshness meta box, a list column and a place in the dashboard widget.

Every public post type on the site is listed, apart from attachments. Posts and pages are ticked by default.

Turning a post type off hides the meta box and the column, but keeps the review dates already stored on those posts. Turn it back on and the history is still there.

## Default Review Interval

How long content stays fresh before it needs another look: 3 months (90 days), 6 months (180 days) or 12 months (365 days). The default is 6 months.

This is the interval used by every tracked post that does not carry its own. To give a single post a different cycle, change **Review Interval** in the sidebar box on that post.

## Per-post override

The **Review Interval** dropdown in the post sidebar accepts:

| Choice | Stored value | Effect |
|--------|--------------|--------|
| Use default | `0` | Follows the site default, so changing the default moves this post too |
| 3 months | `90` | Fixed 90 day cycle |
| 6 months | `180` | Fixed 180 day cycle |
| 12 months | `365` | Fixed 365 day cycle |
| Don't track | `-1` | No status, no colour, excluded from the dashboard widget |

## Where the data is stored

| Key | Type | Meaning |
|-----|------|---------|
| `wpcity_cf_post_types` | Option | Array of tracked post type slugs |
| `wpcity_cf_default_interval` | Option | Site default interval in days |
| `wpcity_cf_review_interval` | Post meta | Per-post interval in days |
| `wpcity_cf_last_reviewed` | Post meta | MySQL datetime of the last review |

Deleting the plugin removes all four, along with every other key in the `wpcity_cf_` namespace.
