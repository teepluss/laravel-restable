<?php namespace Teepluss\Restable;

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag as MessageBag;
use Illuminate\Support\Facades\Response as LaravelResponse;
use Abishekrsrikaanth\ExportToAny\ExportToAny;

class Restable {

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    protected $format = 'json';

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
     * API responsed.
     *
     * @var mixed
     */
    protected $returned;

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

    public function setDefaultFormat($format)
    {
        $this->format = $format;
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
                if ($v == ':response') $v = $data;
            });
        }
        else
        {
            $returned['response'] = $data;
        }

        $this->returned = $returned; //$this->response->make($returned['response'], $returned['header']);

        return $this;
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
    public function single($messages)
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
     * Any error return 400 as bad request.
     *
     * @param  mixed  $description
     * @return string
     */
    public function bad($description)
    {
        return $this->error($description, 'bad');
    }

    /**
     * Alias of error 404 response.
     *
     * @param  array  $messages
     * @return string
     */
    public function missing()
    {
        return $this->error(null, 404);
    }

    /**
     * Alias of error 422 response.
     *
     * @param  array  $error
     * @return string
     */
    public function unprocess($errors)
    {
        return $this->error($errors, 422);
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

    public function render($format = null)
    {
        $format = ($format) ?: $this->format;

        $returned = $this->returned;

        if (isset($returned['response']['data']))
        {
            $returned['response']['entries'] = $returned['response']['data'];
            unset($returned['response']['data']);
        }

        switch ($format)
        {
            case 'xml' :
                $data = array(
                    'type'    => 'application/xml',
                    'content' => Format::factory($returned['response'])->toXML()
                );
                break;
            case 'csv' :
                $data = array(
                    'type'    => 'text/plain',
                    'content' => Format::factory($returned['response']['entries'])->toCSV()
                );
                break;
            case 'serialized' :
                $data = array(
                    'type'    => 'text/plain',
                    'content' => Format::factory($returned['response'])->toSerialized()
                );
                break;
            case 'php' :
                $data = array(
                    'type'    => 'text/plain',
                    'content' => Format::factory($returned['response'])->toPHP()
                );
                break;
            default :
                $data = array(
                    'type'    => 'application/json',
                    'content' => Format::factory($returned['response'])->toJson()
                );
                break;
        }

        $response = $this->response->make($data['content'], $returned['header']);

        $response->header('Content-Type', $data['type']);

        return $response;
    }

}