<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Usuario extends REST_Controller 
{
    public function __construct()
    {
        parent::__construct();
        // Cargue estos ayudantes para crear tokens JWT
        $this->load->helper(['jwt', 'authorization']);
    }

    public function index_post(){
        //obtenemos los datos que envia el cliente
        $nombre = $this->post('nombre');
        $password = $this->post('password');
        //buscamos el usuario en la base de datos
        $dataDB = $this->db->get_where("usuario", ['nombre'=>$nombre])->row_array();
        //se comparan los datos que envio el cliente con los obtenidos de la base de datos
        if ($nombre == $dataDB['nombre'] && $password == $dataDB['password']) {
            //se crea un token con los datos recibidos
            $token = AUTHORIZATION::generateToken(['nombre' => $nombre]);
            //se crea una respuesta que contiene el token y el estado 201 
            $respuesta = ['status' => parent::HTTP_CREATED, 'token' => $token];
            //se envia la respuesta
            $this->response($respuesta, parent::HTTP_OK);
        } else {
            $this->response(['mensaje' => 'El usuario o contraseña son incorrectos'], parent::HTTP_NOT_FOUND);
        }
    }

    private function verify_request(){
        $headers = $this->input->request_headers();
        $token = $headers['Authorization'];
        try {                                                  
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Acceso no autorizado'];
                $this->response($response, $status);
            } else {
                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'data' => $data];
                $this->response($response, $status);
            }
        } catch (Exception $e) {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Acceso no autorizado'];
            $this->response($response, $status);
        }
    }
}
?>

