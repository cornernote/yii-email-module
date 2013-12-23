#!/bin/sh

mysql -u root -e 'CREATE SCHEMA `email` CHARACTER SET utf8 COLLATE utf8_general_ci; GRANT ALL ON `email`.* TO test@localhost IDENTIFIED BY "test"; FLUSH PRIVILEGES;'