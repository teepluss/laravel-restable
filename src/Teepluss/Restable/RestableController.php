<?php namespace Teepluss\Restable;

use Illuminate\Support\MessageBag as MessageBag;

class RestableController extends \BaseController {

    // protected function processResponse($router, $method, $response)
    // {
    //     $response = parent::processResponse($router, $method, $response);

    //     return \Response::json($response);
    // }

    public function missingMethod($parameters)
    {

    }

    public function error($messages)
    {
        if ( ! $messages instanceOf MessageBag)
        {
            $messages = $messages->getMessageBag();
        }

        $errors = array();

        foreach ($messages->getMessages() as $field => $message)
        {
            $error = array(
                'field'   => $field,
                'message' => current($message)
            );

            array_push($errors, $error);
        }

        $response = array(
            'message' => 'Validation Failed',
            'errors'  => $errors
        );

        return $response;
    }

}