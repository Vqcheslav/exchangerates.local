# Курсы валют по данным банка России

### Инструкция по установке проекта на Linux:

- Необходимо предварительно установить:
  - [Docker](https://docs.docker.com/desktop/install/linux-install/)
  - [Docker-Compose](https://docs.docker.com/compose/install/)
  - [Composer](https://getcomposer.org/download/)
  - [Symfony](https://symfony.com/download)

- Скачать zip-архив проекта по ссылке https://github.com/QueflyQuefly/exchangerates.local/archive/refs/heads/main.zip 
(можно также воспользоваться командой `git clone https://github.com/QueflyQuefly/exchangerates.local.git`)
и разархивировать проект в любую директорию на локальном диске
- Перейти в корневую папку проекта и выполнить команду (на вопрос о выполнении миграции нажать Enter)
`sudo service docker start; docker-compose up -d; composer install; symfony console doctrine:migrations:migrate; symfony server:start`
- Теперь сайт доступен по адресу ' http://localhost:8000/ '

Сайт написан на фреймворке Symfony 6.0, в качестве БД используется MySQL 8.0, 
работа с которой ведется с помощью Doctrine, оформлен с помощью Twig 3 и Bootstrap 5
