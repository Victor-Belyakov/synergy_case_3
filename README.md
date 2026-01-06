# Блог-приложение на Yii2

Веб-приложение для блога с полным функционалом управления постами, пользователями, подписками, комментариями и тегами.

## Требования

- PHP >= 7.4.0
- Composer
- SQLite (или любая другая БД, поддерживаемая Yii2)

## Установка

```bash
# Запуск одной командой
./start.sh

# Или вручную:
docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php php yii migrate
```

Откройте браузер: `http://localhost:8080`
