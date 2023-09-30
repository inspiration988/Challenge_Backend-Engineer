<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirement
-  PHP >= 7.4
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Install
 Clone from https://github.com/inspiration988/Challenge_Backend-Engineer.git
```bash
   git clone https://github.com/inspiration988/Challenge_Backend-Engineer.git .
```
 Run composer install
```bash
   composer install
```

## Database
The Json file is used as a database and is located at the following address :
```bash
./storage/app/db.json
```

## Run API
- In root directory run  : 
```bash
php artisan serve
```

- Open url [http://127.0.0.1:8000/products] in your browser

## Optional Params
- Fitler by category : http://127.0.0.1:8000/products?category={category_name}
- Fitler by price less than : http://127.0.0.1:8000/products?priceLessThan={price}
- Sort product by : http://127.0.0.1:8000/products?sort={sku,category,name,price}
- Pagination : http://127.0.0.1:8000/products?page={1,2,3,...}

## Testing 
To run all tests:
 ```bash
 php artisan test
```
