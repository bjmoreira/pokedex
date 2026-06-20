#!/usr/bin/env bash
# EN: Container entrypoint - prepares the app then runs the given command.
# PT: Entrypoint do container - prepara a app e então roda o comando informado.
set -euo pipefail

cd /var/www/html

# EN: Create .env from the example on first run. / PT: Cria .env a partir do exemplo no primeiro boot.
if [ ! -f .env ]; then
    cp .env.example .env
fi

# EN: Generate the application key if missing. / PT: Gera a chave da aplicação se faltar.
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# EN: Wait until the database accepts connections (up to ~60s).
# PT: Aguarda o banco aceitar conexões (até ~60s).
echo "Waiting for database / Aguardando o banco..."
ATTEMPTS=0
until php -r '
    $host = getenv("DB_HOST") ?: "db";
    $port = getenv("DB_PORT") ?: "3306";
    $db   = getenv("DB_DATABASE");
    $user = getenv("DB_USERNAME");
    $pass = getenv("DB_PASSWORD");
    try { new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass); exit(0); }
    catch (Throwable $e) { exit(1); }
' 2>/dev/null; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ "$ATTEMPTS" -ge 30 ]; then
        echo "Database not reachable / Banco inacessível. Abortando." >&2
        exit 1
    fi
    sleep 2
done
echo "Database is ready / Banco pronto."

# EN: Run migrations and seed the default admin user (idempotent).
# PT: Roda migrations e popula o usuário admin padrão (idempotente).
php artisan migrate --force --seed

# EN: Clear any stale cached config/routes/views.
# PT: Limpa config/rotas/views cacheados que estejam desatualizados.
php artisan optimize:clear

# EN: In the background: import Pokémon now (skipped if fresh), then run the
#     scheduler so the data is refreshed periodically (see routes/console.php).
#     This lets the web server start immediately while data loads progressively.
# PT: Em background: importa Pokémon agora (pulado se atualizado) e então roda o
#     scheduler para atualizar os dados periodicamente (ver routes/console.php).
#     Assim o servidor web sobe na hora enquanto os dados carregam aos poucos.
(
    php artisan pokemon:sync || echo "WARN: pokemon:sync failed; scheduler will retry / o scheduler tentará de novo"
    php artisan schedule:work
) >/proc/1/fd/1 2>&1 &

exec "$@"
