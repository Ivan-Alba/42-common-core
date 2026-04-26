all: build

build:
	mkdir -p $(CURDIR)/database
	mkdir -p $(CURDIR)/certs
	mkdir -p $(CURDIR)/private
	mkdir -p $(CURDIR)/redis_data
	mkdir -p $(CURDIR)/php_storage
	docker compose up -d --build #-d to run docker as a background process
up:
	docker compose up -d
down: 
	docker compose down
clean: down
	-@rm -rf services/frontend/app/node_modules
	-@rm -rf services/php/www/vendor

	# We create a tmp container to remove the mount volumes
	docker run --rm -v $(CURDIR):/tmp alpine rm -rf /tmp/database \
		/tmp/certs \
		/tmp/private \
		/tmp/redis_data\
		/tmp/php_storage/logs/*.log \
		/tmp/php_storage/framework/views/* \
		/tmp/php_storage/framework/cache/* \
		/tmp/php_storage/framework/sessions/*\
		/tmp/services/php/www/public/storage

	@if [ ! -z "$$(docker ps -qa)" ]; then \
		docker stop $$(docker ps -qa); \
		docker rm $$(docker ps -qa); \
	fi
	@if [ ! -z "$$(docker images -qa)" ]; then \
		docker rmi $$(docker images -qa); \
	fi
	@if [ ! -z "$$(docker volume ls -q)" ]; then \
		docker volume rm $$(docker volume ls -q); \
	fi
	@if [ ! -z "$$(docker network ls | grep transcendence)" ]; then \
		docker network rm transcendence; \
	fi
re: clean build

.PHONY: all build up down clean fclean re