# how to install development environment

## start docker containers
    docker-compose up
    CTRL + Z to detach

## configure database container
    # connect to container
    winpty docker exec -it heylisten-db zsh

    # login to mysql
    mysql -u root

    # create database and user
    CREATE DATABASE `heylisten` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
    CREATE USER 'heylisten'@'%' IDENTIFIED BY '1234';
    GRANT ALL PRIVILEGES ON `heylisten`.* TO 'heylisten'@'%';
    FLUSH PRIVILEGES;
    exit

    exit

## configure web container
    # connect to container
    winpty docker exec -it heylisten-web zsh

    # install composer dependencies
    composer install

    # create application encryption key
    php artisan key:generate

    # add env file symbolic link
    ln -s .env.example .env

    # create database tables and seed them
    php artisan migrate
    php artisan db:seed

    # install javascript libraries
    apk add node npm yarn

    # install yarn dependencies
    yarn install --frozen-lockfile

## update nginx configuration 
```bash
# create dir
mkdir etc/nginx/sites-enabled

# add heylisten nginx configuration file
cp docker-nginx.conf etc/nginx/sites-enabled/heylisten.conf

# comment line in /etc/nginx/nginx.conf
#include /etc/nginx/conf.d/*.conf;

# add line in /etc/nginx/nginx.conf after the commented line
include /etc/nginx/sites-enabled/*.conf;
```

# add api subdomain to SSL certificate

```bash
>/etc/ssl/nginx/$DOMAIN.ext cat <<-EOF
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
subjectAltName = @alt_names
[alt_names]
DNS.1 = $DOMAIN # Be sure to include the domain name here because Common Name is not so commonly honoured by itself
DNS.2 = www.$DOMAIN # add additional domains and subdomains if needed
DNS.2 = api.$DOMAIN
IP.1 = 192.168.0.13 # Optionally, add an IP address (if the connection which you have planned requires it)
EOF

# create signed certificate by certificate authority
openssl x509 -req -in /etc/ssl/nginx/heylisten.csr -CA /etc/ssl/nginx/certificate_authority.pem -CAkey /etc/ssl/nginx/certificate_authority.key    -CAcreateserial -out /etc/ssl/nginx/heylisten.pem -days 825 -sha256 -extfile /etc/ssl/nginx/heylisten.ext
```

# add domain to hosts file on host computer
    127.0.0.1 heylisten.app api.heylisten.app

# start node application
    yarn start

# update composer dependencies
    composer show --outdated
    composer update

# yarn stuff
    # build production css and js
    yarn run build

    # list outdated dependencies
    yarn outdated

    # security audit
    yarn audit

    # upgrade dependencies
    yarn upgrade
