# Пример сервиса на Laravel для отправки сообщений и авторизации пользователя через Telegram

### Для быстрого запуска и тестирования при наличии task:

#### Task документация:
https://taskfile.dev

```shell
task init
```

```shell
task prep
```

### Команды

Посмотреть доступные команды
```shell
task -a
```

### Для быстро запуска docker:

```shell
docker compose -f compose.local.yaml build --build-arg UID=${BUILD_ARG_UID:-$(id -u)} --build-arg GID=${BUILD_ARG_GID:-$(id -g)}
```


### Известные проблемы
* На MacBook сборка образа php может падать с ошибкой `addgroup: gid '20' in use`
  Для решения проблемы нужно указать другой id группы пользователя через переменную BUILD_ARG_GID, в командной строке или в корневом файле .env
```shell
BUILD_ARG_GID=1000 task init
```
```dotenv
#.env

BUILD_ARG_GID=1000
```

## Входные точки в сервисе

### 1. Маршрут /v1/api/telegram/webhook
* Предназначен для того чтобы ваш сервис мог отлавливать ответы от telegram
* Для того чтобы прикрепить бота к вашему вебхуку необходимо инициализировать curl команду пример:
```shell
curl -F "url=https://YOUR_DOMAIN/api/v1/telegram/webhook" \
     https://api.telegram.org/botYOUR_TOKEN/setWebhook
```

### 2. Маршрут /v1/api/telegram/init-data
* Предназначен для того чтобы ваш сервис мог проверить пользователя и вернуть ему токен по которому он впоследствии войдёт в приложение
