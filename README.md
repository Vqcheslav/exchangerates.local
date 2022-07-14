# exchangerates.local

Курсы валют по данным банка Росссии

Инструкция по установке проекта:

  1) Необходимо предварительно установить PHP 8.1, Symfony, Docker и Docker-Compose
    1.1) https://www.php.net/manual/ru/install.php
    1.2) https://symfony.com/doc/current/setup.html
    1.3) https://docs.docker.com/desktop/install/linux-install/  и https://docs.docker.com/compose/install/
  2) Скачать zip-архив проекта по ссылке https://github.com/QueflyQuefly/exchangerates.local/archive/refs/heads/main.zip 
  (можно также воспользоваться командой ' git clone https://github.com/QueflyQuefly/exchangerates.local.git ')
  и разархивировать проект в любую директорию на локальном диске
  3) Перейти в корневую папку проекта и в консоли запустить команду ' docker-compose up -d '
  4) Затем в корневой папке проекта запустить ' symfony server:start '
  5) Теперь сайт доступен по адресу ' http://localhost:8000/ '
