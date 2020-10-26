## Wymagania

PHP 7+
Laravel 8.0 <a href="https://laravel.com/docs/8.x">Manual</a>
Git
Composer
Mysql
Xampp
Postman

# Komendy aby sprawdzić czy wszystko jest zainstalowane 

php -v
composer -V
git -v


## Instalacja projektu
Komendy w terminalu:
1.git clone https://github.com/Piotr-Jakowicki/Beesfund-rekrutacja.git
2.composer intsall
3.cp .env.example .env
4.php artisan key:generate

Odpowiednie zmiany w pliku .env

DB_CONNECTION=mysql 
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<nazwa bazy>
DB_USERNAME=<nick mysql>
DB_PASSWORD=<hasło mysql>

np.

DB_CONNECTION=mysql 
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beesfund
DB_USERNAME=root
DB_PASSWORD=

w localhost/phpmyadmin stworzyć bazę o takiej samej nazwie jak w DB_DATABASE

Migracja bazy danych w terminalu:
aby zrobić migrację xampp musi być włączony [Apache i MySQL]

w konsoli:
php artisan migrate:fresh 

ewentualnie

php artisan migrate:fresh --seed 

aby wygenerować dummy text w bazie

lub użyć pliku .sql który znajduje się w głownym katalogu

## Server

W terminalu:
php artisan serve

lub 

php artisan serve [--port] 

aby sprecyzować port

## Dodatkowo

Błąd walidacji zmieniłbym z 405 na 422 oraz
Endpointy z /project && /reward na /projects && /rewards

## Kod

Głowny kod znajduje się w :
- app/Controllers/API
- app/Gateways/

Poboczny kod w :
- app/Resource
- app/Models
- database/migrations
- database/factories
- app/Exceptions

## Opis projektu 

# Endpoint /reward 

-Możliwość dodania tylko jednego zasobu jednocześnie
-projectId,name,description,amount są wymagane 
-id jest polem opcjonalnym, jeżeli nie zostanie podane id zostanie wygenerowane automatycznie

# Endpoint /project POST

Project:
-Możliwośc dodania tylko jednego zasobu typu project jednocześnie 
-name,descriptiont są wymagane
-id jest polem opcjonalnym, jeżeli nie zostanie podane id zostanie wygenerowane automatycznie
-jeżeli status nie zostanie podany, w bazie zapiszę się jako null

Rewards:
-Pole rewards jest opcjonalne
-Możliwość dodania wielu zasobów typu reward jednocześnie
-id jest polem opcjonalnym, jeżeli nie zostanie podane id zostanie wygenerowane automatycznie
-walidacja rewards odbywa się tylko jeżeli istnieje przynajmniej 1 obiekt typu reward
-projectId,name,description,amount są wymagane 

# Endpoint /project PUT

Project:
-id,name,descrption są wymagane
-jeżeli status nie zostanie podany wartość nie zostanie zmieniona 

Reward:
-edycja możliwa tylko jeżeli reward należy do project
-Możliwośc edytowania wielu zasobów typu reward jednocześnie
-Jeżeli id nie zostanie podane zostanie stworzony nowy zasób typu reward
-Jeżeli id jest podane zasób typu reward zostanie edytowany

# Endpoint /project/{projectId} GET

-Endpoint jest cachowany na 30 sekund

# Endpoint /project /findByStatus GET

-Możliwość szukania po wielu filtrach
-Endpoint jest cachowany na 30 sekund

## W razie problemów z projektem lub pytań proszę o wiadomość na email jakowicki.piotr@gmail.com