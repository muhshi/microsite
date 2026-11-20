#!/bin/bash
# ==========================================================
#  deploy.sh - Script Deploy Cepat & Otomatis untuk Microsite
#  Penggunaan: 
#    bash deploy.sh          (Deploy cepat tanpa rebuild)
#    bash deploy.sh --build  (Paksa rebuild image Docker)
# ==========================================================

set -e  # Berhenti jika ada error

APP_DIR=~/apps/microsite
CONTAINER_NAME=microsite-franken
BRANCH=main
FORCE_BUILD=false

# Cek argumen --build atau -b
for arg in "$@"; do
    if [ "$arg" == "--build" ] || [ "$arg" == "-b" ]; then
        FORCE_BUILD=true
    fi
done

echo ""
echo "=========================================="
echo "  🚀 Memulai Deploy Microsite..."
echo "=========================================="
echo ""

# 1. Masuk ke direktori aplikasi
cd "$APP_DIR"
echo "📂 Direktori: $(pwd)"

# Simpan commit lama sebelum pull untuk deteksi perubahan file
OLD_COMMIT=$(git rev-parse HEAD 2>/dev/null || echo "")

# 2. Fetch & Pull dari GitHub
echo ""
echo "📥 [1/6] Mengambil kode terbaru dari GitHub..."
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"
echo "   ✅ Kode terbaru berhasil ditarik."

NEW_COMMIT=$(git rev-parse HEAD)

# Deteksi apakah perlu rebuild Docker image
NEED_BUILD=$FORCE_BUILD

if [ "$FORCE_BUILD" = true ]; then
    echo "   ℹ️  Rebuild dipicu secara manual (flag --build)."
elif [ -n "$OLD_COMMIT" ] && [ "$OLD_COMMIT" != "$NEW_COMMIT" ]; then
    CHANGED_FILES=$(git diff --name-only "$OLD_COMMIT" "$NEW_COMMIT")
    if echo "$CHANGED_FILES" | grep -qE "^(Dockerfile|docker-compose\.yml|Caddyfile|docker/)"; then
        echo "   ℹ️  Terdeteksi perubahan pada konfigurasi Docker/Caddy. Rebuild diperlukan."
        NEED_BUILD=true
    fi
fi

# Cek apakah image Docker untuk layanan utama sudah ada
if [ "$NEED_BUILD" = false ]; then
    IMAGE_EXISTS=$(docker compose images -q "$CONTAINER_NAME" 2>/dev/null || docker images -q "$CONTAINER_NAME" 2>/dev/null || echo "")
    if [ -z "$IMAGE_EXISTS" ]; then
        echo "   ℹ️  Image Docker belum tersedia di sistem. Build pertama kali diperlukan."
        NEED_BUILD=true
    fi
fi

# 3. Build & Restart Container jika diperlukan
echo ""
if [ "$NEED_BUILD" = true ]; then
    echo "🔨 [2/6] Rebuild Docker image & restart stack..."
    docker compose build
    docker compose down
    docker compose up -d --build
    echo "   ✅ Image berhasil di-build & container dinyalakan ulang."
else
    echo "⚡ [2/6] Lewati build Docker (image sudah tersedia & tidak ada perubahan Docker/Caddy)..."
    docker compose up -d --no-build
    echo "   ✅ Container aktif."
fi

# 4. Update dependencies jika composer.lock berubah
if [ -n "$OLD_COMMIT" ] && [ "$OLD_COMMIT" != "$NEW_COMMIT" ]; then
    if echo "$CHANGED_FILES" | grep -q "composer.lock"; then
        echo ""
        echo "📦 Terdeteksi perubahan composer.lock, menjalankan composer install..."
        docker exec "$CONTAINER_NAME" composer install --no-dev --optimize-autoloader
        echo "   ✅ Dependencies berhasil diupdate."
    fi
fi

# 5. Jalankan migrasi (tanpa --fresh, hanya yang baru)
echo ""
echo "🗄️  [3/6] Menjalankan migrasi database..."
docker exec "$CONTAINER_NAME" php artisan migrate --force
echo "   ✅ Migrasi selesai."

# 6. Build frontend assets (Vite)
echo ""
echo "🎨 [4/7] Build frontend assets (Vite)..."
docker exec "$CONTAINER_NAME" npm run build
echo "   ✅ Aset Vite berhasil di-build."

# 7. Optimasi Laravel (cache config, route, view)
echo ""
echo "⚡ [5/7] Optimasi Laravel..."
docker exec "$CONTAINER_NAME" php artisan optimize:clear
docker exec "$CONTAINER_NAME" php artisan config:cache
docker exec "$CONTAINER_NAME" php artisan route:cache
docker exec "$CONTAINER_NAME" php artisan view:cache
docker exec "$CONTAINER_NAME" php artisan event:cache
docker exec "$CONTAINER_NAME" php artisan icons:cache
docker exec "$CONTAINER_NAME" php artisan filament:optimize
echo "   ✅ Cache berhasil di-generate."

# 8. Reload Web Server
echo ""
echo "👷 [6/7] Reload Web Server..."
docker restart "$CONTAINER_NAME" >/dev/null 2>&1 || true
echo "   ✅ Web Server di-reload dengan kode terbaru."

# 9. Bersihkan image Docker lama hanya jika baru saja build
echo ""
if [ "$NEED_BUILD" = true ]; then
    echo "🧹 [7/7] Membersihkan image Docker lama yang tidak terpakai..."
    docker image prune -f
    echo "   ✅ Image lama dibersihkan."
else
    echo "🧹 [7/7] Lewati pembersihan image."
fi

# 9. Verifikasi
echo ""
echo "=========================================="
echo "  ✅ Deploy Selesai!"
echo "=========================================="
echo ""
echo "📊 Status Container:"
docker ps --filter "name=$CONTAINER_NAME" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo ""
