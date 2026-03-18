.PHONY: artisan build-prod octane-reload

artisan:
	docker compose -f docker-compose.yml exec php php artisan $(filter-out $@,$(MAKECMDGOALS))

artisan-socket:
	docker compose -f docker-compose.yml exec websocket php artisan $(filter-out $@,$(MAKECMDGOALS))

bash:
	docker compose -f docker-compose.yml exec bash

build:
	npm run build && make artisan octane:reload

error:
	tail -f -n 2000000 storage/logs/laravel.log | grep "production.ERROR"

build-prod: env-use-prod
	@echo "→ Сборка фронтенда (npm run build)"
	@if [ -d node_modules ]; then npm run build; else npm ci && npm run build; fi
	@$(MAKE) octane-reload

octane:
	@echo "→ Перезагрузка Laravel Octane"
	@docker compose exec -T php php artisan octane:reload

env-use-prod:
	@echo "→ Подмена $(ENV_FILE) из $(ENV_PROD)"
	@if [ ! -f "$(ENV_PROD)" ]; then echo "Файл $(ENV_PROD) не найден. Останавливаемся."; exit 1; fi
	@if [ -f "$(ENV_FILE)" ]; then cp "$(ENV_FILE)" "$(ENV_FILE).bak"; fi
	@cp "$(ENV_PROD)" "$(ENV_FILE)"

%:
	@:
