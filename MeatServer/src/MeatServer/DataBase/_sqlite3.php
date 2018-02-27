<?php

namespace MeatServer\DataBase;

use MeatServer\Main;

class _sqlite3{

    public $link;
    public $oldlink = null;

    public function __construct(){
        $file = Main::$path."\DataBase\playerData_".Main::VERSION.".db";
        if(!file_exists($file)) {
            $this->link = new \SQLite3($file, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }else{
            $this->link = new \SQLite3($file, SQLITE3_OPEN_READWRITE);
        }

        $file = Main::$path."\DataBase\playerData_".Main::OLD_VERSION.".db";
        if(file_exists($file)) $this->oldlink = new \SQLite3($file, SQLITE3_OPEN_READWRITE);
    }

    public function entry($table){

        $this->DB("CREATE TABLE IF NOT EXISTS ".$table." (id TEXT PRIMARY KEY,data TEXT)");

    }

    public function get_row($table,$id){

        $result = $this->DB("SELECT * FROM ".$table." WHERE id=\"$id\"", true);

        $convert = explode("#",$result[1]);

        return str_replace("'",'"', $convert);

    }

    public function exists($table,$id){

        $result = $this->DB("SELECT * FROM ".$table." WHERE id=\"$id\"", true);

        if(empty($result)) {
            return false;
        }

        return true;
    }

    public function delete($table,$id){

        $this->DB("DELETE FROM ".$table." WHERE id=\"$id\"");

    }

    public function update($table,$id,$variables = []){

        if(empty($variables)) {
            return;
        }

        $variable = implode("#",$variables);
        $data = str_replace('"', "'", $variable);
        $this->DB("INSERT OR REPLACE INTO ".$table." VALUES(\"$id\",\"$data\")");
    }

    public function getAllData($table){
        $datas = [];
        $sql_result = $this->link->query("SELECT * FROM ".$table);
        while($data = $sql_result->fetchArray()){
            $datas[$data["id"]] = explode("#",$data["data"]);
        }
        return $datas;
    }

    public function search($name){
        $results = $this->link->query("SELECT * FROM Account WHERE id LIKE '%".$name."%'");
        $row = array();
        while($r = $results->fetchArray()){
            $row[] = $r;
        }
        return $row;
    }

    public function DB($sql, $return = false) {
        if ($return) {
            return $this->link->query($sql)->fetchArray();
        } else {
            $this->link->query($sql);
            return true;
        }
    }
}