<?php namespace Teepluss\Restable;

use Teepluss\Restable\Format;
use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Contracts\MessageProviderInterface;
use Illuminate\Support\Facades\Response as LaravelResponse;

class Restable {

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Format to response.
     *
     * @var string
     */
    protected $format = 'json';

    /**
     * Laravel response.
     *
     * @var \Illuminate\Support\Facades\Response
     */
    protected $response;

    /**
     * Converter.
     *
     * @var \Teepluss\Restable\Format
     */
    protected $converter;

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
     * @param \Teepluss\Restable\Format            $converter
     */
    public function __construct(Repository $config, LaravelResponse $response, Format $converter)
    {
        $this->config = $config;

        $this->response = $response;

        $this->converter = $converter;

        // Response format.
        $this->codes = $this->config->get('restable::codes');
    }

    /**
     * Set default response format.
     *
     * @param string $format
     */
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

        $this->returned = $returned;

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
        // Get message bag from validator.
        if ($messages instanceof MessageProviderInterface)
        {
            $messages = $messages->getMessageBag();
        }

        // Get validation message bag.
        if ( ! $messages instanceOf MessageBag)
        {
            $messages = new MessageBag($messages);
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
     * Render response with format.
     *
     * @param  string $format
     * @return mixed
     */
    public function render($format = null)
    {
        $format = ($format) ?: $this->format;

        $returned = $this->returned;

        switch ($format)
        {
            case 'xml' :

                if (isset($returned['response']['data']))
                {
                    $returned['response']['entries'] = $returned['response']['data'];
                    unset($returned['response']['data']);
                }

                $data = array(
                    'type'    => 'application/xml',
                    'content' => $this->converter->factory($returned['response'])->toXML()
                );
                break;

            case 'php' :
                $data = array(
                    'type'    => 'text/plain',
                    'content' => $this->converter->factory($returned['response'])->toPHP()
                );
                break;

            case 'serialized' :
                $data = array(
                    'type'    => 'text/plain',
                    'content' => $this->converter->factory($returned['response'])->toSerialized()
                );
                break;
            case 'json' :
            default :
                $data = array(
                    'type'    => 'application/json',
                    'content' => $this->converter->factory($returned['response'])->toJson()
                );
                break;
        }

        // Making response.
        $response = $this->response->make($data['content'], $returned['header']);

        // Set content header.
        $response->header('Content-Type', $data['type']);

        return $response;
    }

    /**
     * Alias of render.
     *
     * @param  string $format
     * @return mixed
     */
    public function to($format)
    {
        return $this->render($format);
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
        // Make error response.
        if (preg_match('/^error_/', $method))
        {
            $arguments[] = $method;

            return call_user_func_array(array($this, 'make'), $arguments);
        }

        // Return response with specific format.
        if (preg_match('/^to(.*)$/', $method, $matches))
        {
            $format = strtolower($matches[1]);

            if (in_array($format, array('json', 'xml', 'php', 'serialized')))
            {
                return $this->to($format);
            }
        }
    }

}