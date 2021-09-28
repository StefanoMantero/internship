<?php

class QueryStatement
{

    protected $pdo;
    protected $query;
    protected $params;
    protected $clauses;
    protected $orm;
    protected $classMap;

    public function __construct(PDO $pdo, string $query, array $params = [])
    {
        $this->pdo = $pdo;
        $this->params = $params;
        $this->query = $query;
        $this->clauses = '';
        $this->orm = false;
    }

    public function where($col1, $exp, $col2)
    {
        $this->clauses .= empty($this->clauses) ? 'WHERE ' : 'AND ';
        $this->clauses .= "$col1 $exp $col2\n";
        return $this;
    }

    public function orWhere($col1, $exp, $col2)
    {
        $this->clauses .= empty($this->clauses) ? 'WHERE ' : 'OR ';
        $this->clauses .= "$col1 $exp $col2\n";
        return $this;
    }

    public function withOrm() {
        return $this->orm && $this->classMap != null;
    }

    public function orm(bool $enable = false, $classMap = null) {
        if (!empty($classMap)) {
            $this->classMap = $classMap;
        }
        $this->orm = $enable;
        return $this;
    }

    public function innerJoin($modelClassName, string $col1, string $exp, string $col2){
        return $this->joinWith($modelClassName::getTableName(), $col1, $exp, $col2,"INNER");
    }

    public function rightJoin($modelClassName, string $col1, string $exp, string $col2){
        return $this->joinWith($modelClassName::getTableName(), $col1, $exp, $col2,"RIGHT");
    }

    public function leftJoin($modelClassName, string $col1, string $exp, string $col2){
        return $this->joinWith($modelClassName::getTableName(), $col1j, $expj, $col2j,"LEFT");
    }

    public function fullJoin($modelClassName, string $col1, string $exp, string $col2){
        return $this->joinWith($modelClassName::getTableName(), $col1, $exp, $col2, "FULL");
    }

    public function crossJoin($modelClassName){
        return $this->joinWith($modelClassName::getTableName(), "", "", "", "CROSS");
    }

    public function joinWith(string $table, $col1, $exp, $col2, $joinType)
    {
        $joinTypes = array("INNER","RIGHT","LEFT","FULL","CROSS");

        if(in_array($joinType, $joinTypes)){
            $this->clauses .= "$joinType JOIN $table ON $col1 $exp $table.$col2 \n";
        }

        return $this;
    }

}
