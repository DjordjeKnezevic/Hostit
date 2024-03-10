FROM php:8.2-fpm

RUN apt-get update && apt-get upgrade && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    curl \
    gnupg2 \
    libicu-dev && \
    docker-php-ext-configure intl && \ 
    docker-php-ext-install intl pdo_mysql zip gd

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./laravel .

RUN npm install

# For production
# RUN npm run dev

COPY ./docker/php/start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 9000
CMD ["/start.sh"]
