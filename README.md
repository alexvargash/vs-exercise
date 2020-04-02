## Requirements

- [Laravel Requirements](https://laravel.com/docs/7.x/installation#server-requirements).
- Redis installed.

## Installation

Clone the repository.

```bash
git clone git@github.com:alexvargash/vs-exercise.git project-name
```

Move to your project folder.

```bash
cd project-name
```

Change to the feature branch.

```bash
git checkout feature/subscriptions-update
```

Install the composer dependencies.

```bash
composer install
```

Copy the example environment file.

```bash
cp .env.example .env
```

Create the Laravel app key.

```bash
php artisan key:generate
```

Create a database `vueschool`, if you use another name make sure to update the `DB_DATABASE` variable on the `.env` file. And add the correct credentials for `DB_USERNAME` and `DB_PASSWORD`.
