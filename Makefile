start:
	docker-compose up -d
	docker exec symfony_prep_php sh -c 'composer install'

shell:
	docker exec -it symfony_prep_php sh

stop:
	docker-compose down
