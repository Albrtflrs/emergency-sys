# Restore and backup drill

This runbook covers the minimum Stage 05 backup and restore checks required by the SRS.

## 1. Backup

Run nightly:

```bash
mysqldump --single-transaction --routines --triggers --hex-blob \
  -u mdrrmo_app -p mdrrmo > /var/backups/mdrrmo-database-$(date +%F).sql

tar -czf /var/backups/mdrrmo-storage-$(date +%F).tgz /var/www/mdrrmo/storage/app
```

Store backups encrypted at rest and keep the encryption key outside the app host.

## 2. Restore drill

1. Restore the database into a temporary schema.
2. Restore storage contents into a staging directory.
3. Point the application to the restored schema in a non-production environment.
4. Verify the landing page, staff dashboard, and tracking route all load without data loss.
5. Record the timestamp, operator, and outcome in the stage report.

## 3. Monitoring

The `health:check-disk` command should run on a schedule and alert when disk usage exceeds 80 percent.

```bash
php artisan health:check-disk --threshold=80
```

A warning is recorded in the application log and the command exits with a failure code when the threshold is crossed.
