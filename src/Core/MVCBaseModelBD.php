<?php

class MVCBaseModelBD  {

    protected $id;
    protected $table_name;

    const TABLE_NAME = "";

    public function __construct( $find_object = false ) {

        if ( !$find_object ) {
            return true;
        }

        if ( is_numeric($find_object) ) {
            $find_object_result = $this->findFirstById($find_object);
            if ($find_object_result) {
                $this->copy_array($find_object_result);
            }
        }

        if ( is_object($find_object) && get_class( $this ) == get_class( $find_object ) ) {
            $this->copy($find_object);
        }

        if ( is_array($find_object) ) {
            $this->copy_array($find_object);
        }

    }

    public function id() { return $this->id;}

    function before_save() {

    }

    public function save() {
        global $wpdb;

        $term_booked = ["PRIMARY", "FOREIGN", "REFERENCES"];
        $this->before_save();
        $fields_str = $this->table_create_fields();
        $fields = explode("\n", $fields_str);

        $columns = [];
        $format = [];
        $insert = false;

        foreach( $fields as $field) {
            $fields_setting = explode(" ", $field);//substr($field, 0, strpos(" ", $field) );
            $field_name = $fields_setting[0];

            if ( array_search( $field_name, $term_booked ) !== false ) {
                continue;
            }

            if (!property_exists($this, $field_name)) {
                continue;
            }

            $value = $this->{$field_name};

            $formatColumn = $fields_setting[1];

            if (str_starts_with($formatColumn, "json")) {
                $value = json_encode($value ?? [], JSON_UNESCAPED_UNICODE);
            }

            $columns[$field_name] = $value;
            if (str_starts_with($formatColumn, "int")) {
                $formatColumn = "%d";
            } else if (str_starts_with($formatColumn, "float")) {
                $formatColumn = "%f";
            } else {
                $formatColumn = "%s";
            }

            $format[] = $formatColumn;
        }


        if ($this->id) {

            $result = $wpdb->update( $this->table_name(),
                $columns,
                [ 'id' => $this->id ],
                $format,
                [ '%d' ]
            );
        } else {


            $result = $wpdb->insert( $this->table_name(),
                $columns,
                $format
            );

            if ($result) {
                $this->id = $wpdb->insert_id;
            }

        }

        if ( $result === false ) {
            throw new Exception( "TABLE " . $this->table_name() . " save() error. Params [".implode(" ", $columns)."]. ".$wpdb->last_error );
        }

        return true;
    }

    static function table_prefix() {
        global $wpdb;
        return $wpdb->prefix ;
    }

    static public function table_name() {

        return self::table_prefix() . static::TABLE_NAME;

        //return $this->table_prefix() . $this->table_name;

    }

    protected function copy($object) {
        if (get_class( $this ) == get_class( $object )) {
            $vars = get_class_vars( get_class( $this ) );
            foreach($vars as $name => $val) {
                $this->{$name} = $object->$name();
            }
        }
    }

    protected function copy_array($array) {
        $vars = get_class_vars( get_class( $this ) );

        foreach($vars as $name => $val) {
            if ( isset($array[$name]) ) {
                $this->{$name} = $array[$name];
            }
        }
    }

    function setProp($name, $value) {

        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

    }

    function saveProp($name, $value) {
        $this->setProp($name, $value);
        return $this->save();

    }

    function getProp($name) {
        if (property_exists($this, $name)) {
            return $this->{$name};
        } else {
            global $MVCTheme;
            $MVCTheme->error("Not found prop $name");
        }

        return null;
    }

    /*
     *
     * @params
     * First param
     * 0 - sql where: " id = %d and name = %s "
     * 1 - params: [$id, $name]
     * 'bind' = ["order" => "id asc"]
     *
     * Second param $type ARRAY_A | OBJECT
     *
     * */

    static public function find( $sql = [], $type = ARRAY_A ) {
        global $wpdb;

        $sql_query = "SELECT * FROM ".self::table_name();

        if ( isset($sql[0]) && $sql[0] !== "" ) {
            if ( isset($sql[1]) ) {
                $sql_query .= " WHERE ".$wpdb->prepare($sql[0], $sql[1]);
            } else {
                $sql_query .= " WHERE " . $sql[0] ;
            }
        }

        if ( isset( $sql["bind"] ) ) {
            if ( isset( $sql["bind"]["order"] ) ) {
                $sql_query .= " ORDER BY ".$sql["bind"]["order"];
            }
        }

        //var_dump($sql_query);

        $result_query = $wpdb->get_results($sql_query, ARRAY_A );


        $result_objects = [];

        if ( $result_query ) {

            if ( $type == ARRAY_A) {
                $result_objects = $result_query;
            } else {
                $class_name = get_called_class( );
                foreach($result_query as $item) {
                    $result_objects[] =  new $class_name($item);
                }
            }

        }

        return $result_objects;
    }

    static public function count() {
        $sql_query = "SELECT count(*) FROM " . self::table_name();
        global $wpdb;
        return $wpdb->get_var($sql_query );
    }

    static public function findFirst( $sql = [], $type = OBJECT  ) {
        $result = self::find($sql, $type);
        if (isset($result[0])) {
            $item = $result[0];
            unset($result);
            return $item;
        }
        return null;
    }

    static public function findFirstById($id, $type = ARRAY_A ) {
        return self::findFirst(["id = %d", [(int)$id] ], $type);
    }

    protected function table_create_fields() {

    }

    protected function table_create_keys() { 
    }

    public function update_table() {

        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $this->table_name();
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";


        $sql = "CREATE TABLE {$table_name} ( 
".$this->table_create_fields()."  
) {$charset_collate};";

        dbDelta($sql);

        foreach($this->table_create_keys() as $key) {
            $sql_key = "ALTER TABLE {$table_name}  ADD CONSTRAINT " . $key ;
            $wpdb->query( $sql_key );
        }



    }

    public function delete_table() {

    }


    public function delete() {

        if ( !$this->id ) {
            //CFWarning("ID empty");
            return false;
        }

        global $wpdb;
        return $wpdb->delete( $this->table_name(), [ 'id' => $this->id ] );
    }


}