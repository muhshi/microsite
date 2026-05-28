# Image dasar FrankenPHP (PHP 8.4 + Caddy)
FROM dunglas/frankenphp:php8.4

ENV SERVER_NAME=":80"
# Lokasi proyek di dalam container
WORKDIR /app

# Sistem deps & ekstensi PHP yang umum dipakai Laravel/Filament
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl gd zip pdo_mysql \
    && docker-php-ext-enable intl gd zip pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js & npm (copy from official node image for reliability)
COPY --from=node:20 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20 /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

COPY . /app
# Composer (buat install deps dari dalam container)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy konfigurasi Caddy/FrankenPHP
COPY Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
EXPOSE 443
EXPOSE 443/udp

# (Opsional tapi berguna) set permission direktori Laravel
# Catatan: karena kamu bind-mount ., permission tetap mengikuti host.
# Perintah ini tetap aman dijalankan.
RUN mkdir -p /app/storage /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache
