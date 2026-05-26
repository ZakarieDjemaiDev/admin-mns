<?php

namespace App\Models;

class Absence extends BaseModel
{
    protected string $table = 'ABSENCE';

    public static function findAll(\PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM ABSENCE ORDER BY date DESC, id DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM ABSENCE WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(\PDO $pdo, array $data): bool
    {
        $sql = 'INSERT INTO ABSENCE (student_id, date, reason, justified)
                VALUES (:student_id, :date, :reason, :justified)';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':date' => $data['date'],
            ':reason' => $data['reason'] ?: null,
            ':justified' => !empty($data['justified']) ? 1 : 0,
        ]);
    }

    public static function updateById(\PDO $pdo, int $id, array $data): bool
    {
        $sql = 'UPDATE ABSENCE SET student_id = :student_id, date = :date,
                reason = :reason, justified = :justified WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':student_id' => $data['student_id'],
            ':date' => $data['date'],
            ':reason' => $data['reason'] ?: null,
            ':justified' => !empty($data['justified']) ? 1 : 0,
        ]);
    }

    public static function deleteById(\PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM ABSENCE WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function validate(): bool
    {
        return true;
    }

    public function save(): bool
    {
        return false;
    }
}
