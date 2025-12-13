# =========================================================
# 🐘 Base Image
# =========================================================
FROM php:8.1.10-fpm

# Set working directory
WORKDIR /app

# =========================================================
# 🧰 System dependencies
# =========================================================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    gnupg \
    zip \
    unzip \
    vim \
    telnet \
    iputils-ping \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    unixodbc-dev \
    apt-transport-https \
    ca-certificates && \
    rm -rf /var/lib/apt/lists/*

# =========================================================
# 🧩 PHP Extensions
# =========================================================
# Copy PHP extension installer from mlocati (optional but handy)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install commonly used PHP extensions
RUN install-php-extensions sockets && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# =========================================================
# 🗄️ Microsoft SQL Server (MSSQL) ODBC + PHP extensions
# Works for Ubuntu (18–22) and Debian (bookworm/trixie)
# =========================================================
RUN apt-get update && apt-get install -y curl gnupg apt-transport-https ca-certificates && \
    curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft.gpg && \
    distro=$(grep -oP '(?<=^ID=)[a-z]+' /etc/os-release | tr -d '"') && \
    codename=$(grep -oP '(?<=VERSION_CODENAME=)[a-z]+' /etc/os-release) && \
    if [ "$distro" = "ubuntu" ]; then \
        echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/ubuntu/${codename}/prod ${codename} main" > /etc/apt/sources.list.d/microsoft-prod.list; \
    else \
        echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/microsoft-prod.list; \
    fi && \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18 unixodbc-dev && \
    echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP SQLSRV extensions
RUN pecl install sqlsrv pdo_sqlsrv && \
    docker-php-ext-enable sqlsrv pdo_sqlsrv

# =========================================================
# 🎼 Composer (v2.8.4)
# =========================================================
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.8.4 --install-dir=/usr/local/bin --filename=composer

# =========================================================
# 📦 Laravel App Setup
# =========================================================
COPY . .

# Install PHP dependencies (ignore zip extension if unavailable)
RUN composer install --no-dev --no-interaction --prefer-dist --ignore-platform-req=ext-zip

# Laravel optimization (safe to run even if cache not set)
RUN php artisan config:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan cache:clear || true && \
    php artisan optimize || true && \
    composer dump-autoload -o || true

# =========================================================
# 🧹 Cleanup
# =========================================================
RUN rm -rf bootstrap/cache/*.php && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# =========================================================
# 🚀 Expose & Run
# =========================================================
EXPOSE 9000
CMD ["php-fpm"]
