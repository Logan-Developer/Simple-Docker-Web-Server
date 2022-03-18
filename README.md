# Simple-Docker-Web-Server
It's a simple web server with apache, php8 mysql and phpmyadmin

## Requirements
Docker and docker-compose are required, check the instructions corresponding to your Linux distribution

## How to use
You can access the apache webserver at http://localhost and phpmyadmin at http://localhost:8080.

### The different folders
- Put your website(s) in the website directory, eventually in a subfolder.<br>
- You can put sql scripts in the dump folder, that will be executed on the MYSQL server when the container starts. (Must be stopped first)<br>
- You can modify the php.ini and vhost.conf files in the conf folder if you want. The configurations will be updated when the container starts. (Must be stopped first)

### MySQL
MySQL is running by default on port 3306.
<br>
The default access are:
- for user:
  - login: user
  - password: test
- for root:
  - login: root
  - password: test
<br>
You can set new ones by modifying the environment variables in docker-compose.yml for db and phpmyadmin services.


### Main commands

#### Build and start container
```bash
docker-compose up -d --build
```

#### Stop container
```bash
docker-compose stop
```

#### Remove stopped containers
```bash
docker-compose rm
```
