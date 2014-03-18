<?php

/**
 * @group Model
 */

class SomeModelTest extends CIUnit_TestCase
{
    protected $tables = array(
        'admins' => 'admins_fixt',
    );
    
    private $_adm;

    public function __contruct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    
    public function setUp()
    {
        parent::tearDown();
        parent::setUp();
        
        /*
        * this is an example of how you would load a product model,
        * load fixture data into the test database (assuming you have the fixture yaml files filled with data for your tables),
        * and use the fixture instance variable
        
        */
        $this->CI->load->model('admins_model');
        $this->_adm=$this->CI->admins_model;
        $this->dbfixt($tables);
        
        /* 
         * the fixtures are now available in the database and so:
         * $this->users_fixt;
         * $this->products_fixt;
         */
        
    }
    /**
     * @dataProvider testGetAdminsData
     */
    public function testGetAdmins(array $attributes, $expected)
    {
        $actual = $this->_adm->id(1);
        
        $this->assertEquals($expected, count($actual));
    }
    public function testGetAdminsData()
    {
        return array(
            array(array('nome'), 1)
        );
    }
}