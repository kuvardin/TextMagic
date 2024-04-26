```shell
docker compose up -d --build
composer install
docker exec -ti textmagic_php bash
doctrine:migrations:migrate
doctrine:fixtures:load 
```

Open in browser: https://localhost:29443