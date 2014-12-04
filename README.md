# Dockerfile for Polr
## Example

```bash
docker run -d -e MYSQL_ROOT_PASSWORD=something -e MYSQL_USER=someusername -e MYSQL_PASSWORD=somepassword -e MYSQL_DATABASE=somedbname --name polrdb mysql

docker run -d -e ADMIN_EMAIL=admin@polr.me -e ADMIN_USER=admin -e ADMIN_PASSWORD=letmein -e APP_NAME=Polr -e APP_URL=polr.me -e REG_TYPE=free --link polrdb:mysql cydrobolt/polr
```

## Options
* DB_HOST - The MySQL host to use (default: uses the 'mysql' link)
* DB_USER - The MySQL username (default: uses the 'mysql' link)
* DB_PASS - The MySQL password (default: uses the 'mysql' link)
* DB_DATABASE - The MySQL database (default: uses the 'mysql' link)
* APP_URL - The domain name of the app. The docker image currently doesn't support subdirectories (default: polr.me)
* APP_NAME - The name of the app (default: Polr)
* SETUP_PASSWORD - The password to access /setup.php (default: none - setup.php is disabled)
* REG_TYPE - The registration type. Can be 'none' (No Registration) or 'free' (Open Registration)
* IP_METHOD - The PHP code to determine a user's IP (default: $_SERVER['REMOTE_ADDR'])
* PRIVATE - Requires users to be logged in to shorten links. Can be 'true' or 'false' (default: false)
* THEME - The URL of the CSS file for a Bootstrap theme to style Polr. See bootswatch.com for some themes (default: none, default theme)
* ADMIN_USER - The username of the administrative user (default: admin)
* ADMIN_PASSWORD - The password of the administrative user (default: secret)
* ADMIN_EMAIL - The email of the administrative user (default: admin@example.tld)
