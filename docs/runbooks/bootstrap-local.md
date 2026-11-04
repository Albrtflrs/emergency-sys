# Bootstrap Local Development

Fresh clone to running app, on a new machine.

## Prerequisites

- PHP 8.2 or higher with pdo_mysql
- Composer
- Node.js 20 LTS or higher
- MySQL 8.0 or higher running on 127.0.0.1:3306

## 1. Clone and install

    git clone <repo> mdrrmo
    cd mdrrmo
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate

## 2. Bootstrap MySQL

Follow `docs/runbooks/bootstrap-mysql-users.md`. That creates both schemas
(mdrrmo, mdrrmo_test) and all three users with the grant matrix.

## 3. Fill in .env

Set DB_PASSWORD, DB_AUDIT_PASSWORD, DB_MIGRATE_PASSWORD to the values you
used in step 2. Leave DB_HOST, DB_DATABASE, DB_USERNAME, DB_PORT at their
defaults unless you changed them.

## 4. Run migrations

Migrations run as the migrate user, not the app user:

    php artisan migrate --database=mysql_migrate --seed

## 5. Verify connections

    php artisan tinker
    >>> DB::connection('mysql')->getPdo();
    >>> DB::connection('mysql_audit')->getPdo();

Both must return a PDO instance without throwing.

## 6. Run the app

    npm run dev
    php artisan serve

Then visit http://localhost:8000.

## 7. Run tests

    php artisan test

Tests use mdrrmo_test. They will fail if the grants from step 2 are missing,
which is intentional.

## Troubleshooting

- "UPDATE command denied": expected. The app user has no UPDATE on audit_logs
  or on the probe table.
- "Access denied for user 'mdrrmo_migrate'": the migration user's grants were
  not applied; rerun section 5 and 6 of the MySQL runbook.
- "STRICT_ALL_TABLES missing": MySQL default sql_mode is not strict enough;
  see section 1 of the MySQL runbook.