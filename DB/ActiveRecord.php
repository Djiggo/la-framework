<?php
/**
 * Created by PhpStorm.
 * User: lev
 * Date: 16.02.15
 * Time: 15:03
 */

namespace LA\DB;


class ActiveRecord
{

    public static function loadModelObj(&$model_obj, $id)
    {
        $model_class_name = get_class($model_obj);

        $db_table_name = $model_class_name::DB_TABLE_NAME;
        $db_ID = $model_class_name::DB_ID;

        $sql = "SELECT * FROM " . $db_table_name . " WHERE id = ?";

        $data_obj = \LA\DB\DBWrapper::getRow($db_ID, $sql, array($id));

        if (!$data_obj) {
            return false;
        }

        $reflect = new \ReflectionClass($model_class_name);

        foreach ($data_obj as $field_name => $field_value) {
            $property = $reflect->getProperty($field_name);
            $property->setAccessible(true);
            $property->setValue($model_obj, $field_value);
        }

        return true;

    }

    public static function removeModelObj(&$model_obj, $id)
    {
        $model_class_name = get_class($model_obj);

        $db_table_name = $model_class_name::DB_TABLE_NAME;
        $db_ID = $model_class_name::DB_ID;

        $sql = "DELETE FROM " . $db_table_name . " WHERE id = ?";

        \LA\DB\DBWrapper::remove($db_ID, $sql, array($id));

        unset($model_obj);
    }

    public static function saveModelObj($model_obj)
    {

        $model_class_name = get_class($model_obj);

        $db_table_name = $model_class_name::DB_TABLE_NAME;
        $db_ID = $model_class_name::DB_ID;

        $reflect = new \ReflectionClass($model_obj);


        foreach ($reflect->getProperties() as $property_obj) {

            if ($property_obj->isStatic()) {
                continue; // игнорируем статические свойства класса - они относятся не к объекту, а только к классу (http://www.php.net/manual/en/language.oop5.static.php), и в них хранятся настройки ActiveRecord и CRUD
            }

            $property_obj->setAccessible(true);
            $fields_to_save_arr[$property_obj->getName()] = $property_obj->getValue($model_obj);
        }


        if (!$model_obj->getID()) {

            foreach ($fields_to_save_arr as $key => $val) {

                if ($key == "id") {
                    continue;
                }

                $sql_fields[] = '`' . $key . '`';
                $sql_args[] = $val;
            }

            $sql_fields_str = implode(', ', $sql_fields);
            $placeholders_arr = array_fill(0, count($sql_fields), '?');
            $placeholders = implode(', ', $placeholders_arr);


            $sql = "INSERT INTO  " . $db_table_name . " ( " . $sql_fields_str . ") VALUES (" . $placeholders . ")";


            $last_insert_id = \LA\DB\DBWrapper::insert($db_ID, $sql, $sql_args);


            $property_obj = $reflect->getProperty("id");
            $property_obj->setAccessible(true);
            $property_obj->setValue($model_obj, $last_insert_id);
        } else {

            $sql_args = array();
            $sql_fields = array();

            foreach ($fields_to_save_arr as $key => $val) {
                $sql_fields[] = "`" . $key . "` =?";
                $sql_args[] = $val;
            }


            $sql_args[] = $model_obj->getID();
            $sql = "UPDATE " . $db_table_name . " SET " . implode(", ", $sql_fields) . " WHERE id = ?";


            \LA\DB\DBWrapper::update($db_ID, $sql, $sql_args);

        }


    }
}