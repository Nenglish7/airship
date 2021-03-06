<?php
declare(strict_types=1);

use Airship\Engine\Database;

/**
 * Class MockDatabase
 *
 * This is a mockery of our DB class! ... for testing purposes.
 */
class MockDatabase extends Database
{
    /**
     * @var array
     */
    protected $expect = [];

    /**
     * Dependency-Injectable constructor
     *
     * @param \PDO $pdo
     * @param string $dbengine
     */
    public function __construct(\PDO $pdo = null, $dbengine = '')
    {
        if (IDE_HACKS) {
            parent::__construct($pdo, $dbengine);
        }
    }

    public static function factory(
        $dsn,
        string $username = '',
        string $password = '',
        $options = []
    ): Database {
        return new MockDatabase(null, $dsn);
    }

    /**
     * @param string $statement
     * @param null $result
     * @return Database
     */
    public function expect(string $statement = '', $result = null): self
    {
        $this->expect[$statement] = $result;
        return $this;
    }

    /**
     * @param string $statement
     * @return mixed
     */
    public function getExpected(string $statement = '')
    {
        return $this->expect[$statement] ?? null;
    }

    public function column(string $statement, array $params = [], int $offset = 0)
    {
        return $this->getExpected(
            \json_encode([
                'column',
                $statement,
                $params,
                $offset
            ])
        );
    }

    public function escapeValueSet(array $values, string $type = 'string'): string
    {
        return $this->getExpected(
            \json_encode([
                $values,
                $type
            ])
        );
    }

    public function insert(string $table, array $map = [])
    {
        return $this->getExpected(
            \json_encode([
                'insert',
                $table,
                $map
            ])
        );
    }

    public function insertGet(string $table, array $map, string $field)
    {
        return $this->getExpected(
            \json_encode([
                'insertGet',
                $table,
                $map,
                $field
            ])
        );
    }

    public function insertMany(string $table, array $maps): bool
    {
        return true;
    }

    public function row(string $statement, ...$params)
    {
        return $this->getExpected(
            \json_encode([
                'row',
                $statement,
                $params
            ])
        );
    }

    public function run(string $statement, ...$params)
    {
        return $this->getExpected(
            \json_encode([
                'run',
                $statement,
                $params
            ])
        );
    }


    public function safeQuery(
        string $statement,
        array $params = [],
        int $fetch_style = \PDO::FETCH_ASSOC
    ) {
        return $this->getExpected(
            \json_encode([
                'run',
                $statement,
                $params
            ])
        );
    }

    public function single(string $statement, array $params = [])
    {
        return $this->getExpected(
            \json_encode([
                'single',
                $statement,
                $params
            ])
        );
    }

    public function update(string $table, array $changes, array $conditions)
    {
        return $this->getExpected(
            \json_encode([
                'update',
                $table,
                $changes,
                $conditions
            ])
        );
    }

    public function beginTransaction(): bool
    {
        return true;
    }

    public function commit(): bool
    {
        return true;
    }

}