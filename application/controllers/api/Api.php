<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Cargue estos ayudantes para crear tokens JWT
        $this->load->helper(['jwt', 'authorization']);
    }

    public function hello_get()
    {
        $tokenData = 'Hello World!';

        // Crear un token
        $token = AUTHORIZATION::generateToken($tokenData);
        // Establecer código de estado HTTP
        $status = parent::HTTP_OK;
        // Prepara la respuesta
        $response = ['status' => $status, 'token' => $token];
        // REST_Controller proporciona este método para enviar respuestas
        $this->response($response, $status);
    }

    public function login_post()
    {
        // Tener detalles de usuario ficticios para verificar las credenciales del usuario
        $dummy_user = [
            'username' => 'jochi',
            'password' => '123'
        ];
        // Extrae datos de usuario de la solicitud POST
        $username = $this->post('username');
        $password = $this->post('password');
        // Comprobar si el usuario es válido
        if ($username === $dummy_user['username'] && $password === $dummy_user['password']) {

            // Cree un token a partir de los datos del usuario y envíelo como respuesta
            $token = AUTHORIZATION::generateToken(['username' => $dummy_user['username']]);
            // Prepara la respuesta
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            $this->response($response, $status);
        } else {
            $this->response(['msg' => 'Nombre de usuario o contraseña inválidos!'], parent::HTTP_NOT_FOUND);
        }
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
                $response = ['status' => $status, 'msg' => 'Acceso no autorizado!'];
                $this->response($response, $status);
                exit();
            } else {
                return $data;
            }
        } catch (Exception $e) {
            // El token no es válido
            // Enviar el mensaje de acceso no autorizado
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Acceso no autorizado! '];
            $this->response($response, $status);
        }
    }

    public function get_me_data_get()
{
    // Llame al método de verificación y almacene el valor de retorno en la variable
    $data = $this->verify_request();
    // // Enviar los datos de devolución como respuesta
    $status = parent::HTTP_OK;
    $response = ['status' => $status, 'data' => $data];
    $this->response($response, $status);
}
}

