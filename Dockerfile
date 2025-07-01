FROM php:8.4-fpm

WORKDIR /var/www/projects/mobilemtask

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxslt1-dev \
    libsodium-dev \
    git \
    libpq-dev && \
    # Install Symfony CLI
    curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mysqli \
    intl \
    zip \
    gd \
    xml \
    xsl \
    bcmath \
    soap \
    mbstring \
    sodium \
    exif

# RUN docker-php-ext-enable pdo_mysql pdo_pgsql pgsql mysqli intl zip gd xml xsl bcmath pcntl opcache soap mbstring exif

# Install and enable Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/90-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Symfony project files
COPY . .

ARG UID=1000
ARG GID=1000

RUN if getent group ${GID}; then \
    group_name=$(getent group ${GID} | cut -d: -f1); \
    useradd -m -u ${UID} -g ${GID} -s /bin/bash www; \
    else \
    groupadd -g ${GID} www && \
    useradd -m -u ${UID} -g www -s /bin/bash www; \
    group_name=www; \
    fi && \
    sed -i "s/user = www-data/user = www/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = $group_name/g" /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

USER www

CMD ["php-fpm"]
