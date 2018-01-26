FROM php:7.2-cli

# zip
RUN apt-get update && apt-get install -y zlib1g-dev \
    && docker-php-ext-install zip \
    && rm -r /var/lib/apt/lists/*

# icu
RUN curl -sS -o /tmp/icu.tar.gz -L http://download.icu-project.org/files/icu4c/58.2/icu4c-58_2-src.tgz \
    && tar -zxf /tmp/icu.tar.gz -C /tmp \
    && cd /tmp/icu/source \
    && ./configure --prefix=/usr/local \
    && make \
    && make install

# intl
RUN docker-php-ext-configure intl --with-icu-dir=/usr/local \
    && docker-php-ext-install intl
