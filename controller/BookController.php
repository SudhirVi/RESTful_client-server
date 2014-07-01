<?php

/**
 * Book controller that returns, based on the request (context switching),
 * information about books
 */
class BookController extends MainController
{

	/**
	* Manages all the GET requests
	*
 	* @param Request $request - Request object passed as parameter
 	* @return Object (or Array) $data - response data
 	*/
	public function getAction(Request $request)
	{
		$data = array();

		if (isset($request->urlElements[2]) AND ctype_alnum($request->urlElements[2])) {

			$keyword = $request->urlElements[2];

			// $gapi = new GoogleAPIsClientModel($keyword);
			// or
			$lcsv = new LocalCSVModel('tests/RESTful_result.csv');

			if (isset($request->urlElements[3]) AND $request->urlElements[3] != '') {

				if (ctype_digit($request->urlElements[3])) {
					//$gapi->filterByYear($request->urlElements[3]);
					// or
					$lcsv->filterByYear($request->urlElements[3]);

					$data = $lcsv->getDataAsObject();
					// or
					// $data = $lcsv->getDataAsArray();
					// or
					// $data['message'] = 'here are the info for books filtered by year "' . htmlentities($request->urlElements[3]) . '"';
				}
			} else {
				$data = $lcsv->getDataAsObject();
				// or
				// $data = $lcsv->getDataAsArray();
				// or
				// $data['message'] = 'here are the info for books found with the keyword "' . $keyword . '"';
			}
		} else {
			$data['message'] = 'Category set to books';
		}

		return $data;
	}
}