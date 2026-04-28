<?php
require_once 'config.php';

/**
 * Получает PDO соединение с PostgreSQL
 * @return PDO
 */
function getDbConnection(): PDO
{
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;options=--client_encoding=UTF8',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Ошибка подключения к базе данных. Попробуйте позже.");
        }
    }
    
    return $pdo;
}

/**
 * Выполняет SELECT запрос и возвращает все результаты
 * @param string $query SQL запрос
 * @param array $params Параметры для prepared statement
 * @return array Массив результатов
 */
function dbSelect(string $query, array $params = []): array
{
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Выполняет SELECT запрос и возвращает одну запись
 * @param string $query SQL запрос
 * @param array $params Параметры для prepared statement
 * @return array|null Запись или null если не найдена
 */
function dbSelectOne(string $query, array $params = []): ?array
{
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * Выполняет INSERT/UPDATE/DELETE запрос
 * @param string $query SQL запрос
 * @param array $params Параметры для prepared statement
 * @return bool True если успешно
 */
function dbExecute(string $query, array $params = []): bool
{
    $pdo = getDbConnection();
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}

/**
 * Получает ID последней вставленной записи
 * @return int ID последней записи
 */
function dbLastInsertId(): int
{
    $pdo = getDbConnection();
    return (int) $pdo->lastInsertId();
}
