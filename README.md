## RESTful format response for Laravel

Restable is a useful to create RESTful API response format that support multiple format, such as Json, XML
Serialized, PHP.

### Installation

- [Restable on Packagist](https://packagist.org/packages/teepluss/restable)
- [Restable on GitHub](https://github.com/teepluss/laravel4-restable)

To get the lastest version of Theme simply require it in your `composer.json` file.

~~~
"teepluss/restable": "dev-develop"
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

Example:
~~~php
class ApiBlogsController extends BaseController {

    /**
     * Checking permission.
     *
     * @return Response
     */
    public function __construct()
    {
        if ( ! Input::get() == '12345')
        {
            return Restable::unauthorized()->render();
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
        //Restable::setDefaultFormat('xml');

        // Override format response.
        //return Restable::listing(Blog::paginate())->to('xml');
        //return Restable::listing(Blog::paginate())->toXML();

        return Restable::listing(Blog::paginate())->render();
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
            return Restable::unprocess($validator)->render();
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return Restable::created($blog)->render();
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
            return Restable::missing()->render();
        }

        return Restable::single($blog)->render();
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
            return Restable::missing()->render();
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
            return Restable::missing()->render();
        }

        $validator = Validator::make(Input::all(), array(
            'title'       => 'required',
            'description' => 'required'
        ));

        if ($validator->fails())
        {
            return Restable::unprocess($validator)->render();
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return Restable::updated($blog)->render();
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
            return Restable::missing()->render();
        }

        $blog->delete();

        return Restable::deleted()->render();
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