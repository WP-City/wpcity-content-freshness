# Getting Started

WPCity Content Freshness records when each piece of content was last reviewed and tells you, at a glance, which pages have gone stale.

## Installation

1. Upload the `wpcity-content-freshness` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Go to **Settings > Content Freshness** to choose which post types to track

## First Steps

### 1. Choose your post types

After activation, open **Settings > Content Freshness**. You will see every public post type on your site. Tick the ones that need a review cycle. Posts and pages are enabled by default.

### 2. Set a default review interval

On the same screen, pick how often content should be revisited: 3, 6 or 12 months. That interval applies to every tracked post unless you override it on the post itself.

### 3. Review a single post

Open any tracked post or page. The **Content Freshness** box sits in the sidebar, below the Publish block, and shows three things:

- **Review interval**: use the site default, pick 3, 6 or 12 months, or choose "Don't track" to leave this post out entirely
- **Last reviewed**: the date the content was last confirmed as current
- A coloured status line telling you where this post stands

Press **Mark as Reviewed** and the date is stored immediately, without reloading the page.

### 4. Read the colours

| Colour | Meaning |
|--------|---------|
| Green | Reviewed recently, nothing to do |
| Orange | Due within the next 30 days |
| Red | Overdue, the interval has passed |
| Grey | Not tracked, you set the interval to "Don't track" |

The same dot appears in the **Content Freshness** column of every post list, next to the title. Click the column header to sort by review date; posts that were never reviewed stay in the list.

### 5. Watch the dashboard

The **Content Freshness** dashboard widget lists the five most urgent posts, with a link through to the full list. When nothing is overdue it simply says so.

## What counts as "last reviewed"

A post that has never been marked as reviewed falls back to its own last modified date. That way a freshly written page does not show up as overdue on the day you install the plugin, and the count means something from the very first day.

## Going further

- [Settings](settings.md): every option explained
- [Freshness tracking](freshness.md): how the status is calculated
- [Hooks and filters](hooks.md): extending the plugin in code
- [FAQ](faq.md): common questions
