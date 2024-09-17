FROM php:8.2-apache

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

RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

RUN pip install --no-cache-dir yt-dlp

COPY app/ /var/www/html/

RUN echo "#!/bin/bash\nrm -rf /var/www/html/downloads/*" > /usr/local/bin/clean_downloads.sh && \
    chmod +x /usr/local/bin/clean_downloads.sh

RUN echo "30 6 * * * root /usr/local/bin/clean_downloads.sh > /dev/null 2>&1" > /etc/cron.d/clean_downloads

RUN chmod 0644 /etc/cron.d/clean_downloads

RUN touch /var/log/cron.log

EXPOSE 1605

CMD ["sh", "-c", "cron && apache2-foreground"]
