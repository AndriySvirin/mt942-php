WIN_ETH_DRIVER := 'Ethernet adapter Ethernet 3'

ifdef WIN_ETH_DRIVER
WIN_ETH_IP := $(shell ipconfig.exe | grep ${WIN_ETH_DRIVER} -A3 | cut -d':' -f 2 | tail -n1 | sed -e 's/\s*//g')
endif

docker-up u start:
	cd docker && docker-compose -p mt942-php up -d;
	@if [ "$(WIN_ETH_IP)" ]; then cd docker && docker-compose -p mt942-php exec php-mt942-php sh -c "echo '$(WIN_ETH_IP) host.docker.internal' >> /etc/hosts"; fi

docker-down d stop:
	cd docker && docker-compose -p mt942-php down

docker-build build:
	cd docker && docker-compose -p mt942-php build --no-cache

docker-php php:
	cd docker && docker-compose -p mt942-php exec php-mt942-php /bin/bash

check:
	cd docker && docker-compose -p mt942-php exec php-mt942-php ./vendor/bin/phpcbf
	cd docker && docker-compose -p mt942-php exec php-mt942-php ./vendor/bin/phpcs
	cd docker && docker-compose -p mt942-php exec php-mt942-php ./vendor/bin/phpstan
	cd docker && docker-compose -p mt942-php exec php-mt942-php ./vendor/bin/phpunit
