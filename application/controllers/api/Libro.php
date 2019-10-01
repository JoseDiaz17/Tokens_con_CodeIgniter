<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'third_party/Format.php';

include_once(APPPATH . 'third_party/REST_Controller.php');
include_once(APPPATH . 'third_party/Format.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, ORIGIN, X-Requested-With, Content, DELETE");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

//URL: http://localhost/Tokens_con_CodeIgniter/index.php/api/Genero/

class libro extends REST_Controller
{

    public function __construct()
    {
        parent::__construct("rest");
        // Cargue estos ayudantes para crear tokens JWT
        $this->load->helper(['jwt', 'authorization']);
    }

    public function index_options()
    {
        return $this->response(NULL, REST_Controller::HTTP_OK);
    }

    private function verify_request()
    {
        // Obtener todos los encabezados
        $headers = $this->input->request_headers();
        // Extrae el token
        $token = $headers['Authorization'];
        // Usa try-catch
        // La biblioteca JWT lanza una excepción si el token no es válido
        try {
            //Validar el token
            // La validación exitosa devolverá los datos de usuario decodificados; de lo contrario, devolverá false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
                $this->response($response, $status);
            } else {
                return $data;
            }
        } catch (Exception $e) {
            // El token no es válido
            // Enviar el mensaje de acceso no autorizado
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
            $this->response($response, $status);
        }
    }

    public function index_get($isbn = null)
    {
        $data = $this->verify_request();
        if ($data == true) {
            if (!empty($isbn)) {
                $this->db->select('l.*, g.titulo as genero')->from('libro as l')->join('genero as g', 'l.genero = g.id');
                $data = $this->db->get_where("libro", ['l.isbn' => $isbn])->row_array();
                if ($data == null) {
                    $this->response(["El registro con ID $isbn no existe"], REST_Controller::HTTP_NOT_FOUND);
                    return;
                }
            } else {
                $this->db->select('l.*, g.titulo as genero')->from('libro as l')->join('genero as g', 'l.genero = g.id');
                $data = $this->db->get()->result();
            }
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
            $this->response($response, $status);
        }
    }

    public function index_post()
    {
        $data = $this->verify_request();
        if ($data == true) {
            $data = [
                'isbn' => $this->post('isbn'),
                'titulo' => $this->post('titulo'),
                'autor' => $this->post('autor'),
                'genero' => $this->post('genero')
            ];
            $this->db->insert('libro', $data);
            $this->response($data, REST_Controller::HTTP_CREATED);
        } else {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
            $this->response($response, $status);
        }
    }

    public function index_put($isbn)
    {
        $data = $this->verify_request();
        if ($data == true) {
            $data = $this->put();
            $this->db->update('libro', $data, array('isbn' => $isbn));
            $this->response("Registro actualizado", REST_Controller::HTTP_OK);
        } else {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
            $this->response($response, $status);
        }
    }

    public function index_delete($isbn)
    {
        $data = $this->verify_request();
        if ($data == true) {
            $this->db->delete('libro', array('isbn' => $isbn));
            $this->response("Registro eliminado", REST_Controller::HTTP_OK);
        } else {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['Estado' => $status, 'Mensaje' => 'Acceso no autorizado!'];
            $this->response($response, $status);
        }
    }
}
