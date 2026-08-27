#!/usr/bin/env bash
# artirdim — Laravel'i Emergent preview portu (3000) üzerinde servis eder.
# MariaDB + Redis'i (yoksa) başlatır, ardından php artisan serve'i foreground çalıştırır.
set -e
cd "$(dirname "$0")"

# --- MariaDB ---
mkdir -p /var/lib/mysql /var/run/mysqld
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld 2>/dev/null || true
[ -d /var/lib/mysql/mysql ] || mariadb-install-db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1
pgrep -x mariadbd >/dev/null || (nohup mariadbd --user=mysql --datadir=/var/lib/mysql >/tmp/mariadb.log 2>&1 &)

# --- Redis ---
pgrep -x redis-server >/dev/null || (nohup redis-server >/tmp/redis.log 2>&1 &)

# DB hazır olana kadar bekle
for i in $(seq 1 30); do mysqladmin ping >/dev/null 2>&1 && break; sleep 1; done

exec php artisan serve --host=0.0.0.0 --port=3000
