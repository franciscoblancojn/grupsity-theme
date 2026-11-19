#!/usr/bin/env bash
# Sube la versión de style.css, renombra "## [Sin publicar]" en CHANGELOG.md y publica.
# Uso: npm run push-v -- major|minor|patch  (confirmar antes: hace commit, tag y push)
set -e

if [ $# -ne 1 ] || [[ ! "$1" =~ ^(major|minor|patch)$ ]]; then
    echo "Uso: npm run push-v -- major|minor|patch"
    exit 1
fi

VERSION=$(grep -m1 "^Version:" style.css | sed 's/.*Version:[[:space:]]*//' | tr -d '\r')
MAJOR=$(echo "$VERSION" | cut -d. -f1)
MINOR=$(echo "$VERSION" | cut -d. -f2)
PATCH=$(echo "$VERSION" | cut -d. -f3)

case "$1" in
    major)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        ;;
    minor)
        MINOR=$((MINOR + 1))
        PATCH=0
        ;;
    patch)
        PATCH=$((PATCH + 1))
        ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"

if git rev-parse -q --verify "refs/tags/$NEW_VERSION" > /dev/null; then
    echo "✗ El tag $NEW_VERSION ya existe"
    exit 1
fi

# No se publica una versión sin notas en el changelog.
if ! grep -q '^## \[Sin publicar\]' CHANGELOG.md; then
    echo "✗ CHANGELOG.md no tiene una sección '## [Sin publicar]' con los cambios de esta versión"
    exit 1
fi

sed -i "s/^\(Version: *\)[0-9.]\+/\1$NEW_VERSION/" style.css
sed -i "s/^## \[Sin publicar\].*/## [$NEW_VERSION] - $(date +%F)/" CHANGELOG.md

echo "✓ Versión: $VERSION → $NEW_VERSION"
echo "✓ CHANGELOG.md: [Sin publicar] → [$NEW_VERSION]"

npm run push-tag --silent
