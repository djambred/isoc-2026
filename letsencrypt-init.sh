#!/bin/bash

set -e

# ─────────────────────────────────────────────
# Konfigurasi — sesuaikan sebelum menjalankan
# ─────────────────────────────────────────────
DOMAIN="isoc.id"
EMAIL="djambred@gmail.com"
# ─────────────────────────────────────────────

CERT_PATH="./certbot/conf/live/${DOMAIN}"
OPTIONS_SSL="./certbot/conf/options-ssl-nginx.conf"
DHPARAMS="./certbot/conf/ssl-dhparams.pem"

echo "=================================================="
echo " Let's Encrypt Init — ${DOMAIN}"
echo "=================================================="

# 1. Buat folder yang dibutuhkan
echo "[1/5] Membuat folder certbot..."
mkdir -p "${CERT_PATH}"
mkdir -p "./certbot/www"
mkdir -p "./certbot/conf"

# 2. Generate dummy self-signed cert agar nginx bisa start
if [ ! -f "${CERT_PATH}/fullchain.pem" ]; then
    echo "[2/5] Membuat dummy self-signed certificate..."
    openssl req -x509 -nodes -newkey rsa:2048 -days 1 \
        -keyout "${CERT_PATH}/privkey.pem" \
        -out "${CERT_PATH}/fullchain.pem" \
        -subj "/CN=${DOMAIN}"
else
    echo "[2/5] Certificate sudah ada, skip dummy cert."
fi

# 3. Download options-ssl-nginx.conf dan dhparams jika belum ada
if [ ! -f "${OPTIONS_SSL}" ]; then
    echo "[3/5] Mengunduh options-ssl-nginx.conf..."
    curl -s https://raw.githubusercontent.com/certbot/certbot/master/certbot-nginx/certbot_nginx/_internal/tls_configs/options-ssl-nginx.conf \
        > "${OPTIONS_SSL}"
else
    echo "[3/5] options-ssl-nginx.conf sudah ada, skip."
fi

if [ ! -f "${DHPARAMS}" ]; then
    echo "[3/5] Membuat ssl-dhparams.pem (mungkin 1-2 menit)..."
    openssl dhparam -out "${DHPARAMS}" 2048
else
    echo "[3/5] ssl-dhparams.pem sudah ada, skip."
fi

# 4. Start nginx dengan dummy cert
echo "[4/5] Menjalankan nginx..."
docker compose up -d nginx php db

echo "      Menunggu nginx siap..."
sleep 5

# 5. Bersihkan akun certbot lama jika ada (mencegah error "Account not found")
if [ -d "./certbot/conf/accounts" ]; then
    echo "[5/5] Menghapus data akun certbot lama..."
    rm -rf ./certbot/conf/accounts
fi

# 5. Request sertifikat Let's Encrypt asli
echo "[5/5] Meminta sertifikat Let's Encrypt untuk ${DOMAIN}..."
docker compose run --rm certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email "${EMAIL}" \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    -d "${DOMAIN}"

# Reload nginx dengan sertifikat asli
echo ""
echo "Reload nginx dengan sertifikat Let's Encrypt..."
docker compose exec nginx nginx -s reload

echo ""
echo "=================================================="
echo " Selesai! Sertifikat Let's Encrypt aktif."
echo " Domain : https://${DOMAIN}"
echo " Expires: $(openssl x509 -enddate -noout -in ${CERT_PATH}/fullchain.pem)"
echo "=================================================="
echo ""
echo "Untuk auto-renew, tambahkan ke crontab (crontab -e):"
echo "  0 3 * * * cd $(pwd) && docker compose run --rm certbot renew --quiet && docker compose exec nginx nginx -s reload"
