FROM alpine:3.11

ENV ALPINE_VERSION 3.11

ADD https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub
ADD https://alpine-pkgs.sgerrand.com/sgerrand.rsa.pub /etc/apk/keys/sgerrand.rsa.pub

RUN apk add --update --no-cache bash ca-certificates curl wget openssl supervisor git

ENV GLIBC_VERSION 2.31-r0

RUN wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/$GLIBC_VERSION/glibc-$GLIBC_VERSION.apk && \
    wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/$GLIBC_VERSION/glibc-bin-$GLIBC_VERSION.apk && \
    wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/$GLIBC_VERSION/glibc-i18n-$GLIBC_VERSION.apk && \
    apk add glibc-bin-$GLIBC_VERSION.apk glibc-i18n-$GLIBC_VERSION.apk glibc-$GLIBC_VERSION.apk && \
    rm glibc-bin-$GLIBC_VERSION.apk glibc-i18n-$GLIBC_VERSION.apk glibc-$GLIBC_VERSION.apk

COPY docker/locale.md /locale.md
RUN cat /locale.md | xargs -i /usr/glibc-compat/bin/localedef -i {} -f UTF-8 {}.UTF-8

ENV LANG=en_US.UTF-8 \
    LANGUAGE=en_US.UTF-8

ENV PHP_VERSION 7.4

RUN echo "https://dl.bintray.com/php-alpine/v$ALPINE_VERSION/php-$PHP_VERSION" >> /etc/apk/repositories && \
    apk add --update --no-cache php composer php-json && \
    ln -s /usr/bin/php7 /usr/bin/php && \
    php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY docker/supervisord.ini /etc/supervisor.d/docker.ini
COPY docker/start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

COPY . /var/www/html
WORKDIR /var/www/html

RUN addgroup brokerage
RUN adduser -S brokerage -G brokerage -h /home/brokerage -s /bin/sh
RUN chown -R brokerage:brokerage /var/www/html

USER brokerage

RUN cp -f .env.docker .env

ENV COMPOSER_MEMORY_LIMIT=-1

RUN composer update nothing
RUN composer install

USER root

ENTRYPOINT ["start-container"]
