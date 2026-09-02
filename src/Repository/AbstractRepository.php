<?php



namespace App\Repository;

abstract class AbstractRepository
{
    public function __construct(protected \PDO $connexion)
    {
    }

    protected function getConnexion(): \PDO
    {
        return $this->connexion;
    }

    protected function query(string $sql, bool $single = true): mixed
    {
        $query = $this->getConnexion()->query($sql);

        return $single
            ? $query->fetch(\PDO::FETCH_OBJ)
            : $query->fetchAll(\PDO::FETCH_OBJ);
    }

    protected function prepare(string $sql, array $datas): \PDOStatement
    {
        $statement = $this->getConnexion()->prepare($sql);

        foreach ($datas as $key => $value) {
            $parameter = str_starts_with((string) $key, ':')
                ? (string) $key
                : ':' . $key;

            $type = match (true) {
                is_bool($value) => \PDO::PARAM_BOOL,
                is_int($value) => \PDO::PARAM_INT,
                is_null($value) => \PDO::PARAM_NULL,
                default => \PDO::PARAM_STR,
            };

            $statement->bindValue($parameter, $value, $type);
        }

        $statement->execute();

        return $statement;
    }

    protected function executeQuery(string $sql, array $datas, bool $single = true): mixed
    {
        $statement = $this->prepare($sql, $datas);

        return $single
            ? $statement->fetch(\PDO::FETCH_OBJ)
            : $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    protected function executeUpdate(string $sql, array $datas): int
    {
        $statement = $this->prepare($sql, $datas);

        return $statement->rowCount();
    }

    protected function getAllData(string $tableName): array
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $tableName)) {
            throw new \InvalidArgumentException('Nom de table invalide.');
        }

        return $this->query("SELECT * FROM {$tableName}", false);
    }
}
