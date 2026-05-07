FROM php:8.4-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    nodejs \
    npm \
    $PHPIZE_DEPS

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    pcntl \
    fileinfo \
    exif \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ensure storage and bootstrap/cache are writable
RUN chmod -R 775 storage bootstrap/cache

# Install Node.js dependencies and build assets
RUN npm install --ignore-scripts && npm run build

# Expose port for artisan serve
EXPOSE 8000

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
