# Take Me To - API

Take Me To - API is a minimalist API built on Laravel 12 to handle Take Me To Url Shortener SaaS.

## Features

- Create custom short url
- Fetch original url based of short url

## Tech Stack

- PHP 8.4
- Laravel 12
- PostgreSQL

## System Diagram

        +------------+
        |   Client   |
        | TMT Client |
        +------------+
        │
              ▼
        +------------+
        | API Server |
        | Laravel 12|
        +------------+
              │
              ▼
        +-----------+
        | Postgres  |
        | Database  |
        +-----------+

## Required External Stack

- PostgreSQL

## API Dir Structure

├── Http/
│ ├── Controllers/
│ │ └── UrlController.php
│ ├── Middleware/
│ │ └── ApiLoggerMiddleware.php
│ └── Requests/
│ ├── CreateShortUrlValidator.php
│ └── GetOriginalUrlValidator.php
├── Infrastructure/
│ └── Repository/
│ ├── Eloquent/
│ │ └── EloquentImplementation.php
│ └── Interface/
│ └── RepositoryInterface.php
├── Models/
│ ├── Urls.php
│ └── User.php
├── Providers/
│ └── AppServiceProvider.php
├── UseCases/
│ ├── CreateShortUrl.php
│ └── GetOriginalUrl.php
└── Utils/
└── Response/
└── StandarizedResponse.php
