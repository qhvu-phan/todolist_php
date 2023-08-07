<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS todos (
        id INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(100) NOT NULL,
        descriptions VARCHAR(100) NOT NULL,
        statuss INT NOT NULL,
        createAt datetime DEFAULT current_timestamp(),
        updateAt datetime DEFAULT current_timestamp(),
        PRIMARY KEY (id)
    ) ENGINE=INNODB;
    

    INSERT INTO todos
        (id, title, descriptions, statuss)
    VALUES
        (1, 'Todo1', 'Do todo1', 1),
        (2, 'Todo2', 'Do todo2', 1),
        (3, 'Todo3', 'Do todo3', 2),
        (4, 'Todo4', 'Do todo4', 1),
        (5, 'Todo5', 'Do todo5', 1),
        (6, 'Todo6', 'Do todo6', 2),
        (7, 'Todo7', 'Do todo7', 2);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}