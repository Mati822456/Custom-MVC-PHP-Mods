# Custom-MVC-PHP-Mods
Basic custom MVC web application that supports mods, including plugins and themes.
![main](https://user-images.githubusercontent.com/103435077/233659091-25386326-68cf-43bd-93c0-ed0196ee81b6.png)

## Table of Contents
* [General Info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Structure](#structure)
* [Acknowledgements](#acknowledgements)
* [Contact](#contact)

## General Info
This project is a simple website that supports mods, including plugins and themes. It is built using a custom MVC architecture, and offers basic support for database queries using only the AND operator. Custom routes can be created with support for HTTP methods such as GET, POST, PUT, PATCH, and DELETE. Plugins and themes can be obtained from repositories, currently four plugins and three themes are available. The website is designed with security in mind, and includes measures to prevent SQL injection attacks. Additionally, the website is fully responsive, ensuring that it works well on a wide range of devices and screen sizes.

![plugins](https://user-images.githubusercontent.com/103435077/235961161-197236a7-06b8-4f30-b934-6e251d720c54.png)
![themes](https://user-images.githubusercontent.com/103435077/235961169-4e9b3f5f-104e-41de-a717-3f01765d940c.png)
![show](https://user-images.githubusercontent.com/103435077/235961164-1f9351c1-a404-44e8-86e4-d3eb35fa184c.png)

### Some modifications enabled:
![example_main](https://user-images.githubusercontent.com/103435077/233671184-4b452552-0dbf-442d-bbdc-051a146ef444.png)
![example_theme](https://user-images.githubusercontent.com/103435077/235961157-67d2ebbf-2302-4a7b-9527-55d52f886d07.png)
![example_theme_2](https://user-images.githubusercontent.com/103435077/235961160-95a0b740-b360-481e-a05f-aa64a3228773.png)

## Technologies
* PHP 8.1.5
* MySQL 8.0.29
* HTML5
* CSS3
* JavaScript
* JQuery
* FontAwesome 6.4.0

## Setup
To run this project you will need PHP, MySQL on your local machine. (Or Apache like XAMPP).

```
Installation using only PHP

# Clone this repository
> git clone https://github.com/Mati822456/Custom-MVC-PHP-Mods.git

# Go into the directory
> cd Custom-MVC-PHP-Mods

# Install dependencies from lock file
> composer install

# Start MySQL Shell
> mysqlsh

# Connect to MySQL server
> \connect host@username

# Type password

# Switch to SQL mode
> \sql

# Create database
> CREATE DATABASE DB_NAME

# Set default schema
> use DB_NAME

# Import tables from database.sql
> \source LOCATION\Custom-MVC-PHP-Mods\database.sql

# In file Config\config.php change
> DB_HOST
> DB_USER
> DB_PASS
> DB_NAME

# Optimize autoload
> composer dump-autoload -o

# Start project
> php -S localhost:8000

# Access using
http://localhost:8000
```

```
# Easiest
Installation for XAMPP

# Clone this repository
> git clone https://github.com/Mati822456/Custom-MVC-PHP-Mods.git

# Go into the directory
> cd Custom-MVC-PHP-Mods

# Go to the XAMPP directory

# Delete all files from htdocs

# Copy files from downloaded repository and paste them to htdocs

# Install dependencies from lock file
> composer install

# Optimize autoload
> composer dump-autoload -o

# Run XAMPP

# Turn on MySQL Service

# Create Database

# Import database.sql to created database

# In file Config\config.php change
> DB_HOST
> DB_USER
> DB_PASS
> DB_NAME

# Turn on Apache Service

# Access using
http://localhost
```

## Structure
Most important:
* **app**
    - Controllers   -   controllers receive data from the user and respond to their actions, managing the update of the model and refreshing the views
        -   Manager.php     -   the most important main module responsible for loading/unloading/uninstalling mods
        -   Controller.php  -   can be inherited
    - Models        -   contains model classes 
    - Repository    -   repositories for plugins and themes to easily manage records in database
    - Database.php  -   database connection and operations
    - router.php    -   routing 
    - web.php       -   contain routes
* **config**
    - config.php    -   database connection credentials and app version
* **mods**  -   directory where mods are stored
    - plugins
    - themes
* **public**
    - css
    - images
    - js
    - mods
        - plugins
        - themes
* **vendor**
    - contains only autoload
* **views**
    - layouts
        - header.php
    - index.php
    - plugins.php
    - response.php
    - themes.php
## Acknowledgements
Thanks https://www.reshot.com/ for icons

## Contact
Feel free to contact me via email mateusz.zaborski1@gmail.com.
Or linkedin https://www.linkedin.com/in/mateusz-zaborski-326426248/