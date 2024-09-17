FROM php:8.2-apache

# Installation des extensions et des dépendances
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

RUN apt-get update && apt-get install -y \
    ffmpeg \
    python3 \
    python3-pip \
    python3-venv \
    python3-requests \
    libzip-dev \
    zip \
    unzip \
    cron \
    && apt-get clean

RUN docker-php-ext-install zip

# Création de l'environnement Python
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Installation de yt-dlp
RUN pip install --no-cache-dir yt-dlp

# Copier les fichiers de l'application
COPY app/ /var/www/html/

# Modification des permissions pour Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Script pour nettoyer le dossier "downloads"
RUN echo "#!/bin/bash\nrm -rf /var/www/html/downloads/*" > /usr/local/bin/clean_downloads.sh && \
    chmod +x /usr/local/bin/clean_downloads.sh

# Configuration de la tâche cron
RUN echo "30 6 * * * root /usr/local/bin/clean_downloads.sh > /dev/null 2>&1" > /etc/cron.d/clean_downloads
RUN chmod 0644 /etc/cron.d/clean_downloads

# Création du fichier log pour cron
RUN touch /var/log/cron.log

# Exposer le port pour Apache
EXPOSE 1605

# Commande de démarrage : démarrer cron et Apache
CMD ["sh", "-c", "cron && apache2-foreground"]
