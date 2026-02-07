<?php

namespace Paw\Core\Database;

use PDO;
use Monolog\Logger;

class QueryBuilder
{
  public function __construct(PDO $pdo, Logger $logger = null)
  {
    $this->pdo = $pdo;
    $this->logger = $logger;
  }

  public function selectViejo($table, $params = [], $orderBy = null, $limit = null)
  {
    $where = [];
    $bindParams = [];

    foreach ($params as $key => $value) {
      $where[] = "$key = :$key";
      $bindParams[":$key"] = $value;
      // switch ($key) {
      //   case 'id':
      //   case 'idUsuario':
      //   case 'idSesion':
      //   case 'correo':
      //   case 'id_torneo':
      //   case 'id_equipo':
      //     $where[] = "$key = :$key";
      //     $bindParams[":$key"] = $value;
      //     break;
      //   case 'id_equipo_local':
      //   case 'id_equipo_visitante':
      //     $where[] = "$key = :$key";
      //     $bindParams[":$key"] = $value;
      //     break;
      //   case 'golesLocal':
      //   case 'golesVisitante':
      //     if (is_null($value)) {
      //       // Si es null, usamos IS NULL y NO bindeamos 
      //       $where[] = "$key IS NULL";
      //     } else {
      //       // Si tiene valor, usamos = y bindeamos
      //       $where[] = "$key = :$key";
      //       $bindParams[":$key"] = $value;
      //     }
      //     break;
      //   case 'torneo_id':
      //     $where[] = "$key = :$key";
      //     $bindParams[":$key"] = $value;
      //     break;
      //   case 'fecha_id':
      //     $where[] = "$key = :$key";
      //     $bindParams[":$key"] = $value;
      //     break;
      // }
    }

    $whereClause = '';
    if (!empty($where)) {
      $whereClause = 'WHERE ' . implode(' AND ', $where);
    }

    $query = "SELECT * FROM {$table} {$whereClause}";
    // order by si existe
    if ($orderBy) {
      $query .= " ORDER BY {$orderBy}";
    }

    // limit si existe
    if ($limit) {
      $query .= " LIMIT " . (int)$limit;
    }
    $sentencia = $this->pdo->prepare($query);

    foreach ($bindParams as $param => $value) {
      $sentencia->bindValue($param, $value);
    }

    $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    $sentencia->execute();
    return $sentencia->fetchAll();
  }


  public function select($table, $params = [])
  {

    $where = " 1 = 1 "; #Para que devuelva todo si no hay parametros
    if (isset($params['id'])) {
      $where = " id = :id ";
    } else if ((isset($params['correo'])) and (isset($params['contraseña']))) {
      $where = " correo = :correo AND contraseña = :contraseña ";
    }
    #Preparo la consulta
    #$query = "select * from {$table} where {$where}"; no funciona
    $query = "select * from {$table} where correo = '{$params['correo']}' and contraseña = '{$params['contraseña']}'";
    $sentencia = $this->pdo->prepare($query);


    #Si exxiste el id, se lo agrego al where
    /*if (isset($params['id'])){
            $sentencia->bindValue(":id", $params['id']);
        }
        else if((isset($params['correo'])) and (isset($params['contraseña']))){
            
            $sentencia->bindValue(":correo", $params['correo']);
            $sentencia->bindValue(":contraseña", $params['contraseña']);
        }*/
    $sentencia->setFetchMode(PDO::FETCH_ASSOC); #Como me retorna todo el array de respuesta FETCH_ASSOC: trae nombre de las columnas
    $sentencia->execute();

    return $sentencia->fetchAll(); #Me devuelve todos los registros que coincidan

  }

  /*Select para obtener la tabla de posiciones*/
  public function selectTabla($table, $idTorneo = null)
  {
    // Construir la consulta SQL con los parámetros de orden y dirección
    $query = "SELECT * FROM {$table} WHERE 1";

    // Agregar filtro por id_torneo si se proporciona
    if ($idTorneo !== null) {
      $query .= " AND id_torneo = :idTorneo";
    }

    // Agregar la cláusula ORDER BY por puntos ascendente
    $query .= " ORDER BY puntos DESC";

    // Preparar y ejecutar la consulta
    $sentencia = $this->pdo->prepare($query);

    // Vincular el parámetro id_torneo si se proporciona
    if ($idTorneo !== null) {
      $sentencia->bindParam(':idTorneo', $idTorneo, PDO::PARAM_INT);
    }

    $sentencia->execute();

    // Devolver los resultados
    return $sentencia->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectOrder2($table, $sortField, $direction, $precioMin = null, $precioMax = null)
  {
    // Validar el campo de ordenamiento
    $camposPermitidos = ['id', 'nombre', 'precio']; // Agrega aquí los nombres de campo permitidos
    if (!in_array($sortField, $camposPermitidos)) {
      $sortField = 'id'; // Establecer un valor predeterminado si el campo no es válido
    }

    // Construir la consulta SQL base
    $query = "SELECT * FROM {$table}";

    // Agregar condiciones de filtrado por rango de precio si están presentes
    $conditions = [];
    if ($precioMin !== null) {
      $conditions[] = "precio >= :precioMin";
    }
    if ($precioMax !== null) {
      $conditions[] = "precio <= :precioMax";
    }

    // Agregar las condiciones al query si existen
    if (!empty($conditions)) {
      $query .= " WHERE " . implode(' AND ', $conditions);
    }

    // Agregar el ordenamiento
    if ($sortField && $direction) {
      $query .= " ORDER BY {$sortField} {$direction}";
    }

    // Preparar y ejecutar la consulta
    $sentencia = $this->pdo->prepare($query);

    // Vincular los parámetros de rango de precio si están presentes
    if ($precioMin !== null) {
      $sentencia->bindValue(':precioMin', $precioMin);
    }
    if ($precioMax !== null) {
      $sentencia->bindValue(':precioMax', $precioMax);
    }

    $sentencia->execute();

    // Devolver los resultados
    return $sentencia->fetchAll(PDO::FETCH_ASSOC);
  }


  public function count($table, $params = [])
  {
    $where = " 1 = 1 ";
    //var_dump($params);

    if (isset($params['torneo_id'])) {
        $where .= " AND torneo_id = :torneo_id";
    }

    // Crear la consulta
    $query = "SELECT COUNT(*) AS total FROM {$table} WHERE {$where}";
    $sentencia = $this->pdo->prepare($query);

    // Bind de parámetros
    if (isset($params['torneo_id'])) {
    $sentencia->bindValue(':torneo_id', $params['torneo_id'], PDO::PARAM_INT);
    }

    $sentencia->execute();
    $result = $sentencia->fetch(PDO::FETCH_ASSOC);

    return $result['total'];
  }

  public function insert($table, array $data)
  {
    # Preparo las columnas y los marcadores de posición
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    # Creo la query
    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
    // Para depuración, puedes eliminar esto en producción

    # Preparo la sentencia
    $sentencia = $this->pdo->prepare($query);

    # Ejecuto la sentencia con los valores correspondientes
    $sentencia->execute(array_values($data));
  }

  public function updateEquipoTorneo($table, $fields, $params = [])
  {
    // Inicializar arrays para fragmentos de la consulta y parámetros
    $set = [];
    $where = [];
    $bindParams = [];

    // Construir la parte SET de la consulta
    foreach ($fields as $key => $value) {
      $paramKey = ":set_$key";
      $set[] = "$key = $paramKey";
      $bindParams[$paramKey] = $value;
    }

    // Construir la parte WHERE de la consulta
    foreach ($params as $key => $value) {
      $paramKey = ":where_$key";
      $where[] = "$key = $paramKey";
      $bindParams[$paramKey] = $value;
    }

    // Unir las partes SET y WHERE
    $setClause = implode(', ', $set);
    $whereClause = implode(' AND ', $where);

    // Construir la consulta completa
    $query = "UPDATE $table SET $setClause WHERE $whereClause";
    $sentencia = $this->pdo->prepare($query);

    // Vincular los parámetros
    foreach ($bindParams as $param => $value) {
      // Asegurarse de que $value sea un valor escalar
      $sentencia->bindValue($param, $value);
    }

    // Ejecutar la consulta
    return $sentencia->execute();
  }

  public function update($table, $data, $where)
  {
    $setParts = [];
    $bindParams = [];

    foreach ($data as $key => $value) {
      $setParts[] = "$key = :$key";
      $bindParams[":$key"] = $value;
    }

    $whereParts = [];
    foreach ($where as $key => $value) {
      $whereParts[] = "$key = :w_$key";
      $bindParams[":w_$key"] = $value;
    }

    $setClause = implode(', ', $setParts);
    $whereClause = implode(' AND ', $whereParts);

    $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

    $sentencia = $this->pdo->prepare($query);

    foreach ($bindParams as $param => $value) {
      $sentencia->bindValue($param, $value);
    }

    $sentencia->execute();
  }
  public function delete($table, $params = [])
  {
    $where = " 1 = 2 ";

    #Manera mas seguro de evitar inyecciones SQL
    if (isset($params['id'])) {
      $where = " id = :id "; # :id -> parametrizado
    }

    $query = "delete from {$table} where {$where}";
    $sentencia = $this->pdo->prepare($query); #Prepara la consulta
    if (isset($params['id'])) {
      $sentencia->bindValue(":id", $params['id']);
    }
    $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    $sentencia->execute();
  }

  public function selectNombreEquipo($table, $nombre)
  {
    $whereClause = 'WHERE nombre = :nombre';
    $query = "SELECT * FROM {$table} {$whereClause}";

    $sentencia = $this->pdo->prepare($query);
    $sentencia->bindValue(':nombre', $nombre);

    $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    $sentencia->execute();
    return $sentencia->fetchAll();
  }

  public function selectJoin(string $table, array $joins = [], array $params = [], string $orderBy = null, int $limit = null)
  {
    $where = [];
    $bindParams = [];

    foreach ($params as $key => $value) {
      $where[] = "$key = :$key";
      $bindParams[":$key"] = $value;
    }

    $query = "SELECT * FROM {$table}";

    // JOINs
    foreach ($joins as $join) {
      // [$tableJoin, $on]
      $query .= " INNER JOIN {$join[0]} ON {$join[1]}";
    }

    // WHERE
    if (!empty($where)) {
      $query .= ' WHERE ' . implode(' AND ', $where);
    }

    // ORDER BY
    if ($orderBy) {
      $query .= " ORDER BY {$orderBy}";
    }

    // LIMIT
    if ($limit) {
      $query .= " LIMIT " . (int) $limit;
    }

    $stmt = $this->pdo->prepare($query);

    foreach ($bindParams as $param => $value) {
      $stmt->bindValue($param, $value);
    }

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    return $stmt->fetchAll();
  }
}
