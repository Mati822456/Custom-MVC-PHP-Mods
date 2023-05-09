# Custom-MVC-PHP-Mods
Basic custom MVC web application that supports mods, including plugins and themes.
![main](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/ebf45b07-7287-4fd6-bd97-9e2745b6cadb)

## Table of Contents
* [General Info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Structure](#structure)
* [Acknowledgements](#acknowledgements)
* [Contact](#contact)

## General Info
This project is a simple website that supports mods, including plugins and themes. It is built using a custom MVC architecture, and offers basic support for database queries using only the AND operator. Custom routes can be created with support for HTTP methods such as GET, POST, PUT, PATCH, and DELETE. Plugins and themes can be obtained from repositories, currently four plugins and three themes are available. The website is designed with security in mind, and includes measures to prevent SQL injection attacks. Additionally, the website is fully responsive, ensuring that it works well on a wide range of devices and screen sizes.

![plugins](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/dd9e48a8-8a40-41a5-8947-bfa1518a4978)
![themes](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/6fcb1694-2043-4f49-8fb2-00d17add3cb8)
![settings](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/1c511b5e-2e76-4f36-a049-43f10602065d)
![show](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/9ba14662-ebbd-4627-8b12-4a8b485afec8)

### Some modifications enabled:
![example_main](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/9e987841-b862-4063-a71d-8567440016db)
![example_theme](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/8fdcae69-0d73-4869-b4c3-f93454340f10)
![example_theme_2](https://github.com/Mati822456/Custom-MVC-PHP-Mods/assets/103435077/2dca738f-6e45-40b7-8b4d-e53d72d32871)

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