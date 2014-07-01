<?php

/**
* Google Books APIs client
*/
class GoogleAPIsClientModel
{
    /**
     * Google API URI
     *
     * @var string
     **/
     private $apiURI = 'https://www.googleapis.com/';
    /**
     * API name
     *
     * @var string
     **/
     public $apiName = 'books';
    /**
     * API version
     *
     * @var string
     **/
     private $apiVersion = 'v1';
    /**
     * API collection name
     *
     * @var string
     **/
     public $collectionName = 'volumes';
    /**
     * API query string
     *
     * @var string
     **/
     private $queryString;

    /**
     * @var array
     */
    public $dataObject = array();

    /**
     * Send a request to the Google Books APIs with a
     * specific {collection} and {keyword}
     *
     * @param string $data - search keyword
     */
    public function __construct($queryString = '')
    {

        if (!ctype_alnum(trim($queryString))) {
            throw new \RuntimeException("Search string required");
        }

        $this->queryString = htmlentities($queryString);

        $this->dataObject = file_get_contents(
                                        $this->apiURI .
                                        $this->apiName .
                                        DIRECTORY_SEPARATOR .
                                        $this->apiVersion .
                                        DIRECTORY_SEPARATOR .
                                        $this->collectionName .
                                        '?q=' .
                                        $this->queryString
                                        );

        $this->dataObject = json_decode($this->dataObject);

        if (!$this->dataObject) {
            throw new \RuntimeException("No data returned");
        }
    }

    /**
     * Gets the number of the results from the  constructor query
     * by reading the returned data stored in an Object
     *
     * @return int - number of results
     */
    public function count()
    {
      return (int)$this->dataObject->totalItems;
    }

    /**
     * Filters the result data by year returning the updated result set
     *
     * @param  int $year - year used to filter the result set
     * @return Object - resulting filtered data set by year in Object format
     */
    public function filterByYear($year)
    {
        $result = array();

        $pattern = '/(\d{4})/';
        preg_match($pattern, $year, $matches);
        if(isset($matches[0]))
            $year = $matches[0];
        else
            $year = null;

        $parsedDate = date_parse("$year-01-01");

        if (!ctype_digit($year) or !$parsedDate['year']) {
            return;
        }

        foreach ($this->dataObject->items as $key => $value) {
            if(substr($value->volumeInfo->publishedDate, 0, 4) == $year)
                $result[] = $value;
        }

        $this->dataObject = $result;
    }

    /**
     * Get the entire collection as Object
     *
     * @return Object as array - converts the data in Object format
     **/
    public function getDataAsObject()
    {
        $result = (object)$this->dataObject;

        if(property_exists($result, 'items'))
            return (object)$result->items;
        else
            return (object)$result;
    }

    /**
     * Get the entire collection as Array
     *
     * @return Object as array - converts the data in Array format
     **/
    public function getDataAsArray()
    {
        $result = $this->dataObject;

        if(is_object($this->dataObject) and property_exists($this->dataObject, 'items'))
            $result = (array)$this->objectToArray($this->dataObject->items);
        else{
            $result = (array)$this->objectToArray($this->dataObject);
        }

        return $result;
    }


    /**
     * Recursive method to convert an Object to array
     *
     * @param object - Object to be converted
     * @return array - Array converted from Object format
     **/
    function objectToArray($obj) {

        if(!is_array($obj) && !is_object($obj))
            return $obj;

        if(is_object($obj))
            $obj = get_object_vars($obj);

        return array_map("self::objectToArray", $obj);
    }
}