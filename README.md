menuplaner
======
Web-Tool für die Berechnung von Menus/rezepten. Inkl. möglichkeitne für Erstellung Einkaufslisten etc.

# Projekt lokal einrichten
* Projekt auschecken `git clone git@github.com:manuelkrieger/menuplaner.git`
* `composer install --dev` ausführen um die Dependencies zu laden und die Konfiguration zu erstellen
* `npm install` ausführen
* `gulp` ausführen

Dieses Projekt unterstützt die [devbox](https://github.com/semabit/vagrant-phpdev). 
Um das Projekt dort zu aktivieren einfach `devbox enable-vhost` ausführen.

Falls das Projekt neu eingerichtet wird, muss die lokale Datenbank noch erstellt werden, dazu folgenden Befehl verwenden:
```bash
php7.0 bin/console doctrine:database:create
```

## Lokale DB aktualisieren
```bash
php7.0 bin/console doctrine:schema:update --force
php7.0 bin/console doctrine:fixtures:load
```

## htaccess kopieren und anpassen
```bash
cp web/.htaccess.dist web/.htaccess 
```

# Deployment
Dieses Projekt unterstützt das Deployment via Git Push.

## Live
```bash
git remote add live ssh://www-data@mtimar02.nine.ch/home/www-data/git/cct.git
```

# Dev-Tools

## Testing
Für das Projekt stehen Tests mit phpunit zur Verfügung

Tests ausführen
```bash
vendor/bin/phpunit
```
Tests ausführen mit Code Coverage Report
```bash
./vendor/bin/phpunit --coverage-html var/report/cc/
```

## phpmetrics
```bash
./vendor/bin/phpmetrics --report-html=var/report/metrics src
```

# Weitere Informationen
findet man im [Wiki](https://github.com/semabit/gk-cct/wiki)
