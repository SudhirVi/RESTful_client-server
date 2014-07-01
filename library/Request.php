<?php

/**
 * Object Request that stores the user request
 * and all the related information useful to return
 * a proper response
 */
class Request
{
    /**
     * Flag is the request is valid or not
     *
     * @var boolean
     **/
    public $valid = false;

    /**
     * Elements of the request (of the URL)
     *
     * @var array
     **/
	public $urlElements = array();

    /**
     * Version of the APIs (not currently used)
     *
     * @var string
     **/
	public $ver;

    /**
     * Array of optional parameters sent with the request
     *
     * @var array
     **/
	public $parameters = array();

    /**
    * Get all information about the request sent
    *
    * @return boolean - response data
    */
	public function __construct()
	{
        if (isset($_SERVER['PATH_INFO']) AND trim($_SERVER['PATH_INFO']) != '/') {
            $this->valid = true;
        } else {
            return false;
        }

		$this->verb = htmlentities($_SERVER['REQUEST_METHOD']);
		$this->urlElements = explode('/', $_SERVER['PATH_INFO']);

		// call the method to parse all input params
		$this->parseIncomingParams();

		// initialise json as default format
		$this->format = 'json';

		// set the new format based on the input request
		if (isset($this->parameters['format'])) {
            $this->format = $this->parameters['format'];
        }

        return true;
	}

    /**
    * Manages all incoming requests
    */
	public function parseIncomingParams()
	{
		$parameters = array();

		// pull the GET variables
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }

        // pull PUT/POST bodies from input request
        $body = file_get_contents("php://input");

        // get the requested content type
        $content_type = false;
        if (isset($_SERVER['CONTENT_TYPE'])) {
        	$content_type = $_SERVER['CONTENT_TYPE'];
        }

        //
        switch ($content_type) {
        	case 'application/json':
        		$body_params = json_decode($body);

        		if ($body_params) {
        			foreach ($body_params as $param_name => $param_value) {
        				$parameters[$param_name] = $parameters[$param_value];
        			}
        		}

        		$this->format = 'json';
        		break;
        	case 'application/x-www-form-urlencoded':
        		parse_str($body, $postvars);
        		foreach ($postvars as $field => $value) {
        			$parameters[$field] = $value;
        		}
        		$this->format = 'html';
        		break;
        	default:
        		break;
        }

        $this->parameters = $parameters;
	}
}