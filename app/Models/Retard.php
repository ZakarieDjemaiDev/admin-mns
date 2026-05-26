<?php

namespace App\Models;

class Retard extends BaseModel
{
    protected string $table = 'RETARD';

    public static function findAll(\PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM RETARD ORDER BY date DESC, id DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public static function findById(\PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM RETARD WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(\PDO $pdo, array $data): bool
    {
        $sql = 'INSERT INTO RETARD (student_id, date, minutes_late, reason)
                VALUES (:student_id, :date, :minutes_late, :reason)';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':date' => $data['date'],
            ':minutes_late' => $data['minutes_late'],
            ':reason' => $data['reason'] ?: null,
        ]);
    }

    public static function updateById(\PDO $pdo, int $id, array $data): bool
    {
        $sql = 'UPDATE RETARD SET student_id = :student_id, date = :date,
                minutes_late = :minutes_late, reason = :reason WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':student_id' => $data['student_id'],
            ':date' => $data['date'],
            ':minutes_late' => $data['minutes_late'],
            ':reason' => $data['reason'] ?: null,
        ]);
    }

    public static function deleteById(\PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM RETARD WHERE id = :id');
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
