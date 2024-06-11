FROM php:8.2-fpm-buster

ARG USER_ID
ARG GROUP_ID
ARG USERNAME

ENV USER_ID=${USER_ID:-0} \
    GROUP_ID=${GROUP_ID:-0} \
    GD_DEPS="libpng-dev libjpeg-dev libwebp-dev libxpm-dev libfreetype6-dev"

WORKDIR /code

COPY --from=composer:2.5.4 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update -qq --fix-missing \
    && apt-get install -qq -y --no-install-recommends \
    bash \
    git \
    wget \
    curl \
    ca-certificates \
    tzdata \
    apt-transport-https \
    libxslt-dev \
    libxml2-dev \
    lsb-release \
    apt-utils \
    software-properties-common \
    xz-utils \
    libfontconfig1 \
    libxrender1 \
    libicu-dev \
    locales \
    pngquant \
    zlib1g-dev \
    libzip-dev \
    unzip \
    ${GD_DEPS} \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    opcache \
    bcmath \
    calendar \
    gd \
    zip \
    intl \
    exif \
    gettext \
    pcntl \
    shmop \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    xsl

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN if [ ${USER_ID} -ne 0 ] && [ ${GROUP_ID} -ne 0 ]; then \
        groupadd -g ${GROUP_ID} ${USERNAME} \
        && useradd -l -u ${USER_ID} -g ${GROUP_ID} -m ${USERNAME} \
        && chown --changes --silent --no-dereference --recursive ${USER_ID}:${GROUP_ID} \
            /run \
            /var/log \
            /usr/local/lib \
            /usr/local/etc \
            /usr/local/sbin/php-fpm \
            /usr/local/bin/composer 2>&1 >/dev/null \
    ;fi

COPY ./code /code/

USER ${USERNAME}

EXPOSE 9000
