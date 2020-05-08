# Default value
ARG NAME_IMG_BASE=php:cli-alpine
ARG NAME_TYPE_REPORT=summary

FROM ${NAME_IMG_BASE}

COPY . /app

RUN \
    EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"; \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"; \
    [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]  && { >&2 echo 'ERROR: Invalid installer checksum'; exit 1; }; \
    php composer-setup.php --quiet --install-dir=$(dirname $(which php)) --filename=composer&& \
    rm composer-setup.php

WORKDIR /app
RUN \
    composer install

ARG NAME_TYPE_REPORT

# Available report type see: Key values under "script" element in composer.json
ENTRYPOINT [ "composer", "bench-${NAME_TYPE_REPORT}" ]