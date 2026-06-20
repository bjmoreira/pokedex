# EN: Backend image - PHP 8.3 + Composer, runs the Laravel built-in server.
# PT: Imagem do backend - PHP 8.3 + Composer, roda o servidor embutido do Laravel.
FROM php:8.3-cli-alpine

# EN: System packages and PHP extensions required by Laravel + MySQL.
# PT: Pacotes do sistema e extensões PHP exigidos pelo Laravel + MySQL.
RUN apk add --no-cache \
        bash \
        git \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        opcache \
    && apk del $PHPIZE_DEPS

# EN: Install Composer from the official image. / PT: Instala o Composer da imagem oficial.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# EN: Install PHP dependencies first to leverage Docker layer caching.
# PT: Instala dependências PHP primeiro para aproveitar o cache de camadas do Docker.
COPY composer.json composer.lock* ./
# EN: Install from the lock file when present; otherwise resolve fresh.
# PT: Instala a partir do lock se existir; senão resolve do zero.
RUN if [ -f composer.lock ]; then \
        composer install --no-interaction --no-progress --no-scripts --prefer-dist; \
    else \
        composer update --no-interaction --no-progress --no-scripts --prefer-dist; \
    fi

# EN: Copy the rest of the application. / PT: Copia o restante da aplicação.
COPY . .

# EN: Finish Composer setup and ensure storage is writable.
# PT: Finaliza o setup do Composer e garante storage gravável.
RUN composer dump-autoload --optimize \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# EN: Normalize CRLF -> LF (in case it was checked out on Windows) and make executable.
# PT: Normaliza CRLF -> LF (caso tenha sido baixado no Windows) e torna executável.
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

# EN: Allow the dev server to handle concurrent requests.
# PT: Permite que o servidor de desenvolvimento atenda requisições concorrentes.
ENV PHP_CLI_SERVER_WORKERS=8

ENTRYPOINT ["entrypoint.sh"]
# EN: --no-reload lets PHP_CLI_SERVER_WORKERS spawn multiple workers (concurrency).
# PT: --no-reload permite que PHP_CLI_SERVER_WORKERS gere vários workers (concorrência).
CMD ["php", "-d", "variables_order=EGPCS", "artisan", "serve", "--host=0.0.0.0", "--port=8000", "--no-reload"]
