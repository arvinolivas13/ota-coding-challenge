# Stage 1: Composer install
FROM composer:2 AS composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Node build (Vue/TypeScript)
FROM node:20 AS frontend

WORKDIR /app
COPY . .
RUN apt-get update && apt-get install -y git curl unzip python3 make g++
RUN npm install
RUN npm run build

# Stage 3: Final PHP runtime image
FROM php:8.2-cli

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring

WORKDIR /app

# Copy from previous stages
COPY --from=composer /app /app
COPY --from=frontend /app/public /app/public

# Laravel permissions
RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Set environment variables (optional if using `.env`)
ENV APP_ENV=production
ENV APP_DEBUG=false

# Start Laravel with built-in server (for dev only)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
