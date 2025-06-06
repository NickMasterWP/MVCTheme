<?php
 
class  AjaxController
{
    protected $error_fields;
    protected $check_fields;
    protected $result;
	protected $role;
	protected $internal;

    //type - 1 только зарегистрированный 2 - только не зарегистрированных 3 - для всех
    public function __construct($internal, $type = 3, $role = false)
    {
    	$this->internal = $internal;

        if ($type == 1 || $type == 3) {
            add_action('wp_ajax_' . $internal, array($this, 'run'));
        }
        if ($type == 2 || $type == 3) {
            add_action('wp_ajax_nopriv_' . $internal, array($this, 'run'));
        }
        $this->role = $role;
        $this->error_fields = [];
        $this->create_fields();
    }

    public function add_check_fields($name, $validator)
    {
        $this->check_fields[$name] = $validator;
    }

	public function value_str($value) {
		if ( is_array($value) ) {
			$res = "";
			foreach ($value as $val) {
				if (is_array($val)) {
					$res .= "array";//$this->value_str($val);
				} else  {
					$res .= ($res ? "," : "") . mb_strlen($val) > 64 ? addslashes( mb_substr($val,0, 64) ) : addslashes($val) ;
				}
			}
			return $res;
		}
		return mb_strlen($value) > 64 ? addslashes( mb_substr($value,0, 64) )."..." : addslashes($value) ;
	}

    static function get_param($name, $type = "string") {
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

    public function run() {
        $result = [];
			
		$params = '';
		foreach($_REQUEST as $name => $value) {
			if ($name == "pwd" || $name == "password") {
				$value = "***";
			}

			$params .= ($params ? "&" : "") . addslashes($name) . '=' . $this->value_str($value);
		}

			
        try {

			if ( $this->role ) {
                $userCurrent = wp_get_current_user();
                if ( !$userCurrent || $userCurrent->roles[0] !== $this->role ) {
                    throw new Exception("Permition denide");
                }
			}
					
            if ($this->check_error()) {

                if( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'nonce' ) ) {
                    throw new Exception( 'Оооопс, все вышло из под контроля, попробуйте еще разок, но чуть позже?' );
                }

                foreach ($this->error_fields as $name => $msg) {
                    $result["fielderror"][] = (object)["field" => $name, "msg" => $msg];
                }
							
                $result["result"] = "error";
            } else {
                $result = $this->exec();
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
        	if (!isset($_POST[$name])) {
		        $this->add_error_field($name, "Не найден");
		        continue;
	        }
            $value = $_POST[$name] ;
            if (strpos($validator, "notempty") !== false && $value == "") {
                $this->add_error_field($name, "Не может быть пустым");
            }
            if (strpos($validator, "email") !== false && !is_email($value)) {
                $this->add_error_field($name, "Емейл имеет неверный формат ");
            }
            if (strpos($validator, "policy") !== false && $value == "") {
                $this->add_error_field($name, "Примите пользовательское соглашение");
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

        $result = [];
        foreach ($fields as $name => $msg) {
            $result["fielderror"][] = (object)["field" => $name, "msg" => $msg];
        }

        $result["result"] = "error";
        return $result;
    }

    public function reload() {
        $result = [];
        $result["result"] = "success";
        $result["type"] = "reload";
        return $result;
    }

    public function header_redirect( $url ) {
        //header("HTTP/1.1 302 Moved Permanently");
        header("Location: " . $url);
    }

    public function redirect($url) {
        $result = [];
        $result["result"] = "success";
        $result["type"] = "redirect";
        $result["redirect"] = $url;
        return $result;
    }

    public function popup($msg, $result = []) {
        $result["result"] = "success";
        $result["msg"] = $msg;
        $result["type"] = "popup";
        return $result;
    }

    public function closepopup($result = []) {
        $result["result"] = "success";
        $result["type"] = "closepopup";
        return $result;
    }

    public function notify_success($msg, $result = []) {

        $result["result"] = "success";
        $result["type"] = "notify";
        $result["msg"] = $msg;
        return $result;
    }

    public function notify_error($msg, $result = []) {

        $result["result"] = "error";
        $result["type"] = "notify";
        $result["msg"] = $msg;
        return $result;
    }

    public function replace($content, $replaceSelector, $result = []) {
        $result["result"] = "success";
        $result["type"] = "replace";
        $result["content"] =  $content;
        $result["replaceSelector"] =  $replaceSelector;
        return $result;
    }

    public function printHtml($html) {
        echo $html;
        die(0);
    }
}