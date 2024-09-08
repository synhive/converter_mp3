FROM php:8.2-apache

# Installer les extensions PHP nécessaires et activer le module rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

# Installer les dépendances nécessaires, y compris libzip-dev pour l'extension zip
RUN apt-get update && apt-get install -y \
    ffmpeg \
    python3 \
    python3-pip \
    python3-venv \
    python3-requests \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean

# Installer l'extension zip pour PHP
RUN docker-php-ext-install zip

# Configurer l'environnement Python
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Installer yt-dlp via pip
RUN pip install --no-cache-dir yt-dlp

# Copier le code de l'application dans le conteneur
COPY app/ /var/www/html/

# Exposer le port 1605
EXPOSE 1605