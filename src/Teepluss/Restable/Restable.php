<?php namespace Teepluss\Restable;

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag as MessageBag;
use Illuminate\Support\Facades\Response as LaravelResponse;

class Restable {

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Laravel response.
     *
     * @var \Illuminate\Support\Facades\Response
     */
    protected $response;

    /**
     * Config codes.
     *
     * @var array
     */
    protected $codes = array();

    /**
     * Constructor.
     *
     * @param \Illuminate\Config\Repository        $config
     * @param \Illuminate\Support\Facades\Response $response
     */
    public function __construct(Repository $config, LaravelResponse $response)
    {
        $this->config = $config;

        $this->response = $response;

        // Response format.
        $this->codes = $this->config->get('restable::codes');
    }

    /**
     * Making response.
     *
     * @param  array  $data
     * @param  string $type
     * @return string
     */
    protected function make($data, $type)
    {
        $returned = $this->codes[$type];

        // Change object to be array.
        if (is_object($data))
        {
            $data = $data->toArray();
        }

        // Finding mark point.
        if (is_array($returned['response']))
        {
            array_walk_recursive($returned['response'], function(&$v, $k) use ($data)
            {
                if ($v == ':content') $v = $data;
            });
        }
        else
        {
            $returned['response'] = $data;
        }

        return $this->response->make($returned['response'], $returned['header']);
    }

    /**
     * Response listing.
     *
     * @param  array  $messages
     * @return string
     */
    public function listing($messages)
    {
        $content = $messages;

        return $this->make($content, 'listing');
    }

    /**
     * Response single.
     *
     * @param  array  $messages
     * @return string
     */
    public function show($messages)
    {
        $content = $messages;

        return $this->make($content, 'show');
    }

    /**
     * Response created.
     *
     * @param  array  $messages
     * @return string
     */
    public function created($messages)
    {
        $content = $messages;

        return $this->make($content, 'created');
    }

    /**
     * Response updated.
     *
     * @param  array  $messages
     * @return string
     */
    public function updated($messages)
    {
        $content = $messages;

        return $this->make($content, 'updated');
    }

    /**
     * Response deleted.
     *
     * @param  array  $messages
     * @return string
     */
    public function deleted()
    {
        return $this->make(null, 'deleted');
    }

    /**
     * Response error.
     *
     * @param  array  $messages
     * @return string
     */
    public function error($messages, $type = 422)
    {
        $alias = 'error_'.$type;

        return $this->$alias($messages);
    }

    /**
     * Alias of error 404 response.
     *
     * @param  array  $messages
     * @return string
     */
    public function missing()
    {
        return $this->make(null, 'error_404');
    }

    /**
     * Alias of error 422 response.
     *
     * @param  array  $error
     * @return string
     */
    public function unprocess($errors)
    {
        return $this->error_422($errors);
    }

    /**
     * Validation error.
     *
     * @param  array  $messages
     * @return string
     */
    protected function error_422($messages)
    {
        // Get validation message bag.
        if ( ! $messages instanceOf MessageBag)
        {
            $messages = $messages->getMessageBag();
        }

        $content = array();

        // Re-format error messages.
        foreach ($messages->getMessages() as $field => $message)
        {
            $error = array(
                'field'   => $field,
                'message' => current($message)
            );

            array_push($content, $error);
        }

        return $this->make($content, 'error_422');
    }

    /**
     * Response any error types.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return string
     */
    public function __call($method, $arguments = array())
    {
        if (preg_match('/^error_/', $method))
        {
            $arguments[] = $method;

            return call_user_func_array(array($this, 'make'), $arguments);
        }
    }

}