<p text-align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p text-align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

#  Parkplatzverwaltung
<p text-align="center"><a href="#" target="_blank"><img src="https://www.ginstr.com/wp-content/uploads/2017/09/smartParkingManager_GAS_appIcon-193x193.png?x31442" width="400" alt="Laravel Logo"></a></p>

##  Installation

### Schritt 1 – Apache Web Server installieren
Aktualisieren Sie alle verfügbaren Repositories auf Ihrem System und installieren Sie den Apache-Webserver mit dem untenstehenden apt-Befehl.
```
sudo apt update
sudo apt install apache2
```

Sobald die gesamte Installation abgeschlossen ist, starten Sie den Apache-Dienst und fügen ihn dem Systemstart hinzu.
```
systemctl start apache2
systemctl enable apache2
```

Überprüfen Sie nun den Apache-Dienststatus mit dem folgenden Befehl.
```
systemctl status apache
```

Fügen Sie dann die SSH-, HTTP- und HTTPS-Dienste mit dem folgenden Befehl zur UFW-Firewall hinzu.
```
for svc in ssh http https
do 
ufw allow $svc
done
```

Aktivieren Sie jetzt die UFW-Firewall-Dienste.
```
sudo ufw enable
```

Geben Sie ‚y‚ ein, um fortzufahren, und die UFW-Firewall wurde aktiviert.
Öffnen Sie dann Ihren Webbrowser und geben Sie die IP-Adresse Ihres Servers in die Adressleiste ein und Sie erhalten die Standard Apache index.html Seite.
***
### Schritt 2 – Installieren und Konfigurieren von PHP 8.1
Die folgenden Abhängigkeiten müssen installiert werden, um PHP erfolgreich zu installieren.
``` 
sudo apt install software-properties-common apt-transport-https -y
sudo add-apt-repository ppa:ondrej/php -y
```

Sobald dies erledigt ist, ist es gut, Ihre APT-Repositories zu aktualisieren
```
sudo apt update
sudo apt upgrade
```

Installieren Sie PHP 8.1 mit Apache Option und starten Sie den Apache-Server neu
````
sudo apt install php8.1 libapache2-mod-php8.1
sudo systemctl restart apache2
````

Prüfen Sie die installierte PHP_Version
````
php --version
````

Laravel benötigt folgende PHP-Module - Server Requirements.



    PHP >= 8.0
    BCMath PHP Extension
    Ctype PHP Extension
    cURL PHP Extension
    DOM PHP Extension
    Fileinfo PHP Extension
    JSON PHP Extension
    Mbstring PHP Extension
    OpenSSL PHP Extension
    PCRE PHP Extension
    PDO PHP Extension
    Tokenizer PHP Extension
    XML PHP Extension



````
sudo apt-get install -y php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath
````
Apache-Dienst neu starten
````
systemctl restart apache2
````
***
### Schritt 3 – Composer PHP-Paketverwaltung installieren
Laden Sie die Binärdatei des Composers herunter und verschieben Sie die Datei in das Verzeichnis ‚/usr/local/bin‘.
````
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
````

Überprüfen Sie danach die Version des Komponisten
````
composer --version
````
***
### Schritt 4 Datenbank anlegen
Installation
````angular2html
sudo apt-get install mariadb-server 
````

Bei __mariadb__ einloggen und einen Benutzer 'Parklatzadmin' erstellen und die Datenbank 'parkplatzverwaltung' anlegen
````
sudo mysql -u root 
CREATE USER 'Parkplatzadmin'@'localhost' IDENTIFIED BY 'parkplatzadmin';
CREATE DATABASE parkplatzverwaltung
GRANT ALL PRIVILEGES ON parkplatzverwaltung.* TO 'parkplatzadmin'@'localhost';
FLUSH PRIVILEGES;
````

***
## Parkplatzverwaltung installieren
wechseln Sie in das Verzeichnis /var/www/html und laden Sie sich das Parkplatzverwaltungspacket herunter
````
git clone https://github.com/luzumi/parkplatzverwaltung/tree/Eloquent
````
wechseln Sie ins Rootverzeichnis des Programms ('Parkplatzverwaltung')
Erstellen Sie eine '.env' Datei
````
cp .env.example .env
````

Packete installieren
````
composer install --optimize-autoloader --no-dev
composer update
````

**App-Key** erstellen
````
php artisan key:generate
````

**npm** installieren
````
npm install
npm run prod
````

Optimieren der Konfigurationen
````
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
````

Migration der Datenbanken
```
php artisan migrate
```

***
## Starten der Parkplatzverwaltung
starten Sie einen Server 
````
php artisan serve
````

starten sie die parkplatzverwaltung im Webbrwoser unter der Adresse
````
'serveradresse':8000
````





***

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
