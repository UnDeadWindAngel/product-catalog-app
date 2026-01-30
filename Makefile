up:
	docker-compose up -d --build
	@echo "Waiting for containers to be ready..."
	sleep 5
	@echo "Containers are running!"

down:
	docker-compose down

logs:
	docker-compose logs -f

ps:
	docker-compose ps

app-bash:
	docker-compose exec app bash

node-bash:
	docker-compose exec node sh

pgsql-bash:
	docker-compose exec pgsql bash