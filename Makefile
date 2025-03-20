start:
	docker-compose up -d
	docker exec symfony_prep_php sh -c 'composer install'

start_linux:
	docker-compose up -d
	docker exec -u1000 symfony_prep_php sh -c 'composer install'

shell:
	docker exec -it symfony_prep_php sh

shell_linux:
	docker exec -u1000 -it symfony_prep_php sh

stop:
	docker-compose down
