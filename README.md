# Sego Lily Routine Quiz

Sego Lily-branded skincare routine quiz wrapper for the [LP Quiz Suite](https://github.com/louievillaverde/lp-quiz-suite) engine.

## What this plugin does

- Brands the quiz plugin as "Sego Lily Routine Quiz" in Holly's WP admin
- Auto-creates `/your-routine` page on activation with the quiz embedded
- Sego Lily-specific heading + subheading on the quiz
- Holly sign-off on the results screen
- Self-updates from this repo's GitHub releases

## What it depends on

- **LP Quiz Suite** engine plugin (must be installed + activated first). The wrapper shows an admin notice if missing.

## Install order

1. Install [LP Quiz Suite v2.0.0+](https://github.com/louievillaverde/lp-quiz-suite/releases/latest)
2. Activate LP Quiz Suite
3. Settings → LP Quiz Suite → confirm Mautic credentials (auto-detected from `sego-lily-wholesale` plugin if installed, otherwise enter manually)
4. Install this plugin (Sego Lily Routine Quiz)
5. Activate — `/your-routine` page is automatically created and live

## Smoke test

Incognito → `segolilyskincare.com/your-routine` → walk the 5 questions → verify:
- Inline 2-product result renders with Holly sign-off
- "Shop →" links point at real WooCommerce product pages
- Mautic admin shows new contact with tags: `quiz-completed`, `retail-quiz-lead`, matching skin concern + frustration

## Release process

```sh
# 1. Bump version in sego-lily-routine-quiz.php (Plugin header + SLRQ_VERSION constant)
git push origin main
git tag -a vX.Y.Z -m "vX.Y.Z: summary"
git push origin vX.Y.Z

# 2. Build the zip
bin/build-release.sh

# 3. Create the GitHub release with zip attached
gh release create vX.Y.Z dist/sego-lily-routine-quiz.zip --title "vX.Y.Z" --notes "..."
```

The plugin's self-updater hits `/releases/latest` every 12 hours and detects new versions. Updates ship through WP admin → Plugins → Update.
