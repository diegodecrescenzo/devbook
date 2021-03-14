<?php

namespace Source\Models;

use Source\Models\Connect;

class UserModel
{
    public function bootstrap(
        string $firstName,
        string $lastName,
        string $email,
        string $performace = null,
        string $level = null,
        string $technology = null,
        string $password
    ): UserModel {

        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->email = $email;
        $this->performace = $performace;
        $this->level = $level;
        $this->technology = $technology;
        $this->password = password_hash($password, PASSWORD_DEFAULT, ["cost" => 10]);

        $this->create($this);
        return $this;
    }

    public function create($data)
    {
        try {
            $arrData = (array)$data;

            $columns = implode(", ", array_keys($arrData));
            $values = ":" . implode(", :", array_keys($arrData));

            $stmt = \Source\Models\Connect::getInstance()->prepare("INSERT INTO users ({$columns}) VALUES ({$values})");
            $stmt->execute($this->filter($arrData));

            $confirmLink = "https://localhost/devbook/success.html";
            $message = "<h2>Seja bem-vindo(a) ao DevBook {$arrData['first_name']}. Vamos confirmar seu cadastro?</h2>
                        <p>É importante confirmar seu cadastro para ativar as notificações.</p>
                        <p><a title='Confirmar Cadastro' href='{$confirmLink}'>CLIQUE AQUI PARA CONFIRMAR</a></p>";

            (new Email())->bootstrap(
                "Confirme sua conta no DevBook",
                $message,
                $arrData['email'],
                "{$arrData['first_name']} {$arrData['last_name']}"
            )->send();

            header("Location: https://localhost/devbook/confirm.html");


        } catch (\PDOException $exception) {
            var_dump($exception);
            return null;
        }
    }

    public function findByEmail(string $email)
    {
        try {
            $stmt = Connect::getInstance()->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue("email", $email, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

        } catch (\PDOException $exception) {
            var_dump($exception);
        }

    }

    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_STRIPPED));
        }
        return $filter;
    }
}


