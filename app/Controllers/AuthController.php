<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthController extends ResourceController
{   

    public function register()
    {   
        $userModel = new UsersModel();
        $db = \Config\Database::connect();

        $rules = [
            'username' => 'required|min_length[8]|is_unique[users.username]',
            "email" => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[customer,agent]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        // save user
        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ];

        $userModel->insert($data);
        $userId = $userModel->insertID();

        // get ID role by role name
        $roleName = $this->request->getVar('role');
        $roleQuery = $db->table('roles')->where('role_name', $roleName)->get()->getRow();
        if (!$roleQuery) {
        return $this->fail("Role tidak valid");
        }

        // save user role
        $db->table('user_roles')->insert([
        'user_id' => $userId,
        'role_id' => $roleQuery->id
        ]);

        return $this->respondCreated(["message" => "User registered successfully"]);
    }

    public function login()
{
    $db = \Config\Database::connect();

    $email = $this->request->getVar('email');
    $password = $this->request->getVar('password');

    // GET USER 
    $user = $db->table('users')
        ->select('users.id, users.email, users.password, roles.role_name AS role')
        ->join('user_roles', 'user_roles.user_id = users.id', 'left')
        ->join('roles', 'roles.id = user_roles.role_id', 'left')
        ->where('users.email', $email)
        ->get()
        ->getRowArray();

    if (!$user || !password_verify($password, $user['password'])) {
        return $this->failUnauthorized("Invalid email or password");
    }

    $key = getenv('JWT_SECRET');
    $payload = [
        'iat' => time(),
        'exp' => time() + 3600,
        'uid' => $user['id'],
        'role' => $user['role'] ?? 'customer' // DEFAULT VALUE
    ];

    $token = JWT::encode($payload, $key, 'HS256');

    return $this->respond(['token' => $token, 'role' => $user['role']]);
}
}
