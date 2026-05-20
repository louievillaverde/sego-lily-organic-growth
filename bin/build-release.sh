#!/usr/bin/env bash
# Build a release zip of the Sego Lily Routine Quiz wrapper plugin.
# Output: dist/sego-lily-routine-quiz.zip

set -e
cd "$(dirname "$0")/.."

PLUGIN_SLUG="sego-lily-routine-quiz"
VERSION=$(grep -E "^\s*\*\s+Version:" "$PLUGIN_SLUG.php" | sed -E 's/.*Version:\s*//' | tr -d ' ')

echo "Building $PLUGIN_SLUG v$VERSION..."

rm -rf dist build
mkdir -p build/$PLUGIN_SLUG dist

rsync -av --exclude='.git' --exclude='dist' --exclude='build' --exclude='.DS_Store' --exclude='*.zip' --exclude='bin' --exclude='node_modules' --exclude='.gitignore' ./ build/$PLUGIN_SLUG/

cd build
zip -rq ../dist/$PLUGIN_SLUG.zip $PLUGIN_SLUG
cd ..

rm -rf build

echo "Done. dist/$PLUGIN_SLUG.zip ($(du -h dist/$PLUGIN_SLUG.zip | cut -f1))"
