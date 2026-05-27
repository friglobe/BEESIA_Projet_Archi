# =====================================================
# Dockerfile - Apache + PHP 8.1 pour JUNIA Seance 3
# =====================================================
# Basé sur l'image officielle PHP avec Apache

FROM php:8.1-apache

# =====================================================
# Métadonnées
# =====================================================
LABEL maintainer="JUNIA Architecture Web"
LABEL description="Environnement PHP/Apache pour Seance 3"
LABEL version="1.0"

# =====================================================
# Configuration du système
# =====================================================

# Définir le fuseau horaire
ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Mise à jour du système
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
    curl \
    wget \
    git \
    vim \
    nano \
    unzip \
    zip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# =====================================================
# Extensions PHP nécessaires
# =====================================================

# Extension MySQL (mysqli)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Extension GD (images) - optionnelle
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd || true

# Extensions utiles - installer individuellement pour robustesse
RUN docker-php-ext-install zip || true
RUN docker-php-ext-install intl || true
RUN docker-php-ext-install opcache || true

# PECL extensions - optionnels
RUN pecl install xdebug && docker-php-ext-enable xdebug || true

# =====================================================
# Configuration PHP
# =====================================================

# Copier fichier de configuration personnalisé
COPY config/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configuration xdebug (optionnel, pour débogage)
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# =====================================================
# Configuration Apache
# =====================================================

# Activer les modules Apache nécessaires
RUN a2enmod rewrite && \
    a2enmod headers && \
    a2enmod ssl && \
    a2enmod expires && \
    a2enmod deflate

# Copier configuration Apache personnalisée
COPY config/apache.conf /etc/apache2/sites-available/000-default.conf

# SSL config (optionnel, pour HTTPS)
# COPY config/apache-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Autoriser .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# =====================================================
# Permissions et répertoires
# =====================================================

# Créer répertoires de travail
RUN mkdir -p /var/www/html && \
    mkdir -p /var/log/apache2 && \
    mkdir -p /var/www/html/uploads

# Permissions pour uploads
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/uploads

# =====================================================
# Composer (optionnel, pour dépendances PHP)
# =====================================================

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# =====================================================
# Script d'initialisation
# =====================================================

# Copier script de démarrage
COPY config/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# =====================================================
# Exposition des ports
# =====================================================

EXPOSE 80 443

# =====================================================
# Répertoire de travail
# =====================================================

WORKDIR /var/www/html

# =====================================================
# Point d'entrée
# =====================================================

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]

# =====================================================
# Healthcheck
# =====================================================

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1
