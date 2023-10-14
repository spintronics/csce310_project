FROM php:8.1.25RC1-apache-bullseye

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY . .
