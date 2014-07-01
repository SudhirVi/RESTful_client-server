<?php

/**
 * Main controller extended by all other controllers
 */
class MainController
{
    /**
    * Manages all the POST requests
    *
    * @param Request $request - Request object passed as parameter
    * @return Array $data - response data
    */
    public function postAction(Request $request)
    {
        $data = $request->parameters;
        $data['message'] = 'POST requests are not available';

        return $data;
    }
}