## RESTful format response for Laravel

### For Laravel 4, please use the [v1.x branch](https://github.com/teepluss/laravel-restable/tree/v1.x)!

Restable is a useful to create RESTful API response format that support multiple format, such as Json, XML
Serialized, PHP.

### Installation

- [Restable on Packagist](https://packagist.org/packages/teepluss/restable)
- [Restable on GitHub](https://github.com/teepluss/laravel-restable)

To get the lastest version of Theme simply require it in your `composer.json` file.

~~~
"teepluss/restable": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Once Theme is installed you need to register the service provider with the application. Open up `config/app.php` and find the `providers` key.

~~~
'providers' => array(

    'Teepluss\Restable\RestableServiceProvider'

)
~~~

Restable also ships with a facade which provides the static syntax for creating collections. You can register the facade in the `aliases` key of your `config/app.php` file.

~~~
'aliases' => array(

    'Restable' => 'Teepluss\Restable\Facades\Restable'

)
~~~

Publish config using artisan CLI.

~~~
php artisan vendor:publish
~~~

## Usage

API RESTful format.

Example:
~~~php

use Teepluss\Restable\Contracts\Restable;

class ApiBlogsController extends BaseController {

    protected $rest;

    /**
     * Checking permission.
     *
     * @return Response
     */
    public function __construct(Restable $rest)
    {
        $this->rest = $rest;

        if ( ! Input::get('secret') == '12345')
        {
            return $this->rest->unauthorized()->render();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Set default response format.
        //$this->rest->setDefaultFormat('xml');

        // Override format response.
        //return $this->rest->listing(Blog::paginate())->to('xml');
        //return $this->rest->listing(Blog::paginate())->toXML();

        return $this->rest->listing(Blog::paginate())->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('api.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $blog = new Blog;

        $validator = Validator::make(Input::all(), array(
            'title'       => 'required',
            'description' => 'required'
        ));

        if ($validator->fails())
        {
            return $this->rest->unprocess($validator)->render();
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return $this->rest->created($blog)->render();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        if ( ! $blog)
        {
            return $this->rest->missing()->render();
        }

        return $this->rest->single($blog)->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);

        if ( ! $blog)
        {
            return $this->rest->missing()->render();
        }

        return View::make('api.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $blog = Blog::find($id);

        if ( ! $blog)
        {
            return $this->rest->missing()->render();
        }

        $validator = Validator::make(Input::all(), array(
            'title'       => 'required',
            'description' => 'required'
        ));

        if ($validator->fails())
        {
            return $this->rest->unprocess($validator)->render();
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return $this->rest->updated($blog)->render();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if ( ! $blog)
        {
            return $this->rest->missing()->render();
        }

        $blog->delete();

        return $this->rest->deleted()->render();
    }

}
~~~

Error cases.
~~~php
// Unauthorized.
Restable::unauthorized()->render();

// Bad request.
Restable::bad()->render();

// Missing, Not found.
Restable::missing()->render();

// Unprocess, Validation Failed.
Restable::unprocess()->render();

// Custom.
Restable::error(null, 429)->render();
~~~

Another success cases.
~~~php
return Restable::success()->render();
~~~

Changing error code.
~~~php
return Restable::code(9001)->bad('message')->render();
~~~

Render to another format.
~~~php
// XML
return Restable::single($data)->render('xml');

// Serialized
return Restable::single($data)->render('serialized');

// PHP
return Restable::single($data)->render('php');

// JSON
return Restable::single($data)->render('json');

// JSONP
return Restable::single($data)->render('json', Input::get('callback'));
// OR
return Restable::single($data)->toJson(Input::get('callback'));
~~~

## Support or Contact

If you have some problem, Contact teepluss@gmail.com


[![Support via PayPal](https://rawgithub.com/chris---/Donation-Badges/master/paypal.jpeg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9GEC8J7FAG6JA)