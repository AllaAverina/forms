# Формы регистрации и авторизации и страница профиля
Решение тестового задания().

## Функционал
* Регистрация пользователя 
* Зарегистрированный пользователь может отредактировать свой профиль

## Требования
* [PHP 8.1+](https://www.php.net/)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)
* [Nginx](https://nginx.org/)
* [Docker](https://www.docker.com/), [Docker Compose](https://docs.docker.com/compose/)
* [Google reCAPTCHA v2](https://developers.google.com/recaptcha/docs/display)

## Установка с помощью Docker
1. Клонируйте этот репозиторий:
```sh
git clone https://github.com/AllaAverina/forms
```
2. Перейдите в папку проекта и выполните:
```sh
docker compose up -d --build
```
3. Затем выполните:
```sh
composer dump-autoload
```
4. Установите ключи [reCAPTCHA v2](https://www.google.com/recaptcha/admin/create) в файле config/config.php:
5. Откройте в браузере http://localhost:8000/
6. Для остановки контейнеров используйте:
```sh
docker compose down
```
