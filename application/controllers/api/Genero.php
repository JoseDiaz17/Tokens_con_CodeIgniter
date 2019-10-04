<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'third_party/Format.php';
include_once(APPPATH . 'third_party/REST_Controller.php');
include_once(APPPATH . 'third_party/Format.php');

class Genero extends REST_Controller
{

    public function __construct()
    {
        parent::__construct("rest");
        header("Access-Control-Allow-Origin: *");//permite el acceso a los recursos
        header("Access-Control-Allow-Headers: X-API-KEY, ORIGIN, X-Requested-With, Content, DELETE");//contenidos aceptados
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");//permite diversos métodos de conexión
        header('Authorization');
    }

    public function index_options()
    {
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    public function index_get($id = null)
    {
        //si la variable no va vacia entonces busca por id
        if (!empty($id)) {
            $data = $this->db->get_where("genero", ['id' => $id])->row_array();
            //si la consulta no devuelve nada 
            if ($data == null) {
                $this->response(["El registro con ID $id no existe!"], REST_Controller::HTTP_NOT_FOUND);
                return;
            }
        //devuelve todos los resultados    
        } else {
            $data = $this->db->get("genero")->result_array();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        //Validar que los campos no vallan vacios
        if (empty($this->post('titulo'))) {
            $this->response(["No puedes mandar los campos vacios!"], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $data = ['titulo' => $this->post('titulo')];
        $this->db->insert('genero', $data);
        $this->response($data, REST_Controller::HTTP_CREATED);
    }

    public function index_put()
    {
        $id=$this->put('id');
        $titulo=$this->put('titulo');
        //Validar que los campos no vallan vacios
        if (empty($titulo)) {
            $this->response(["No puedes mandar los campos vacios!"], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $data = ['id' =>$id,'titulo' =>$titulo];
        $this->db->update('genero', $data, array('id' => $id));
        $this->response("Registro actualizado", REST_Controller::HTTP_OK);
    }

    public function index_delete($id = null)
    {
        //Validar que el id exista
        if (empty($id)) {
            $this->response(["No puedes mandar el id vacio!"], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $this->db->delete('genero', array('id' => $id));
        $algo = $this->db->affected_rows();
        //valida con la ayuda de la funcion affected_rows si alguna fila a sido afectada o no
        if ($algo === 0) {
            $this->response(["El registro con ID $id no existe!"], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $this->response(["Registro ID $id Eliminado"], REST_Controller::HTTP_OK);
    }
}
