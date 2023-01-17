# Projet CRM DFS 2023

Un projet très passionnat pour découvrir Symfony

## Setup

composer install

yarn install

yarn encore dev

symfony console doctrine:database:create

symfony console doctrine:migration:migrate

symfony console doctrine:fixtures:load

### Important

N'oubliez pas de créer votre fichier .env.local !!!

APP_ENV=dev
DATABASE_URL="mysql://...."
