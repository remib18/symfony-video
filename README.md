# Symfony video

## Introduction

This project ambition is to create a video platform. Started as a college project, we plan to continue it as a side project.

The end goal is to be able to search for videos, adds them to a watchlist, and watch them.

### Features

Note: For our college project, the goal is to have a functioning homepage customization, accounts system, administration panel and  basic film research. 

- [x] Users (login, register)
- [x] Admin panel
- [x] Homepage customization with markdown
- [ ] Blog
- [ ] Contact form
- [ ] Media search
- [ ] Media watchlist
- [ ] Media player

## Installation

Before starting, make sure you have the following installed on your computer:
- PHP 8.2
- Composer
- NodeJS
- MySQL
- Symfony CLI

### Setup

1. Get the codebase from GitHub
2. Copy the `.env` file to `.env.local`
3. Set up the MySQL database in the `.env.local` file
4. Install the dependencies with `composer i`
5. If there is any php file in the `migrations` folder, delete them
6. Run the command `symfony console make:migration`
7. Create the database if not already created with `symfony console doctrine:database:create`
8. Then run the command `symfony console doctrine:migrations:migrate` to create all the tables
9. Run the SQL setup script: `./setup.sql`
10. Install NodeJS dependencies with `npm i`
11. Build the assets with `npm run build`
12. Start the local server with `symfony serve`
13. Go to `localhost:8000` in your browser
14. Follow the instructions to create an admin account