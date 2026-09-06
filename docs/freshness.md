# Freshness Tracking

How the plugin decides whether a post is fresh, due or overdue.

## The calculation

For every tracked post:

1. Take the **effective interval**. That is the post's own interval when it has one, otherwise the site default.
2. Take the **baseline date**. That is `wpcity_cf_last_reviewed` when the post has been reviewed, otherwise the post's last modified date.
3. The deadline is the baseline plus the effective interval.
4. The status follows from how many days are left until that deadline.

| Days left | Colour | Label |
|-----------|--------|-------|
| More than 30 | Green | Reviewed [date] |
| 30 or fewer | Orange | Due in [n] days |
| 0 or fewer | Red | Overdue by [n] days |

A post with its interval set to "Don't track" is grey and is skipped everywhere.

## Why never-reviewed posts use the modified date

Without a baseline, every post on the site would count as overdue the moment you activate the plugin, and the dashboard widget would be useless on day one. Falling back to the modified date means a page edited last week reads as fresh, and a page untouched for three years reads as overdue, which is the answer you actually wanted.

Marking a post as reviewed replaces that fallback with a real review date. From then on, editing the post no longer resets its freshness, which is the point: an edit is not a review.

## Marking as reviewed

The **Mark as Reviewed** button writes the current site time to `wpcity_cf_last_reviewed` over AJAX and updates the status line in place. The request is protected by a nonce and requires `edit_post` on that post.

## Sorting the list

The **Content Freshness** column is sortable. Sorting orders by review date and keeps posts that were never reviewed in the list, so clicking the header never hides content from you.

## The dashboard widget

The widget counts every tracked, published post that is orange or red, shows the five most urgent, and links to the full list. Posts set to "Don't track" never appear.
