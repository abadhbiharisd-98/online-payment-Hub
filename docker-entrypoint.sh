#!/bin/bash
set -e

# Case 1: Internal MariaDB (Local/Fallback mode)
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "localhost" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
    echo "Starting internal MariaDB service..."
    service mariadb start

    # Wait for MariaDB to start
    until mysqladmin ping --silent; do
        sleep 1
    done

    # Check if spms_db exists, if not initialize it
    if ! mysql -e "USE spms_db;" 2>/dev/null; then
        echo "Creating and importing spms_db database..."
        mysql -e "CREATE DATABASE IF NOT EXISTS spms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
        mysql -e "CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY '';"
        mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION; FLUSH PRIVILEGES;"
        if [ -f "/var/www/html/spms/db/spms_db.sql" ]; then
            mysql spms_db < /var/www/html/spms/db/spms_db.sql
            echo "spms_db.sql imported successfully into internal MariaDB!"
        fi
    else
        echo "Database spms_db already exists."
    fi

# Case 2: External Database (Aiven MySQL / Cloud Database)
else
    echo "Configured for external database: $DB_HOST:$DB_PORT (Database: $DB_NAME)"
    
    # Try auto-importing spms_db.sql to Aiven if tables don't exist yet
    if [ -f "/var/www/html/spms/db/spms_db.sql" ] && [ -n "$DB_NAME" ] && [ -n "$DB_USER" ]; then
        echo "Checking if database tables exist on external database..."
        TABLES=$(mysql -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USER" -p"$DB_PASSWORD" --ssl-mode=REQUIRED -e "SHOW TABLES LIKE 'admin_list';" "$DB_NAME" 2>/dev/null || true)
        
        if [ -z "$TABLES" ]; then
            echo "No existing tables found. Importing spms_db.sql to external database ($DB_NAME)..."
            mysql -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USER" -p"$DB_PASSWORD" --ssl-mode=REQUIRED "$DB_NAME" < /var/www/html/spms/db/spms_db.sql 2>/dev/null || echo "Notice: Could not auto-import SQL dump. Please ensure credentials and database exist on Aiven."
            echo "External database setup check completed."
        else
            echo "Database tables already present on external database."
        fi
    fi
fi

# Render dynamically assigns PORT (default 80 or 10000)
if [ -n "$PORT" ]; then
    echo "Configuring Apache to listen on port $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf
fi

echo "Starting Apache Web Server..."
exec apache2-foreground
