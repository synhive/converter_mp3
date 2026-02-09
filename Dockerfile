FROM php:8.2-apache

# Installation des extensions et des dépendances
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

RUN apt-get update && apt-get install -y \
    ffmpeg \
    python3 \
    python3-pip \
    python3-venv \
    libzip-dev \
    zip \
    unzip \
    cron \
    && apt-get clean

RUN docker-php-ext-install zip

# Création de l'environnement Python
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Installation de yt-dlp dans le venv
RUN pip install --no-cache-dir yt-dlp

# Copier les fichiers de l'application
COPY app/ /var/www/html/

# Supprimer le warning Apache ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Modification des permissions pour Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Script pour nettoyer le dossier "downloads"
RUN echo "#!/bin/bash\nrm -rf /var/www/html/downloads/*" > /usr/local/bin/clean_downloads.sh && \
    chmod +x /usr/local/bin/clean_downloads.sh

# Script pour mettre à jour yt-dlp
RUN echo "#!/bin/bash\n/opt/venv/bin/pip install -U yt-dlp > /var/log/yt-dlp-update.log 2>&1" > /usr/local/bin/update_ytdlp.sh && \
    chmod +x /usr/local/bin/update_ytdlp.sh

# Configuration des tâches cron
RUN echo "# Nettoyage des téléchargements à 6h30\n30 6 * * * root /usr/local/bin/clean_downloads.sh > /dev/null 2>&1\n# Mise à jour de yt-dlp tous les jours à 3h\n0 3 * * * root /usr/local/bin/update_ytdlp.sh" > /etc/cron.d/maintenance
RUN chmod 0644 /etc/cron.d/maintenance

# Création du fichier log pour cron
RUN touch /var/log/cron.log /var/log/yt-dlp-update.log

# Exposer le port pour Apache
EXPOSE 80

# Commande de démarrage : démarrer cron et Apache
CMD ["sh", "-c", "cron && apache2-foreground"]