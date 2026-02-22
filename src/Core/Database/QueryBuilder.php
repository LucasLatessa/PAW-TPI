<?php
namespace Paw\Core\Database;

use Monolog\Logger;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $logger;
    private $query  = "";
    private $params = [];

    public function __construct(PDO $pdo, Logger $logger = null)
    {
        $this->pdo    = $pdo;
        $this->logger = $logger;
    }

    // -------- SELECT ---------
    public function select($table, $params = [], $conector = 'AND')
    {
        // Limpiamos estados anteriores
        $this->query  = "";
        $this->params = [];

        if (! empty($params)) {
            $where = [];
            foreach ($params as $key => $value) {
                $where[]               = "$key = :$key";
                $this->params[":$key"] = $value;
            }
            $whereClause = ' WHERE ' . implode(" $conector ", $where);
            $this->query = "SELECT * FROM {$table} {$whereClause}";
            return $this; // Ejecuta directo si mandas array
        }

        // Modo Fluido: Solo inicializa la base de la consulta
        $this->query = "SELECT * FROM {$table}";
        return $this;
    }

    // -------- WHEHRE ---------
    public function where($condition, $params = [])
    {
        // Concatenamos la condicion
        $this->query .= " WHERE {$condition}";

        // Guardamos los parametros para el execute()
        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }

        return $this;
    }

    // -------- SET PARAMS ---------
    public function setParam($name, $value)
    {
        $this->params[":$name"] = $value;
        return $this;
    }

    // -------- ORDER ---------
    public function order($order)
    {
        if ($order) {
            $this->query .= " ORDER BY {$order}";
        }
        return $this;
    }

    // -------- LIMIT ---------
    public function limit($limit)
    {
        if ($limit) {
            $this->query .= " LIMIT " . (int) $limit;
        }
        return $this;
    }
    public function offset($start)
    {
        $this->query .= " OFFSET " . (int) $start;
        return $this;
    }

    // -------- EXECUTE ---------
    public function execute()
    {
        $sentencia = $this->pdo->prepare($this->query);

        foreach ($this->params as $param => $value) {
            $sentencia->bindValue($param, $value);
        }

        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();

        $result = $sentencia->fetchAll();

        // Limpieza post-ejecucion
        $this->query  = "";
        $this->params = [];

        return $result;
    }

    // -------- JOIN ---------
    public function join($table, $on)
    {
        $this->query .= " INNER JOIN {$table} ON {$on}";
        return $this;
    }

    // Especifica las columnas que se desean tarde de la BD
    public function addSelect($columns)
    {
        $this->query = str_replace("SELECT *", "SELECT *, {$columns}", $this->query);
        return $this;
    }

    // -------- GET PDO ---------
    public function getPdo()
    {
        return $this->pdo;
    }

    // -------- COUNT ---------
    public function count($table, $params = [])
    {
        $where = " 1 = 1 ";
        //var_dump($params);

        if (isset($params['torneo_id'])) {
            $where .= " AND torneo_id = :torneo_id";
        }

        // Crear la consulta
        $query     = "SELECT COUNT(*) AS total FROM {$table} WHERE {$where}";
        $sentencia = $this->pdo->prepare($query);

        // Bind de parametros
        if (isset($params['torneo_id'])) {
            $sentencia->bindValue(':torneo_id', $params['torneo_id'], PDO::PARAM_INT);
        }

        $sentencia->execute();
        $result = $sentencia->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    // -------- INSERT ---------
    public function insert($table, array $data)
    {
        # Preparo las columnas y los marcadores de posicion
        $columns      = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        # Creo la query
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        # Preparo la sentencia
        $sentencia = $this->pdo->prepare($query);

        # Ejecuto la sentencia con los valores correspondientes
        $sentencia->execute(array_values($data));
    }

    // -------- UPDATE ---------
    public function update($table, $data, $where)
    {
        $setParts   = [];
        $bindParams = [];

        foreach ($data as $key => $value) {
            $setParts[]          = "$key = :$key";
            $bindParams[":$key"] = $value;
        }

        $whereParts = [];
        foreach ($where as $key => $value) {
            $whereParts[]          = "$key = :w_$key";
            $bindParams[":w_$key"] = $value;
        }

        $setClause   = implode(', ', $setParts);
        $whereClause = implode(' AND ', $whereParts);

        $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        $sentencia = $this->pdo->prepare($query);

        foreach ($bindParams as $param => $value) {
            $sentencia->bindValue($param, $value);
        }

        $sentencia->execute();
    }

    // -------- DELETE ---------
    public function delete($table, $params = [])
    {
        $where = " 1 = 2 ";

        # Manera mas seguro de evitar inyecciones SQL
        if (isset($params['id'])) {
            $where = " id = :id "; # :id -> parametrizado
        }

        $query     = "delete from {$table} where {$where}";
        $sentencia = $this->pdo->prepare($query); #Prepara la consulta
        if (isset($params['id'])) {
            $sentencia->bindValue(":id", $params['id']);
        }
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
    }
}
