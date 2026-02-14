FROM webdevops/php-nginx:8.4-alpine

WORKDIR /app
COPY . /app

RUN mkdir -p /app/storage /app/bootstrap/cache \
    && chmod -R 777 /app/storage /app/bootstrap/cache \
    && chown -R application:application /app/storage /app/bootstrap/cache || true \
    && chown -R nginx:nginx /app/storage /app/bootstrap/cache || true

# Nginxの公開ディレクトリをLaravelのpublicへ
ENV WEB_DOCUMENT_ROOT=/app/public

# Laravel本番設定（RenderのENVで上書きされてもOK）
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# composer install（本番用）
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction


RUN apk add --no-cache nodejs npm \
    && npm ci \
    && npm run build \
    && test -f public/build/manifest.json

# キャッシュ（route:cacheは失敗しても起動優先）
RUN php artisan config:cache \
    && php artisan route:cache || true

# 起動時にmigrateしてからNginx/PHP-FPM起動
COPY ./scripts/render-entrypoint.sh /render-entrypoint.sh
RUN chmod +x /render-entrypoint.sh

CMD ["/render-entrypoint.sh"]
