<?php

namespace Paw\Core\Database;

use PDO;
use Monolog\Logger;

class QueryBuilder
{
    public function __construct(PDO $pdo, Logger $logger = null){
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function selectViejo($table, $params = []) {
        $where = [];
        $bindParams = [];
    
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'id':
                case 'idUsuario':
                case 'idSesion':
                case 'correo':
                    $where[] = "$key = :$key";
                    $bindParams[":$key"] = $value;
                    break;
                case 'id_pedido':
                    $where[] = "$key = :$key";
                    $bindParams[":$key"] = $value;
                    break;
                // Añadir más casos según los parámetros que necesites manejar
            }
        }
    
        $whereClause = '';
        if (!empty($where)) {
            $whereClause = 'WHERE ' . implode(' AND ', $where);
        }
    
        $query = "SELECT * FROM {$table} {$whereClause}";
        $sentencia = $this->pdo->prepare($query);
    
        foreach ($bindParams as $param => $value) {
            $sentencia->bindValue($param, $value);
        }
    
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }
    

    public function select($table, $params = []){

        $where = " 1 = 1 "; #Para que devuelva todo si no hay parametros
        if (isset($params['id'])){
            $where = " id = :id ";
        }
        else if((isset($params['correo'])) and (isset($params['contraseña']))){
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
        
        return  $sentencia->fetchAll(); #Me devuelve todos los registros que coincidan

    }

    /** Para el ordenamiento y filtrado de items  */

    public function selectOrder($table, $sortField, $direction ){
        // Validar el campo de ordenamiento
        $camposPermitidos = ['id', 'nombre', 'precio']; // Agrega aquí los nombres de campo permitidos
        if (!in_array($sortField, $camposPermitidos)) {
            $sortField = 'id'; // Establecer un valor predeterminado si el campo no es válido
        }
        
        // Construir la consulta SQL con los parámetros de orden y dirección
        $query = "SELECT * FROM {$table} ORDER BY {$sortField} {$direction}";
        
        // Preparar y ejecutar la consulta
        $sentencia = $this->pdo->prepare($query);
        $sentencia->execute();
        
        // Devolver los resultados
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOrder2($table, $sortField, $direction, $precioMin = null, $precioMax = null){
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

    
    public function count($table, $params = []){
        $where = " 1 = 1 ";

        // Filtrado por precio mínimo y máximo
        if (isset($params['min_price'])) {
            $where .= " AND precio >= :min_price ";
        }
        if (isset($params['max_price'])) {
            $where .= " AND precio <= :max_price ";
        }

        // Crear la consulta
        $query = "SELECT COUNT(*) AS total FROM {$table} WHERE {$where}";
        $sentencia = $this->pdo->prepare($query);

        // Bind de parámetros
        if (isset($params['min_price'])) {
            $sentencia->bindValue(':min_price', $params['min_price'], PDO::PARAM_INT);
        }
        if (isset($params['max_price'])) {
            $sentencia->bindValue(':max_price', $params['max_price'], PDO::PARAM_INT);
        }

        $sentencia->execute();
        $result = $sentencia->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    public function insert($table, array $data){
        # Preparo las columnas y los marcadores de posición
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        # Creo la query
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        var_dump($query);  // Para depuración, puedes eliminar esto en producción
        var_dump(array_values($data));  // Para depuración, puedes eliminar esto en producción
        
        # Preparo la sentencia
        $sentencia = $this->pdo->prepare($query);
    
        # Ejecuto la sentencia con los valores correspondientes
        $sentencia->execute(array_values($data));
    }

    public function update($table, array $data, array $conditions){
        //SET de la consulta
        $setPart = implode(", ", array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data)));

        //Where
        $wherePart = implode(" AND ", array_map(function($key) {
            return "$key = :where_$key";
        }, array_keys($conditions)));

        //Consulta completa
        $sql = "UPDATE $table SET $setPart WHERE $wherePart";

        //Preparo la consulta
        $stmt = $this->pdo->prepare($sql);

        //Vinculo valor con dato
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        //Vinculo valor con condicion
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }

        // Ejecutar consulta
        return $stmt->execute();
    }

    public function delete($table,$params = []){
        $where = " 1 = 2 ";

        #Manera mas seguro de evitar inyecciones SQL
        if (isset($params['id'])){
            $where = " id = :id "; # :id -> parametrizado
        }

        $query = "delete from {$table} where {$where}";
        $sentencia = $this->pdo->prepare($query); #Prepara la consulta
        if (isset($params['id'])){
            $sentencia->bindValue(":id", $params['id']);
        }
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
    }

    public function selectNombreEquipo($table, $nombre){
        $whereClause = 'WHERE nombre = :nombre';
        $query = "SELECT * FROM {$table} {$whereClause}";

        $sentencia = $this->pdo->prepare($query);
        $sentencia->bindValue(':nombre', $nombre);
            
        $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();

    }
}