<?php

namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpParser\Node\Stmt\TryCatch;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('AUTHORIZATION');
        if(!$header){
            return response()->setJSON(['messege' => "Authorization header not found"])->setStatusCode(401);
        }

        $token = explode(' ',$header)[1] ?? null;
        try{
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($key,'HS256'));

            $request->user =$decoded;

            session()->set('role',$decoded->role);
        }catch(\Exception$e){
            return response() -> setJSON(["message" => "invalid Token"])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}