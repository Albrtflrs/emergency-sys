# Bootstrap Local Development

Fresh clone to running app, on a new machine.

## Prerequisites

- PHP 8.2 or higher with pdo_mysql
- Composer
- Node.js 20 LTS or higher
- MySQL 8.0 or higher running on 127.0.0.1:3306 for production-like setup

## 1. Clone and install

    git clone <repo> mdrrmo
    cd mdrrmo
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate

## 2. Bootstrap MySQL

Follow `docs/runbooks/bootstrap-mysql-user.md`. That creates both schemas
(`mdrrmo`, `mdrrmo_test`) and all three users with the grant matrix.

For this Windows development machine, the configured local server is MariaDB
10.4 on port 3307 and uses `emergency_sys` / `emergency_sys_test`. The local
service accounts and grants have been provisioned on that instance. This local
setup is not production-compliant; production must use the MySQL 8 runbook
requirements and separate production passwords.

## 3. Configure local environments

For a fresh MySQL 8 setup, set `DB_HOST`, `DB_PORT`, `DB_DATABASE`,
`DB_USERNAME`, `DB_PASSWORD`, `DB_AUDIT_USERNAME`, `DB_AUDIT_PASSWORD`,
`DB_MIGRATE_USERNAME`, and `DB_MIGRATE_PASSWORD` to match the runbook.

On the current Windows development machine, local MariaDB is on port 3307:

- `.env`: `emergency_sys`, runtime user `mdrrmo_app`
- `.env.testing`: `emergency_sys_test`, runtime user `mdrrmo_app`
- Both files set the audit and migration usernames and local passwords.

Do not copy local credentials into production. Set distinct production secrets
and rotate the local example credentials before deployment.

## 4. Run migrations

Migrations run as the migrate user, not the app user. The normal command uses
the active `.env` schema; for testing, use the isolated test environment:

    php artisan migrate --database=mysql_migrate --seed
    php artisan migrate --env=testing --database=mysql_migrate --force

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

Tests use `emergency_sys_test` on port 3307 in this local setup. They will fail
if the grants from step 2 are missing,
which is intentional.

## Troubleshooting

- "UPDATE command denied": expected. The app user has no UPDATE on audit_logs
  or on the probe table.
- "Access denied for user 'mdrrmo_migrate'": the migration user's grants were
  not applied; rerun section 5 and 6 of the MySQL runbook.
- "STRICT_ALL_TABLES missing": MySQL default sql_mode is not strict enough;
  see section 1 of the MySQL runbook.