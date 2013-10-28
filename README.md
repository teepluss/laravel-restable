## RESTful format response for Laravel 4

Restable is a useful to create RESTful response format.

### Installation

- [Restable on Packagist](https://packagist.org/packages/teepluss/restable)
- [Restable on GitHub](https://github.com/teepluss/laravel4-restable)

To get the lastest version of Theme simply require it in your `composer.json` file.

~~~
"teepluss/restable": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Theme is installed you need to register the service provider with the application. Open up `app/config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Teepluss\Restable\RestableServiceProvider'

)
~~~

Restable also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `app/config/app.php` file.

~~~
'aliases' => array(

    'Restable' => 'Teepluss\Restable\Facades\Restable'

)
~~~

Publish config using artisan CLI.

~~~
php artisan config:publish teepluss/restable
~~~

## Usage

Create reponses format for RESTful.

.......

## Support or Contact

If you have some problem, Contact teepluss@gmail.com

<a href='http://www.pledgie.com/campaigns/22201'><img alt='Click here to lend your support to: Donation and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/22201.png?skin_name=chrome' border='0' /></a>
