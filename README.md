---

## 📦 Состав проекта

- ✅ Symfony 6+
- ✅ JWT аутентификация (LexikJWTAuthenticationBundle)
- 🐳 Docker (Nginx, PHP-FPM, PostgreSQL)

---

## ⚙️ Переменные окружения

Перед запуском убедитесь, что файл `.env` содержит следующие переменные:

```env
# PostgreSQL
POSTGRES_VERSION=16
POSTGRES_DB=db_name
POSTGRES_PASSWORD=db_password
POSTGRES_USER=db_user

# JWT (важно для генерации токенов)
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_jwt_passphrase
```

## 🚀 Запуск
docker-compose up
composer install
