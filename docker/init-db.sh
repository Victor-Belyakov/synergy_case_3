#!/bin/bash
# Скрипт для инициализации базы данных

echo "Ожидание готовности PostgreSQL..."
until docker-compose exec -T db pg_isready -U blog_user -d blog_db; do
  sleep 1
done

echo "База данных готова!"


