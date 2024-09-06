<?php

use PHPUnit\Framework\TestCase;

include_once ('./controller/Controller.php');

final class SanitizeTest extends TestCase
{
    function testSanitizeRemovesExtraSpaces()
    {
        $controller = new Controller();
        $input = '  hello world  ';
        $expected = 'hello world';
        $this->assertEquals($expected, $controller->sanitize($input));
    }
    
    public function testSanitizeRemovesSlashes()
    {
        $controller = new Controller();
        $input = 'hello\\world';
        $expected = 'helloworld';
        $this->assertEquals($expected,  $controller->sanitize($input));
    }
    
    public function testSanitizeEscapesHtml()
    {
        $controller = new Controller();
        $input = '<script>alert("test");</script>';
        $expected = '&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;';
        $this->assertEquals($expected,  $controller->sanitize($input));
    }
    
    public function testSanitizeHandlesMixedInput()
    {
        $controller = new Controller();
        $input = ' <b>Hello</b> \\ World ';
        $expected = '&lt;b&gt;Hello&lt;/b&gt;  World';
        $this->assertEquals($expected,  $controller->sanitize($input));
    }
}
