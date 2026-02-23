@echo off
echo --- Iniciando Migracion ---
call vendor\bin\phinx migrate -e development

echo --- Ejecutando Seeders ---
call vendor\bin\phinx seed:run -s UsuarioSeeder -e development
call vendor\bin\phinx seed:run -s EstadiosSeeder -e development
call vendor\bin\phinx seed:run -s EquiposSeeder -e development
call vendor\bin\phinx seed:run -s NoticiasSeeder -e development
call vendor\bin\phinx seed:run -s FechasSeeder -e development
call vendor\bin\phinx seed:run -s TorneosSeeder -e development


echo --- Seeders Listos ---