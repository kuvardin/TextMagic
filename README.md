```shell
docker compose up -d --build
docker exec -ti textmagic_php bash
doctrine:migrations:migrate
doctrine:fixtures:load 
```

Open in browser: https://localhost:29443