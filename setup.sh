#!/bin/bash
# Script para correr en el deploy de Railway

echo "--- Corriendo Migraciones ---"
./vendor/bin/phinx migrate -e development

echo "--- Cargando Datos (Seeds) ---"
./vendor/bin/phinx seed:run -s UsuarioSeeder -e development
./vendor/bin/phinx seed:run -s EstadiosSeeder -e development
./vendor/bin/phinx seed:run -s EquiposSeeder -e development
./vendor/bin/phinx seed:run -s NoticiasSeeder -e development
./vendor/bin/phinx seed:run -s FechasSeeder -e development
./vendor/bin/phinx seed:run -s TorneosSeeder -e development

echo "--- Iniciando Servidor ---"
php -S 0.0.0.0:$PORT -t public