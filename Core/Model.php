<?php
namespace Core;
use PDO;
use App\Config;
abstract class Model
{
    private static $db;
    public static function prepare_input($input){
        return trim(htmlspecialchars(stripcslashes($input)));
    }
    public static function getDB(){
        if (self::$db==null){
            try{
                self::$db=new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME.";charset=utf8",Config::DB_USER,Config::DB_PASSWORD);
                self::$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                return self::$db;
            }
            catch (\PDOException $e){
                echo $e->getMessage();
            }
        }
        return self::$db;
    }
    public static function get_last_inserted_id(){
        return self::$db->lastInsertId();
    }
    public static function insert($tbl_name,$data){
        $db=self::getDB();
        $arrayKeys=array_keys($data);
        $sql="INSERT INTO $tbl_name (";
        $sql.=implode(",",$arrayKeys).")"." VALUES(:".implode(",:",$arrayKeys).")";
        $stmt=$db->prepare($sql);
        return $stmt->execute($data);
    }
    public static function select($tblName,$fields="*",$where=[],$is_one=false,$options=false){
        $db=static::getDB();
        $sql="SELECT ".self::prepare_input($fields)." FROM `$tblName`";
        if ($options){
            $options=self::prepare_input($options);
            $sql.=" $options";
        }
        if (count($where)){
            $whereString=" WHERE ";
            foreach($where as $key=>$value){
                if($whereString!=" WHERE "){
                    $whereString.=" AND ";
                }
                if(is_array($value)){
                    $whereString.="`$key` $value[0] :$key";
                }else{
                    $whereString.="`$key`= :$key";
                }
            }
        }
        $sql.=$whereString;
        $stmt=$db->prepare($sql);
        foreach($where as $key=>$value){
            if(is_array($value)){
                $stmt->bindParam(":$key", $value[1]);
            }else{
                $stmt->bindParam(":$key", $value);
            }
        }
        $stmt->execute();
        if($is_one){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }
    public static function update($tbl_name,$data,$where){
        $db=static::getDB();
        $sql="UPDATE $tbl_name SET ";
        foreach ($data as $key=>$value){    
            $sql.="`$key`=:$key,";
        }
        $sql=substr($sql,0,strlen($sql)-1);
        $where=self::prepare_input($where);
        $sql.=" WHERE $where";
        $stmt=$db->prepare($sql);
        return $stmt->execute($data);
        
    }
    public static function delete($tbl_name,$where){
        $db=static::getDB();
        $where=self::prepare_input($where);
        $sql="DELETE FROM `$tbl_name` WHERE $where";
        return $db->query($sql);
    }
  


}