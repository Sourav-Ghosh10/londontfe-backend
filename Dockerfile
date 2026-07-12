FROM php:8.3-cli

# System dependencies for gd and common extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configure and install gd (required by mpdf/mpdf and phpoffice/phpspreadsheet)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql opcache zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader --no-interaction

# Bind Laravel's built-in server to Railway's provided PORT on 0.0.0.0
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
