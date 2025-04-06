# Symfony Video Platform

## Introduction
This project aims to create a comprehensive video platform. It originated as a college project and is designed to allow users to search for videos, add them to watchlists, and watch content directly on the platform.

## Legal Disclaimer
**IMPORTANT NOTICE**: This software is provided for educational and personal use only. Users are responsible for ensuring that all content accessed, uploaded, or shared through this platform complies with applicable copyright laws and regulations. The creators and contributors of this project do not endorse or encourage copyright infringement or unauthorized distribution of protected content. Users should obtain proper rights or permissions before uploading or sharing any content that is not their own or is not in the public domain.

## Project Scope
For the college project phase, we're focusing on implementing these core functionalities:
- Functional homepage customization
- User account management system
- Administrative control panel
- Basic film search capabilities

### Feature Status
- ✅ User Management (login, registration)
- ✅ Administrative Panel
- ✅ Homepage Customization with Markdown
- ✅ Blog System
- ✅ Contact Form
- ✅ Media Search Functionality
- ❌ Media Watchlist (planned)
- ❌ Integrated Media Player (planned)

## Installation Guide

### Prerequisites
Before beginning installation, ensure your system meets these requirements:
- PHP 8.2 or higher
- Composer (dependency manager)
- NodeJS (for frontend assets)
- MySQL database
- Symfony CLI

### Step-by-Step Setup Instructions
1. Clone the repository from GitHub
2. Create a local environment configuration by copying `.env` to `.env.local`
3. Configure your MySQL database connection in the `.env.local` file
4. Install PHP dependencies:
   ```
   composer i
   ```
5. Remove any existing migration files from the `migrations` directory
6. Generate a fresh migration:
   ```
   symfony console make:migration
   ```
7. Create the database if it doesn't exist:
   ```
   symfony console doctrine:database:create
   ```
8. Apply the migrations to create the database schema:
   ```
   symfony console doctrine:migrations:migrate
   ```
9. Execute the SQL setup script:
   ```
   ./setup.sql
   ```
10. Install frontend dependencies:
    ```
    npm i
    ```
11. Compile frontend assets:
    ```
    npm run build
    ```
12. Launch the development server:
    ```
    symfony serve
    ```
13. Access the application at `localhost:8000`
14. Follow the on-screen instructions to create your initial administrator account
