<?php

require_once('library/Request.php');
require_once('controller/MainController.php');
require_once('controller/BookController.php');
require_once('model/GoogleAPIsClientModel.php');

class BookControllerTest extends PHPUnit_Framework_TestCase
{

    // Avoid a call to Google API and mock the contents
    const TEST_ON_LOCAL_CONTENT = false;

    public $request;

    public function setUp()
    {
        $request = new Request();

        $request->valid = true;
        $request->verb = 'GET';
        array_push($request->urlElements, '');

        $this->request = $request;
    }

    /**
     * @covers BookController::getAction
     */
    function testIfSystemIsWorkingFromLiveContent()
    {

        $this->assertInstanceOf('Request', $this->request, 'The Request object has not been correctly created!');

        $bc = new BookController();

        //
        // first parameter
        //

        array_push($this->request->urlElements, 'book');
        $res = $bc->getAction($this->request);

        $this->assertArrayHasKey('message', $res);
        $this->assertEquals('Category set to books', $res['message'], 'The first parameter representing {category} doesn\'t exist!');

        //
        // second parameter
        //

        // Mock the content

        if(self::TEST_ON_LOCAL_CONTENT){
            // Create a stub for the GoogleAPIsClientModel class.
            $gapi = $this->getMockBuilder('GoogleAPIsClientModel')
                         ->disableOriginalConstructor()
                         ->getMock();

            $gapi->dataObject = json_decode(file_get_contents('tests/RESTful_result.json'));

            $bc = $this->getMock('BookController');
            // Configure the stub.
            $bc->expects($this->any())
                 ->method('getAction')
                 ->will($this->returnValue((object)$gapi->getDataAsObject()));
        }


        array_push($this->request->urlElements, 'RESTful');
        $res = $bc->getAction($this->request);


        $this->is_structure_object($res);
        // or
        // $this->is_structure_array($res);

        //
        // third parameter
        //

        array_push($this->request->urlElements, '2010');
        $res = $bc->getAction($this->request);

        $this->is_structure_object($res);
        // or
        // $this->is_structure_array($res);
    }


    function is_structure_object($structure){

        $this->assertTrue(is_object($structure));

        foreach ($structure as $key) {
            $this->assertTrue(is_object($key), 'This is not an object');
            $this->assertObjectHasAttribute('kind', $key, 'This kind of object is not complete or invalid');
        }
    }

    function is_structure_array($structure){

        $this->assertTrue(is_array($structure));

        foreach ($structure as $key) {
            $this->assertTrue(is_array($key), 'This is not an object');
            $this->assertArrayHasKey('kind', $key);
        }
    }
}