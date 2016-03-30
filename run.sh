echo "* Configuring nginx"
sed -i "s/%SERVER_NAME%/$APP_URL/g" /etc/nginx/conf.d/polr.conf
echo "* Downloading polr"
git clone --depth 1  --branch 1.0-legacy https://github.com/Cydrobolt/polr app
cd app
cp /scripts/dockercfg.php .
echo "* Installing polr"
php dockercfg.php
rm dockercfg.php
if [ $SETUP_PASSWORD == "none" ]; then
    rm setup.php
fi
echo "* Starting nginx"
service php5-fpm start && chmod a+rwx /var/run/php5-fpm.sock && nginx -g "daemon off;"
