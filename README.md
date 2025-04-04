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

```cmd
docker-compose up
composer install
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

```

## 🚀 Роуты

POST http://localhost:8000/api/register
```
{
    "email": "user@mail.com",
    "password": "password",
    "name": "user"
}
```

POST http://localhost:8000/api/login
```
{
    "username": "user@mail.com",
    "password": "password"
}
```

GET http://localhost:8000/me
```
Authorization: Bearer ******
```

PUT http://localhost:8000/me
```
Authorization: Bearer ******

{
    "email": "user@mail.com",
    "password": "password",
    "name": "user"
}
```

DELETE http://localhost:8000/me
```
Authorization: Bearer ******
```
