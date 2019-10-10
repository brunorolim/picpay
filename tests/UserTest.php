<?php

class UserTest extends TestCase
{
    public function test_listarUsuarios()
    {
        $this->get('/users?q=');
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['*' =>
            [
                'id',
                'cpf',
                'email',
                'full_name',
                'password',
                'phone_number'
            ]
        ]);
    }

    public function test_addUser()
    {
        $this->post('/users', [
            'cpf' => '111.111',
            'email' => "aaa@aaa.com.br",
            'full_name' => 'Teste',
            'password' => '123456',
            'phone_number' => '(11) 11111-1111'
        ]);
        $this->seeStatusCode(200);
    }

    public function test_loadUser()
    {
        $this->get('/users/1');
        $this->seeStatusCode(200);
    }
}
