#!/bin/bash

GREEN='\033[0;32m'
NC='\033[0m'

docker-compose exec app-service bash -c "php artisan migrate:fresh"
docker-compose exec app-service bash -c "php artisan db:seed"
if [ $? == 0 ]; then
    echo -e "${GREEN}Database seeded!${NC}"
fi
