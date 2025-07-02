# MobilemTask Application based on Symfony

## Requirments

- Optional: Docker & Docker Compose
- PHP 8.4
- Composer
- MySQL lub MariaDB
- NodeJS 20+

## Setup

Project is part of enviorment based on docker-compose file with separate services for Apache, MariaDB and phpMyAdmin.

Standard:

```bash
cd project-folder
composer install
npm install
php bin/console assets:install
npm run build
```

For VSCode with Docker are prepared 2 scripts
- php - required for vscode to have access to php binary inside container
- container - helper to use commands in container
for those to work properly container name should be mobilemtask or update scripts

Docker with provided Dockerfile

```bash
cd project-folder
container composer install
container symfony assets:install
npm install
npm run build
```


## Notes

- Images upload is handled by `FileUploader` service
- The form validate up to 5 images, adding image is not required
