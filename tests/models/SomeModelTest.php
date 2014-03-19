<?php

/**
 * @group Model
 */

class SomeModelTest extends CIUnit_TestCase
{
    protected $tables = array(
        // 'admins' => 'admins',
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
        $this->dbfixt(array('admins',));
        
        /* 
         * the fixtures are now available in the database and so:
         * $this->users_fixt;
         * $this->products_fixt;
         */
        
    }
    /**
     * @dataProvider testGetAdminData
     */
    public function testGetAdmin(array $attributes, $expected)
    {
        $registro = $this->_adm->id($attributes['id']);
        
        $this->assertEquals($expected, $registro->$attributes['coluna']);
    }
    public function testGetAdminData()
    {
        return array(
            array(array('id' => 1, 'coluna' => 'nome'), 'Admin'),
            array(array('id' => 1, 'coluna' => 'login'), 'admin'),
            array(array('id' => 2, 'coluna' => 'nome'), 'GG2'),
        );
    }
    /**
     * @dataProvider testGetListAdminsData
     */
    public function testGetListAdmins(array $attributes, $expected)
    {
    	$itens = $this->_adm->lista($attributes);
        $this->assertEquals($expected, $itens['num_itens']);
    }
    public function testGetListAdminsData()
    {
        return array(
            array(array('ativo' => 'S'), 2),
            array(array('id > 1'), 1),
            array(array('nome like "G%"'), 1),
            array(array('ativo' => 'N'), 0),
        );
    }
}