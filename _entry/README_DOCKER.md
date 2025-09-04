# MyTasks - Docker Setup

## 🐳 Запуск с Docker

### Предварительные требования
- Docker
- Docker Compose

### Быстрый старт

1. **Перейдите в папку с Docker конфигурацией:**
```bash
cd _docker
```

2. **Запустите контейнеры:**
```bash
docker-compose up -d
```

3. **Установите зависимости Laravel:**
```bash
docker exec -it task_app composer install
```

4. **Скопируйте .env файл:**
```bash
docker exec -it task_app cp .env.example .env
```

5. **Сгенерируйте ключ приложения:**
```bash
docker exec -it task_app php artisan key:generate
```

6. **Запустите миграции:**
```bash
docker exec -it task_app php artisan migrate
```

7. **Заполните базу тестовыми данными:**
```bash
docker exec -it task_app php artisan db:seed
```

### Доступ к приложению

- **Веб-приложение:** http://localhost:8000
- **API:** http://localhost:8000/api/tasks
- **MySQL:** localhost:3306
  - База данных: `mytasks`
  - Пользователь: `mytasks`
  - Пароль: `secret`

### Полезные команды

**Остановить контейнеры:**
```bash
docker-compose down
```

**Перезапустить контейнеры:**
```bash
docker-compose restart
```

**Просмотр логов:**
```bash
docker-compose logs -f
```

**Выполнить команду в контейнере приложения:**
```bash
docker exec -it task_app php artisan [command]
```

**Подключиться к MySQL:**
```bash
docker exec -it mysql mysql -u mytasks -p mytasks
```

### API Endpoints

#### GET /api/tasks
Получить все задачи
```bash
curl http://localhost:8000/api/tasks
```

#### POST /api/tasks
Создать новую задачу
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Новая задача",
    "description": "Описание задачи",
    "priority": "high",
    "due_date": "2024-12-31 23:59:59"
  }'
```

#### GET /api/tasks/{id}
Получить задачу по ID
```bash
curl http://localhost:8000/api/tasks/{id}
```

#### PUT /api/tasks/{id}
Обновить задачу
```bash
curl -X PUT http://localhost:8000/api/tasks/{id} \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed"
  }'
```

#### DELETE /api/tasks/{id}
Удалить задачу
```bash
curl -X DELETE http://localhost:8000/api/tasks/{id}
```

### Структура базы данных

#### Таблица `tasks`
- `id` (string, primary key) - UUID задачи
- `title` (string) - Название задачи
- `description` (text) - Описание задачи
- `status` (enum) - Статус: todo, in_progress, completed, cancelled
- `priority` (enum) - Приоритет: low, medium, high, urgent
- `due_date` (datetime, nullable) - Срок выполнения
- `created_at` (timestamp) - Дата создания
- `updated_at` (timestamp) - Дата обновления

### Тестовые данные

После выполнения `php artisan db:seed` в базе будут созданы 5 тестовых задач с разными статусами и приоритетами.

### Разработка

Для разработки рекомендуется:
1. Использовать `docker-compose up -d` для запуска инфраструктуры
2. Работать с кодом локально (файлы монтируются в контейнер)
3. Использовать `docker exec -it task_app` для выполнения Laravel команд
