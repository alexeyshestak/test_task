# Test task

## Deploy

1. Download code
2. Import database (test.loc.sql - script file)
3. Install composer
    > composer install
4. Modify config files
    * Configs\db.php
    * Configs\settings.php
5. Configure virtual host
6. Paste
    > report.csv
file to the
    > Resources\Files directory
7. Import functionality will be available on home-page (scheme://domain/)

## Database

1. Script file with database schema located in the root folder and called:

> test.loc.sql

2. SQL queries are here:

> queries.sql

## Params for queries

You can set parameters for queries and import functionality (file link, mapping).
Just change values in configuration file:

> Configs\settings.php
