#!/bin/bash

if ! grep -q "127.0.0.1 station-simulator.local" /etc/hosts; then
    echo "127.0.0.1 station-simulator.local" >> /etc/hosts
fi

# Обновляем IP-адрес контейнера database
DB_IP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' station-simulator-db)
# Проверяем, есть ли запись в файле hosts
if grep -q "db.station-simulator.local" /etc/hosts; then
  # Если запись уже существует, заменяем ее на новый IP-адрес
  sed -i "s/^.*db.station-simulator.local$/$DB_IP db.station-simulator.local/" /etc/hosts
else
  # Если записи нет, добавляем новую строку в файл hosts
  echo "$DB_IP db.station-simulator.local" >> /etc/hosts
fi