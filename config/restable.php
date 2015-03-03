<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Retrieves a list of contents
    |--------------------------------------------------------------------------
    |
    | Get multi records.
    |
    */

    'listing' => array(
        'response' => ':response',
        'header'   => 200
    ),


    /*
    |--------------------------------------------------------------------------
    | Retrieves a specific content
    |--------------------------------------------------------------------------
    |
    | Get single record.
    |
    */

    'show' => array(
        'response' => ':response',
        'header'   => 200
    ),


    /*
    |--------------------------------------------------------------------------
    | Creates a new content
    |--------------------------------------------------------------------------
    |
    | Insert record.
    |
    */

    'created' => array(
        'response' => ':response',
        'header'   => 201
    ),


    /*
    |--------------------------------------------------------------------------
    | Updates a specific content
    |--------------------------------------------------------------------------
    |
    | Update record.
    |
    */

    'updated' => array(
        'response' => ':response',
        'header'   => 200
    ),


    /*
    |--------------------------------------------------------------------------
    | Deletes a specific content
    |--------------------------------------------------------------------------
    |
    | Delete record.
    |
    */

    'deleted' => array(
        'response' => null,
        'header'   => 204
    ),

    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    |
    | OK.
    |
    */

    'success' => array(
        'response' => array(
            'message'     => ':response|OK'
        ),
        'header'   => 200
    ),

    /*
    |--------------------------------------------------------------------------
    | Error 400
    |--------------------------------------------------------------------------
    |
    | Bad Request.
    |
    */

    'error_400' => array(
        'response' => array(
            'code'        => 400,
            'message'     => 'Bad Request',
            'description' => ':response|The request was invalid or cannot be otherwise served.'
        ),
        'header'   => 400
    ),

    /*
    |--------------------------------------------------------------------------
    | Error 401
    |--------------------------------------------------------------------------
    |
    | Unauthorized.
    |
    */

    'error_401' => array(
        'response' => array(
            'code'        => 401,
            'message'     => 'Unauthorized',
            'description' => ':response|Authentication credentials were missing or incorrect.'
        ),
        'header'   => 401
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 403
    |--------------------------------------------------------------------------
    |
    | Forbidden.
    |
    */

    'error_403' => array(
        'response' => array(
            'code'        => 403,
            'message'     => 'Forbidden',
            'description' => ':response|The request is understood, but it has been refused or access is not allowed.'
        ),
        'header'   => 403
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 404
    |--------------------------------------------------------------------------
    |
    | Not found.
    |
    */

    'error_404' => array(
        'response' => array(
            'code'        => 404,
            'message'     => 'Not found',
            'description' => ':response|The request was not found.'
        ),
        'header'   => 404
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 405
    |--------------------------------------------------------------------------
    |
    | Method Not Allowed.
    |
    */

    'error_405' => array(
        'response' => array(
            'code'        => 405,
            'message'     => 'Method Not Allowed',
            'description' => ':response|Request method is not allowed.'
        ),
        'header'   => 405
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 406
    |--------------------------------------------------------------------------
    |
    | Not Acceptable.
    |
    */

    'error_406' => array(
        'response' => array(
            'code'        => 406,
            'message'     => 'Not Acceptable',
            'description' => ':response|Returned when an invalid format is specified in the request.'
        ),
        'header'   => 406
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 410
    |--------------------------------------------------------------------------
    |
    | Gone.
    |
    */

    'error_410' => array(
        'response' => array(
            'code'        => 410,
            'message'     => 'Gone',
            'description' => ':response|This resource is gone. Used to indicate that an API endpoint has been turned off.'
        ),
        'header'   => 410
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 422 (Validation error)
    |--------------------------------------------------------------------------
    |
    | Unprocessable Entity.
    |
    */

    'error_422' => array(
        'response' => array(
            'code'        => 422,
            'message'     => 'Unprocessable Entity',
            'description' => 'Data is unable to be processed.',
            'errors'      => ':response'
        ),
        'header'   => 422
    ),


    /*
    |--------------------------------------------------------------------------
    | Error 429
    |--------------------------------------------------------------------------
    |
    | Too Many Requests.
    |
    */

    'error_429' => array(
        'response' => array(
            'code'        => 429,
            'message'     => 'Too Many Requests',
            'description' => ':response|Request cannot be served due to the application\'s rate limit having been exhausted for the resource.'
        ),
        'header'   => 429
    )

);