<?php

class EncoderTest extends PHPUnit_Framework_TestCase
{
    private $encoder;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->encoder = new Encoder;
    }
    
    /**
     * Tear down the test environment.
     */
    public function tearDown()
    {
        $this->encoder = null;
    }
    
    /**
     * Test instance of $this->encoder;
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Encoder', $this->encoder);
    }
}
