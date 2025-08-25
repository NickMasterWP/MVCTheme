<?php
 
class  APIController
{
    protected $error_fields;
    protected $check_fields;
    protected $result;

    public function __construct()
    {
        $this->error_fields = [];
        $this->check_fields = [];
        $this->create_fields();
    }

    public function add_check_fields($name, $validator)
    {
        $this->check_fields[$name] = $validator;
    }

    function get_param($name, $type = "string") {
        if (isset($_REQUEST[$name])) {
            if ($type == "int") {
                return (int)sanitize_text_field($_REQUEST[$name]);
            }
            if ($type == "float") {
                return (float)sanitize_text_field($_REQUEST[$name]);
            }
            if ($type == "html") {
                return $_REQUEST[$name];
            }
            return wp_kses_post($_REQUEST[$name]);
        }
        return false;
    }

    public function run(WP_REST_Request $request ) {
        $result = [];

        try {
            if ($this->check_error()) {

                foreach ($this->error_fields as $name => $msg) {
                    $result["fielderror"][] = (object)["field" => $name, "msg" => $msg];
                }
							
                $result["result"] = "error";
            } else {
                $result = $this->exec($request);
            }
					
        } catch (Exception $e) {

            $result["result"] = "error";
            $result["msg"] = $e->getMessage();
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        die(0);
    }

    public function add_error_field($field, $msg)
    {
        $this->error_fields[$field] = $msg;
    }

    public function after_check_error() {
    	return false;
    }

    public function check_error()  {
      if ( is_array($this->check_fields)) {
        
        foreach ($this->check_fields as $name => $validator) {
        	if (!isset($_REQUEST[$name])) {
		        $this->add_error_field($name, "Не найден");
		        continue;
	        }
            $value = $_REQUEST[$name] ;
            if (str_contains($validator, "notempty") && $value == "") {
                $this->addErrorField($name, "Cannot be blank");
            }
            if (str_contains($validator, "email") && !is_email($value)) {
                $this->addErrorField($name, "The email is in an invalid format.");
            }
            if (str_contains($validator, "policy") && $value == "") {
                $this->addErrorField($name, "Accept the user agreement");
            }
        }

      }

	    if (count($this->error_fields) > 0 || $this->after_check_error()) {
		    return true;
	    }
		return false;

    }

    public function success($result) {
        $result["result"] = "success";
        return $result;
    }

    public function error($msg) {
        $result = [];
        $result["result"] = "error";
        $result["msg"] = $msg;
        return $result;
    }

    public function error_fields($fields) {
        global $MVCTheme;
        $result = [];
        foreach ($fields as $name => $msg) {
            $result["fielderror"][] = (object)["field" => $name, "msg" => $msg];
            $MVCTheme->error("Field[$name] : $msg");
        }

        $result["result"] = "error";
        return $result;
    }

}