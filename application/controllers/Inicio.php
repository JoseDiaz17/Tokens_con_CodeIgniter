<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inicio extends CI_Controller
{
	//Metodo Constructor
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$titulo["titulo"]="Bienvenidos al controlador Usuarios";
		$this->load->view('Inicio');
	}
}
