Database initialization and connection

- **DB name**: hiki
- **Host**: localhost
- **User**: root
- **Password**: golden ratio 1.618

The project uses `class/ConnexionDB.php` for PDO connection.

By default, the code uses these values for the project:

- `DB_HOST=localhost`
- `DB_NAME=hiki`
- `DB_USER=root`
- `DB_PASS=golden ratio 1.618`

If your MySQL credentials differ, set environment variables before starting the server:

```
set DB_HOST=localhost
set DB_NAME=hiki
set DB_USER=root
set DB_PASS=your_password
```

To initialize the database schema, run the provided `init.sql` against your MySQL server.

Run with MySQL CLI:

```
mysql -u root < init.sql
```

If your root account has a password, run:

```
mysql -u root -p < init.sql
```

Or import `init.sql` through phpMyAdmin or other DB tools. The SQL file creates the `hiki` database and the core tables including `camping_sites`, `users`, `adresses`, and `posts`.
