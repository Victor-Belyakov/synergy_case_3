#!/bin/bash

echo "🚀 Запуск Приложения   "
echo ""

if ! command -v docker &> /dev/null; then
    echo "❌ Docker не установлен. Установите Docker и попробуйте снова."
    exit 1
fi

if ! docker compose version &> /dev/null && ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose не установлен. Установите Docker Compose и попробуйте снова."
    exit 1
fi

echo "📦 Сборка и запуск контейнеров..."
docker compose up -d --build

echo "⏳ Ожидание готовности базы данных..."
sleep 5

echo "📚 Установка Composer зависимостей..."
docker compose exec -T php composer install --no-interaction

echo "🗄️  Выполнение миграций базы данных..."
docker compose exec -T php php yii migrate --interactive=0

echo ""
echo "✅ Готово! Приложение доступно по адресу:"
echo "   🌐 http://localhost:8080"
echo ""
echo "📝 Полезные команды:"
echo "   docker compose logs -f    - просмотр логов"
echo "   docker compose down       - остановка"
echo "   docker compose restart    - перезапуск"
echo ""

