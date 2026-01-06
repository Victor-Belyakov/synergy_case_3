<?php

// Определяем, работаем ли мы в Docker
$isDocker = getenv('DOCKER_CONTAINER') === 'true' || file_exists('/.dockerenv');

if ($isDocker) {
    // Конфигурация для Docker (PostgreSQL)
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=db;port=5432;dbname=blog_db',
        'username' => 'blog_user',
        'password' => 'blog_password',
        'charset' => 'utf8',
        'schemaMap' => [
            'pgsql' => [
                'class' => 'yii\db\pgsql\Schema',
                'defaultSchema' => 'public',
            ],
        ],
    ];
} else {
    // Конфигурация для локальной разработки (SQLite)
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'sqlite:' . dirname(__DIR__) . '/runtime/blog.db',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
    ];
}

