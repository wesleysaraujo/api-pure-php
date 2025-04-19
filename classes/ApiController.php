<?php

require_once 'Database.php';
class ApiController
{
    private $db;

    public function __construct()
    {
        $this->connectDb();
    }

    private function connectDb()
    {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function getUsers($id = null)
    {
        try {
            if ($id) {
                $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
                $stmt->execute([$id]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (!$user) {
                    responseJson(
                        [
                            'error' => 'Usuário não encontrado.'
                        ],
                        404
                    );
                }

                responseJson($user);
            } else {
                $stmt = $this->db->query('SELECT * FROM users');
                $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                responseJson($users);
            }
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => 'Erro ao buscar usuários' . $e->getMessage()
                ],
                500
            );
        }
    }

    public function createUser ($data)
    {
        if (!isset($data['name']) || !isset($data['email'])) {
            responseJson(
                [
                    'error' => 'Nome e email são obrigatórios.'
                ],
                400
            );
        }

        try {
            $createdAtNow = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare('INSERT INTO users (name, email, created_at) VALUES (?, ?, ?)');
            $stmt->execute([$data['name'], $data['email'], $createdAtNow]);

            $userId = $this->db->lastInsertId();
            responseJson($this->getUsers($userId), 201);
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => 'Erro ao criar usuário: ' . $e->getMessage()
                ],
                500
            );
        }
    }

    public function updateUser(int $id, array $data)
    {
        if (!$id) {
            responseJson(
                [
                    'error' => 'ID do usuário é obrigatório.'
                ],
                400
            );
        }

        $checkStmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $checkStmt->execute([$id]);

        if (!$checkStmt->fetch()) {
            responseJson(
                [
                    'error' => 'Usuário não encontrado.'
                ],
                404
            );
        }

        $updateFields = [];
        $updateFieldsValues = [];

        foreach ($data as $field => $value) {
            if (in_array($field, ['name', 'email'])) {
                $updateFields[] = $field . ' = ?';
                $updateFieldsValues[] = $value;
            }
        }

        if (empty($updateFields)) {
            responseJson(
                [
                    'error' => 'Nenhum campo válido para atualizar.'
                ],
                400
            );
        }
        $updateFields = array_merge($updateFields, ['updated_at = ?']);
        $updateFieldsValues = array_merge( $updateFieldsValues, (array)(date('Y-m-d H:i:s')));
        $updateFieldsValues[] = $id;

        try {
            $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $updateFields ) . ' WHERE id = ?');
            $stmt->execute($updateFieldsValues);

            responseJson(
                [
                    'message' => 'Usuário atualizado com sucesso.'
                ]
            );
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => 'Erro ao atualizar usuário: ' . $e->getMessage()
                ],
                500
            );
        }
    }

    public function deleteUser($id)
    {
        if (!$id) {
            responseJson(
                [
                    'error' => 'ID do usuário é obrigatório.'
                ],
                400
            );
        }

        $checkStmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $checkStmt->execute([$id]);

        if (!$checkStmt->fetch()) {
            responseJson(
                [
                    'error' => 'Usuário não encontrado.'
                ],
                404
            );
        }

        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);

            responseJson(
                [
                    'message' => 'Usuário excluído com sucesso.'
                ]
            );
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => 'Erro ao excluir usuário: ' . $e->getMessage()
                ],
                500
            );
        }
    }

    public function notFound()
    {
        responseJson(
            [
                'error' => 'Endpoint não encontrado.'
            ],
            404
        );
    }

}