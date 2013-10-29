## RESTful format response for Laravel 4

Restable is a useful to create RESTful API response format.

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

Example:
~~~php
class ApiBlogsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Restable::listing(Blog::paginate());
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
            return Restable::unprocess($validator);
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return Restable::created($blog);
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
            return Restable::missing();
        }

        return Restable::show($blog);
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
            return Restable::missing();
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
            return Restable::missing();
        }

        $validator = Validator::make(Input::all(), array(
            'title'       => 'required',
            'description' => 'required'
        ));

        if ($validator->fails())
        {
            return Restable::unprocess($validator);
        }

        $blog->title = Input::get('title');
        $blog->description = Input::get('description');

        $blog->save();

        return Restable::updated($blog);
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
            return Restable::missing();
        }

        $blog->delete();

        return Restable::deleted();
    }

}
~~~

## Support or Contact

If you have some problem, Contact teepluss@gmail.com

<a href='http://www.pledgie.com/campaigns/22201'><img alt='Click here to lend your support to: Donation and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/22201.png?skin_name=chrome' border='0' /></a>
