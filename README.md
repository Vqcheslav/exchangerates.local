# Курсы валют по данным банка России

### Инструкция по установке проекта на Linux:

- Необходимо предварительно установить:
   1) PHP 8.1:  https://www.php.net/manual/ru/install.php
   2) Symfony:  https://symfony.com/download
   3) Docker:  https://docs.docker.com/desktop/install/linux-install/  
   4) Docker-Compose: https://docs.docker.com/compose/install/
   5) Composer: https://getcomposer.org/download/
   6) Yarn: https://classic.yarnpkg.com/lang/en/docs/install/
- Скачать zip-архив проекта по ссылке https://github.com/QueflyQuefly/exchangerates.local/archive/refs/heads/main.zip 
  (можно также воспользоваться командой ' git clone https://github.com/QueflyQuefly/exchangerates.local.git ')
  и разархивировать проект в любую директорию на локальном диске
- Перейти в корневую папку проекта и выполнить команду ' sudo service docker start; docker-compose up -d; composer install; php bin/console doctrine:migrations:migrate; yarn install; yarn dev; symfony server:start ' - на вопрос о выполнении миграции нажать Enter
- Теперь сайт доступен по адресу ' http://localhost:8000/ '

Сайт написан на фреймворке Symfony 6.0, в качестве БД используется MySQL 8.0, работа с которой ведется с помощью Doctrine, оформлен с помощью Twig 3 и Bootstrap 5
