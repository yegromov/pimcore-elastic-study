# PHP Beauty Project 

The project template with classes to start PHP Beauty project.

## Getting started
```bash
git clone -b Infrastructure ssh://git@stash.scnsoft.com:2222/pd/pimcorex-dev-box.git
cd pimcorex-dev-box/php-beauty
make init-project
```

If needed, change UID and GID in docker-compose.yaml in user section (default 1000:1000).

- Open http://localhost:8080/admin in your browser
- Done! 😎

## Docker

PHP Beauty project assumes you're using Docker to run your local environment.
You don't need to have a PHP environment with composer installed.

### Prerequisits

* Your user must be allowed to run docker commands (directly or via sudo).
* You must have docker-compose installed.
* Your user must be allowed to change file permissions (directly or via sudo).
* You may need to put SELinux in Permissive mode: ``setenforce permissive``.

## TO-DO List

* Varnish is not yet properly configured, so nginx is listening 8080 port because Varnish is expected to operate on 80 port.


