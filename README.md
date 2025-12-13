# Lyricist (Backend)

## Contents
- [Lyricist (Backend)](#lyricist-backend)
  - [Contents](#contents)
  - [Tools](#tools)
    - [Built With](#built-with)
  - [Imeplementation](#imeplementation)
    - [Modules](#modules)
      - [Login](#login)
      - [Profile](#profile)
      - [Access Control](#access-control)
        - [User](#user)
        - [Roles](#roles)
          - [Super Admin Role:](#super-admin-role)
          - [Users Role:](#users-role)
        - [Permissions](#permissions)
  - [Deployment](#deployment)
    - [Public domains](#public-domains)
    - [Version check](#version-check)
    - [For local Development](#for-local-development)
    - [Cron Scheduler](#cron-scheduler)
    - [important](#important)
    - [Database](#database)
      - [Database change](#database-change)
    - [Log Viewer](#log-viewer)
    - [Docker](#docker)
      - [Docker:](#docker-1)
      - [Network : External Network](#network--external-network)
      - [Access container to container](#access-container-to-container)
      - [Command: docker](#command-docker)
      - [Command: docker  : Services](#command-docker---services)
      - [Browser:](#browser)
      - [Workbench:](#workbench)
  - [Improvements](#improvements)
    - [Issue](#issue)
  - [GIT commit types](#git-commit-types)
  - [Documentation](#documentation)
  - [Project details](#project-details)
    - [Service](#service)
    - [Hotkey](#hotkey)
    - [Sub Hotkey](#sub-hotkey)
- [](#)
- [Force PHP 8.1 for this directory](#force-php-81-for-this-directory)
- [Download Composer installer](#download-composer-installer)
- [Install locally](#install-locally)
- [Use composer](#use-composer)
- [Cron job setup example](#cron-job-setup-example)
- [**📌 Current Cron Jobs**](#-current-cron-jobs)



## Tools

### Built With

<!-- What things you need to install the software and how to install them -->

| Build With                | Version | Server |
| ------------------------- | ------- | ------ |
| php                       | 8.1.10  |        |
| Composer                  | 2.8.4   |        |
| MySQL                     | 8       |        |
| Laravel                   | 8.83.29 |        |
| passport                  |         |        |
| spatie/laravel-permission |         |        |
| logviwer                  |         |        |


## Imeplementation

This projects has been divided into several modules to make the development of the projectis much more easier.  Such are -

### Modules

| Modules               |
| --------------------- |
| ⛨⛨ **Admin Panel** ⛨⛨ |
| Login                 |
| Profile               |
| 🛂 **Super Admin**     |
| Users                 |
| Roles                 |
| Permissions           |
| 🗐  **Dashboard**      |
| Dashboard             |
| 🗐  **Reports**        |
| Audit Log             |
| Usage Report          |


#### Login

**Token Accept Ways**

1. Header Authorization Bearer
   1. Authorization = Bearer token
2. Query params
   - **URL**
       - www.abc.com/api/v1/user?access_token=token
       - here,  access_token will be set to header automatically,
         - Authorization = Bearer access_token


#### Profile

- Update user own information

- search user own permission


#### Access Control

##### User

- user list
- user create
  - add role
- user update
  - manage role

##### Roles

- role list
- role create
  - add permissions
- role update
  - manage permissions

###### Super Admin Role:
- Can create access for the Service OPS users.
- Access will be created as preset i.e DB Web Level 1, DB Push Level 2 etc.
- While creating presets the super admin can set preset wise DB, Table, Column and row limit.
- DB Query: This is the main module of this phase. In this module the user can perform a search in the Database without accessing it. And the user will get only that data which h/she has permission. No download can't be performed.
- Only Super Admin and management can view the Audit log. this audit log is for tracking the user's journey to through the panel. His/her every movement is recorded here.

###### Users Role:
1.	Can see his/her access level.
2.	DB Query: This is the main module of this phase. In this module the user can perform a search in the Database without accessing it. And the user will get only that data which h/she has permission. No download can't be performed.

##### Permissions

- permission list
- permission create
- permission update
- role list
- role create
- role update
- user list
- user create
- user update

## Deployment

### Public domains



### Version check

```
php .\artisan --version
```


### For local Development

```
    git clone repo

    composer install
    or
    composer install  --ignore-platform-reqs
    or
    composer install --no-cache --ignore-platform-reqs
    or
    composer install --optimize-autoloader --dev

    cp .env.example .env   
    change content of .env file


============passport==========
    php artisan key:generate  
    or
    php artisan passport:install
    php artisan passport:keys 
============passport==========

    php artisan serve 
    or
    php artisan serve --port=8001


    composer dump-autoload

    ==========Cache clear===============
    php artisan cache:clear
    php artisan route:cache
    php artisan config:cache
    php artisan view:clear
    php artisan optimize
    php artisan config:clear
    php artisan route:clear
    composer dump-autoload
    php artisan log:clear
    yes

    php artisan serve --port=8001
    ==========Cache clear===============


php.ini size check
php -i | findstr /C:"upload_max_filesize" /C:"post_max_size"

```

### Cron Scheduler
```
every minute
/usr/local/bin/php /home/winedsco/ppsapi.wineds.com/artisan schedule:run
```

### important
```
for file system always use like this

/var/www

    not

/var/www/
```

### Database 

#### Database change 
```
ALTER TABLE roles DROP COLUMN id 
ALTER TABLE roles ADD id INT IDENTITY(1,1)


ALTER TABLE users
ADD CONSTRAINT PK_users_id PRIMARY KEY (id);




-- Disable foreign key constraints referencing the 'id' column in the 'roles' table
EXEC sp_MSforeachtable 'ALTER TABLE ? NOCHECK CONSTRAINT ALL';

ALTER TABLE users DROP CONSTRAINT PK_users_id;
ALTER TABLE users DROP COLUMN id ;
ALTER TABLE users ADD id INT IDENTITY(1,1);

ALTER TABLE roles DROP CONSTRAINT PK_roles_id;
ALTER TABLE roles DROP COLUMN id ;
ALTER TABLE roles ADD id INT IDENTITY(1,1);

ALTER TABLE permissions DROP CONSTRAINT PK_permissions_id;
ALTER TABLE permissions DROP COLUMN id ;
ALTER TABLE permissions ADD id INT IDENTITY(1,1);

-- Re-enable foreign key constraints
EXEC sp_MSforeachtable 'ALTER TABLE ? WITH CHECK CHECK CONSTRAINT ALL';
```

### Log Viewer
* http://localhost:801/log_cmp_(today date y-m-d)
  * http://localhost:801/log_cmp_2022-06-09
* 

### Docker
#### Docker: 

#### Network : External Network
```
sudo docker network create invoice_portal_network
sudo docker network create local_central_db_network

docker exec -it lyricist-admin-api-container sh
```

#### Access container to container
```
docker exec ms-rmq-container ping lyricist-admin-api-container
docker exec ms-rmq-container ping cmp-db-container

docker exec lyricist-admin-api-container ping ms-rmq-container 
docker exec lyricist-admin-api-container telnet ms-rmq-container 5673
docker exec lyricist-admin-api-container ping cmp-db-container 

docker exec cmp-db-container ping ms-rmq-container
docker exec cmp-db-container ping lyricist-admin-api-container
```




#### Command: docker 
```
sudo docker-compose down
sudo docker-compose build && docker-compose up -d
```

#### Command: docker  : Services
```
docker-compose exec export sh
```

#### Browser: 
- browser: http://localhost:801

#### Workbench:
```
host: 127.0.0.1 (System/LocalHost port)
port: 4310 (System/LocalHost PC Port)
username: root
pass: root
``` 

## Improvements


### Issue 

```
file data reading only now file_path
but in real scenario we have to pass other data like server ip, 
```





## GIT commit types
```
feat: New feature for the user.
fix: Bug fix.
style: Code Style Changes.
refactor: Code Refactoring.
build: Build System Changes.
ci: Continuous Integration Changes.
perf: Performance Improvements.
revert: Revert a Previous Commit.
docs: Documentation changes.
test: Adding or modifying tests.
chore: Routine tasks, maintenance, or housekeeping.
```



## Documentation
- https://github.com/alexandr-mironov/php-smpp


## Project details

### Service
### Hotkey
### Sub Hotkey


http://esoftbd.org/rpt/rptan.aspx?item=embeded_details&startdate=01/01/2024&enddate=12/10/2024



# 
```
TRUNCATE events;
TRUNCATE home_main_slider;
TRUNCATE members;
TRUNCATE success_stories;
TRUNCATE product;
```
for php version compatability issue : 
InvoiceAPI Laravel Setup Guide

Server: cPanel / SSH
Subdomain: invoiceapi.wineds.com
PHP version needed: 8.1

1. Set Specific PHP Version for Subdomain/Directory

Option 1: Using .htaccess in the root directory of subdomain

# Force PHP 8.1 for this directory
<IfModule ea-php81>
    AddHandler application/x-httpd-ea-php81 .php
</IfModule>


Option 2: Using .user.ini in the subdomain root

; Force PHP 8.1
; (This works if MultiPHP INI editor is not available)


✅ This ensures the subdomain runs PHP 8.1 even if the system default is lower.

2. Clone Git Repository and Initialize Git
cd ~
git clone https://github.com/Asaduzzaman285/wintel_api_backend.git invoiceapi.wineds.com
cd invoiceapi.wineds.com
git pull origin main

3. Copy Environment File
cp .env.example .env


Edit .env and set your database credentials and other environment variables.

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=winedsco_invoice
DB_USERNAME=winedsco_invoice
DB_PASSWORD=paSSSss1$23!!

4. Install Composer (if not installed)
# Download Composer installer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install locally
/opt/cpanel/ea-php81/root/usr/bin/php composer-setup.php --install-dir=. --filename=composer

# Use composer
/opt/cpanel/ea-php81/root/usr/bin/php composer install --ignore-platform-req=ext-sockets


⚠️ If proc_open is disabled in PHP, temporarily enable it using:

/opt/cpanel/ea-php81/root/usr/bin/php -d disable_functions="" composer install

5. Generate Laravel App Key
/opt/cpanel/ea-php81/root/usr/bin/php -d disable_functions="" artisan key:generate

6. Run Migrations
/opt/cpanel/ea-php81/root/usr/bin/php -d disable_functions="" artisan migrate


Make sure .env has correct DB credentials.

7. Seed Roles, Permissions, and Users

Connect to MySQL using your cPanel credentials or phpMyAdmin:

-- Insert roles
INSERT INTO roles (name, guard_name, created_at, updated_at)
VALUES 
  ('admin', 'web', NOW(), NOW()),
  ('user', 'web', NOW(), NOW());

-- Insert permissions
INSERT INTO permissions (name, guard_name, created_at, updated_at)
VALUES 
  ('manage users', 'web', NOW(), NOW()),
  ('create user', 'web', NOW(), NOW()),
  ('update user', 'web', NOW(), NOW()),
  ('create problem', 'web', NOW(), NOW()),
  ('update problem', 'web', NOW(), NOW());

-- Assign permissions to admin role
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id
FROM permissions p, roles r
WHERE p.name IN ('manage users', 'create user', 'update user', 'create problem', 'update problem') 
  AND r.name = 'admin';

-- Create admin user
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES 
  ('Admin', 'admin@gmail.com', 
   '$2y$10$wHnQwQwQwQwQwQwQwQwQwOQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', -- bcrypt for Password123!
   NOW(), NOW());

-- Create demo user
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES 
  ('Demo User', 'user@gmail.com', 
   '$2y$10$wHnQwQwQwQwQwQwQwQwQwOQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', -- bcrypt for Password123!
   NOW(), NOW());

-- Assign roles to users
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\Models\\User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND u.email = 'admin@gmail.com';

INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\Models\\User', u.id
FROM roles r, users u
WHERE r.name = 'user' AND u.email = 'user@gmail.com';

8. Run Passport Installation
/opt/cpanel/ea-php81/root/usr/bin/php artisan passport:install


This will create:

Personal access client

Password grant client

Note the client IDs and secrets for API usage.

9. Optional: Add Soft Deletes Columns
ALTER TABLE roles ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE model_has_roles ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE role_has_permissions ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE permissions ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;

10. Serve Laravel App (Development Only)
/opt/cpanel/ea-php81/root/usr/bin/php -d disable_functions="" artisan serve


⚠️ For production, set up Apache/Nginx with .htaccess or public_html routing.

✅ cPanel DB Credentials

User: winedsco_invoice

Password: paSSSss1$23!!

Database: winedsco_invoice

This guide ensures you can reuse it next time without repeating commands or troubleshooting PHP version issues.



-- Assign 'user' role (id = 2) to User1 (id = 1)
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (2, 'App\\Models\\User', 1);

-- Assign 'admin' role (id = 1) to Asaduzzaman Asad (id = 2)
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (1, 'App\\Models\\User', 2);





# Cron job setup example


---

# **📌 Current Cron Jobs**

```markdown
| Minute | Hour | Day | Month | Weekday | Command                                                                                                              |
| ------ | ---- | --- | ----- | ------- | -------------------------------------------------------------------------------------------------------------------- |
| *      | *    | *   | *     | *       | /usr/local/bin/php /home/winedsco/ppsapi.wineds.com/artisan schedule:run                                             |
| *      | *    | *   | *     | *       | /usr/local/bin/php /home/winedsco/gamesapi.wineds.com/artisan schedule:run                                           |
| *      | *    | *   | *     | *       | /opt/cpanel/ea-php81/root/usr/bin/php /home/winedsco/invoiceapi.wineds.com/artisan sync:wintext-invoice-support-data |
```

---

If you want, I can also:

✅ Rewrite it into crontab format
✅ Check if each cron job is correct
✅ Fix path or permissions
✅ Add logs (recommended)

Just tell me!

