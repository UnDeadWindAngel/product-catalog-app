# 🛍️ Product Catalog App

Полнофункциональное веб-приложение — каталог товаров с административной панелью на Laravel + Vue.js 3.

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 📋 Оглавление
- [Особенности](#-особенности)
- [Технологический стек](#-технологический-стек)
- [Быстрый старт](#-быстрый-старт)
- [Структура проекта](#-структура-проекта)
- [API документация](#-api-документация)
- [Скриншоты](#-скриншоты)
- [Разработка](#-разработка)

## ✨ Особенности

### Бэкенд (Laravel 12)
- ✅ RESTful API с пагинацией и фильтрацией
- ✅ Аутентификация через Laravel Sanctum
- ✅ CRUD операции для товаров и категорий
- ✅ Валидация с Form Request
- ✅ Ресурсы (Resources) для форматирования JSON
- ✅ PostgreSQL с отношениями один-ко-многим

### Фронтенд (Vue.js 3 + Inertia.js)
- ✅ Composition API (ref, reactive, computed)
- ✅ Публичный каталог с фильтрацией по категориям
- ✅ Административная панель с аутентификацией
- ✅ Формы создания/редактирования с валидацией
- ✅ Пагинация и обработка ошибок
- ✅ Современный UI с Tailwind CSS

### Инфраструктура
- ✅ Полное Docker окружение
- ✅ Многоконтейнерная архитектура
- ✅ Автоматическая сборка и деплой
- ✅ Готово к продакшену

## 🛠 Технологический стек

| Компонент | Технология |
|-----------|------------|
| **Бэкенд** | Laravel 12, PHP 8.2, PostgreSQL |
| **Фронтенд** | Vue.js 3, Inertia.js, Tailwind CSS |
| **Аутентификация** | Laravel Sanctum |
| **Контейнеризация** | Docker, Docker Compose |
| **Веб-сервер** | Nginx |
| **Сборка фронтенда** | Vite, Node.js 20 |

## 🚀 Быстрый старт

### Предварительные требования
- [Docker](https://www.docker.com/get-started) и Docker Compose
- Git

### Установка и запуск

```bash
# 1. Клонировать репозиторий
git clone https://github.com/YOUR-USERNAME/product-catalog-app.git
cd product-catalog-app

# 2. Запустить все сервисы через Docker
docker-compose up -d --build

# 3. Установить зависимости Laravel и выполнить миграции
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed

# 4. Установить зависимости Node.js
docker-compose exec node npm install

# 5. Открыть приложение в браузере
# Приложение будет доступно по адресу: http://localhost:8080

# Учётные данные для входа
Email: admin@example.com
Пароль: password
```
## 📁 Структура проекта

```
product-catalog-app/
│
├── 📂 docker/
│   └── ... Docker-конфигурации
│
├── 📂 app/
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   └── 📂 API/
│   │   └── 📂 Resources/
│   └── 📂 Models/
│
├── 📂 database/
│   ├── 📂 migrations/
│   └── 📂 seeders/
│
├── 📂 resources/
│   ├── 📂 js/
│   │   └── 📂 Pages/
│   └── 📂 views/
│
├── 📂 routes/
├── 📄 docker-compose.yml
├── 📄 Dockerfile
└── 📄 README.md
```
## 🔌 API Документация

### Базовый URL
```
http://localhost:8080/api
```

### 📋 Эндпоинты

#### 🔓 Публичные (не требуют аутентификации)

| Метод | Эндпоинт | Описание |
|-------|----------|----------|
| `GET` | `/products` | Получение списка товаров с пагинацией |
| `GET` | `/products/{id}` | Получение детальной информации о товаре |
| `GET` | `/categories` | Получение списка всех категорий |

#### 🔐 Защищённые (требуют Bearer Token)

| Метод | Эндпоинт | Описание |
|-------|----------|----------|
| `POST` | `/login` | Аутентификация пользователя и получение токена |
| `POST` | `/products` | Создание нового товара |
| `PUT` | `/products/{id}` | Обновление информации о товаре |
| `DELETE` | `/products/{id}` | Удаление товара |
| `POST` | `/logout` | Выход из системы (инвалидация токена) |

### 🚀 Примеры запросов

#### 1. Получение токена авторизации
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

**Ответ:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

#### 2. Создание нового товара (требуется токен)
```bash
curl -X POST http://localhost:8080/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -d '{
    "name": "Новый товар",
    "description": "Описание нового товара",
    "price": 99.99,
    "category_id": 1,
    "stock": 100
  }'
```

#### 3. Получение списка товаров
```bash
curl -X GET http://localhost:8080/api/products
```

#### 4. Пагинация и фильтрация товаров
```bash
# Получение второй страницы с 10 товарами на странице
curl -X GET "http://localhost:8080/api/products?page=2&per_page=10"

# Фильтрация по категории
curl -X GET "http://localhost:8080/api/products?category_id=1"

# Поиск по названию
curl -X GET "http://localhost:8080/api/products?search=телефон"
```

### 📝 Формат ответов

#### Успешный ответ
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Пример товара",
    "price": 99.99
  },
  "message": "Операция выполнена успешно"
}
```

#### Ответ с ошибкой
```json
{
  "success": false,
  "message": "Ошибка аутентификации",
  "errors": {
    "email": ["Поле email обязательно для заполнения"]
  }
}
```

#### Пагинированный ответ
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Товар 1"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  },
  "links": {
    "first": "http://localhost:8080/api/products?page=1",
    "last": "http://localhost:8080/api/products?page=5",
    "next": "http://localhost:8080/api/products?page=2"
  }
}
```

### 🔑 Аутентификация

Для работы с защищёнными эндпоинтами необходимо:
1. Получить токен через `/api/login`
2. Добавить заголовок в каждый запрос:
   ```
   Authorization: Bearer {ваш_токен}
   ```
3. Токен действителен 1 час
4. Для обновления токена используйте `/api/refresh`
5. Для выхода из системы используйте `/api/logout`

### ⚠️ Коды ответов

| Код | Описание |
|-----|----------|
| `200` | Успешный запрос |
| `201` | Успешно создан |
| `400` | Неверный запрос |
| `401` | Не авторизован |
| `403` | Доступ запрещен |
| `404` | Ресурс не найден |
| `422` | Ошибка валидации |
| `500` | Внутренняя ошибка сервера |

## 🖼 Скриншоты

### Главная страница каталога
![Главная страница каталога](https://docs/screenshots/home.png)

### Административная панель
![Административная панель](https://docs/screenshots/admin.png)

### Форма создания товара
![Форма создания товара](https://docs/screenshots/form.png)

---

## 👨‍💻 Разработка

### 🐳 Команды Docker

```bash
# Запуск всех сервисов
docker-compose up -d

# Остановка всех сервисов
docker-compose down

# Пересборка и запуск
docker-compose up -d --build

# Просмотр логов
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql

# Выполнение команд в контейнере Laravel
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker

# Выполнение команд в контейнере Node.js
docker-compose exec node npm run dev
docker-compose exec node npm run build
```

### 💻 Локальная разработка

#### Работа с Laravel
```bash
# Создание миграции
docker-compose exec app php artisan make:migration create_products_table

# Создание модели с миграцией
docker-compose exec app php artisan make:model Product -m

# Создание API контроллера
docker-compose exec app php artisan make:controller API/ProductController --api

# Создание ресурса для API
docker-compose exec app php artisan make:resource ProductResource

# Запуск сидов
docker-compose exec app php artisan db:seed

# Генерация тестовых данных
docker-compose exec app php artisan db:seed --class=ProductSeeder

# Очистка кеша
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

#### Работа с Vue.js
```bash
# Установка зависимостей
docker-compose exec node npm install

# Запуск в режиме разработки
docker-compose exec node npm run dev

# Сборка для production
docker-compose exec node npm run build

# Запуск линтера
docker-compose exec node npm run lint

# Запуск тестов
docker-compose exec node npm run test
```

#### Работа с базой данных
```bash
# Вход в интерактивную консоль PostgreSQL (psql) внутри контейнера
docker-compose exec pgsql psql -U laravel -d laravel

# Экспорт базы данных (выполняется на хосте, вне контейнера)
docker-compose exec -T pgsql pg_dump -U laravel laravel > backup_$(date +%Y%m%d).sql

# Импорт базы данных из файла backup.sql
docker-compose exec -T pgsql psql -U laravel -d laravel < backup.sql

**Примечание к командам:**
- `-U laravel` — указывает пользователя БД.
- `-d laravel` — указывает имя базы данных.
- Флаг `-T` в `docker-compose exec` отключает выделение TTY, что важно для перенаправления потоков ввода/вывода.
- `pg_dump` — стандартная утилита PostgreSQL для создания дампа.
```

#### Полезные команды
```bash
# Проверка статуса контейнеров
docker-compose ps

# Просмотр использования ресурсов
docker stats

# Очистка Docker
docker system prune -a

# Перезапуск конкретного сервиса
docker-compose restart app
```

---

## 📝 Лицензия

Этот проект лицензирован под **MIT License** - смотрите файл [LICENSE](LICENSE) для деталей.

**Краткое описание лицензии MIT:**
- ✅ Можно свободно использовать, копировать, модифицировать
- ✅ Можно использовать в коммерческих целях
- ✅ Можно распространять
- ✅ Обязательно включать копию лицензии и уведомление об авторских правах
- ❌ Нет гарантий, автор не несет ответственности

---

## 🤝 Вклад в проект

Мы приветствуем вклад в развитие проекта! Вот как вы можете помочь:

### Процесс внесения изменений

1. **Форкните репозиторий**
   ```bash
   # Нажмите кнопку "Fork" в GitHub
   # Затем клонируйте ваш форк локально
   git clone https://github.com/YOUR-USERNAME/product-catalog-app.git
   ```

2. **Создайте ветку для новой функции**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Зафиксируйте изменения**
   ```bash
   git add .
   git commit -m 'feat: добавить новую функцию'
   ```

4. **Запушьте ветку**
   ```bash
   git push origin feature/amazing-feature
   ```

5. **Откройте Pull Request**

### Правила коммитов

Используйте [Conventional Commits](https://www.conventionalcommits.org/):
- `feat:` - новая функциональность
- `fix:` - исправление ошибки
- `docs:` - изменения в документации
- `style:` - форматирование, отсутствующие точки с запятой и т.д.
- `refactor:` - рефакторинг кода
- `test:` - добавление или исправление тестов
- `chore:` - обновление сборки, настройки и т.д.

### Руководство по стилю кода

- Следуйте стандартам PSR для PHP
- Используйте ESLint для JavaScript
- Пишите комментарии на русском языке
- Добавляйте тесты для новой функциональности
- Обновляйте документацию при изменении API

### Сообщение о проблемах

При создании issue укажите:
1. Версию приложения
2. Шаги для воспроизведения
3. Ожидаемое поведение
4. Фактическое поведение
5. Скриншоты (если применимо)

### Код ревью

- Все изменения проходят код ревью
- Минимум 1 approve от мейнтейнера
- Тесты должны проходить успешно
- Документация должна быть обновлена

**Спасибо за ваш вклад!** 🎉

---

## 📞 Контакты

Если у вас есть вопросы или предложения:

- **Создайте Issue** в GitHub репозитории
- **Напишите на email**: UnDeadWindAngel@yandex.ru

---

## 🙏 Благодарности

- Laravel сообществу за прекрасный фреймворк
- Vue.js за отличный фронтенд фреймворк
- Docker за удобные контейнеры
- Всем контрибьюторам проекта

---

⭐ **Если проект был полезен, поставьте звезду на GitHub!** ⭐
