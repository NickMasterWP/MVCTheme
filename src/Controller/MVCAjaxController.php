<?php

namespace MVCTheme\Controller;

class  MVCAjaxController
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
        $this->createFields();
    }

    public function addCheckFields($name, $validator)
    {
        $this->check_fields[$name] = $validator;
    }

	public function valueStr($value): string
    {
		if ( is_array($value) ) {
			$res = "";
			foreach ($value as $val) {
				if (is_array($val)) {
					$res .= "array";
				} else  {
					$res .= ($res ? "," : "") . mb_strlen($val) > 64 ? addslashes( mb_substr($val,0, 64) ) : addslashes($val) ;
				}
			}
			return $res;
		}
        if (is_string($value)) {
            return mb_strlen($value) > 64 ? addslashes( mb_substr($value,0, 64) )."..." : addslashes($value) ;
        }
        return "";
	}

    static function getParam($name, $type = "string") {
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

			$params .= ($params ? "&" : "") . addslashes($name) . '=' . $this->valueStr($value);
		}

        try {
			if ( $this->role ) {
                $userCurrent = wp_get_current_user();
                if ( !$userCurrent || $userCurrent->roles[0] !== $this->role ) {
                    throw new Exception("Permition denide");
                }
			}
					
            if ($this->checkError()) {

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

    public function addErrorField($field, $msg)
    {
        $this->error_fields[$field] = $msg;
    }

    public function afterCheckError() {
    	return false;
    }

    public function checkError(): bool
    {
      if ( is_array($this->check_fields)) {
        
        foreach ($this->check_fields as $name => $validator) {
        	if (!isset($_POST[$name])) {
		        $this->addErrorField($name, "Не найден");
		        continue;
	        }
            $value = $_POST[$name] ;
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

	    if (count($this->error_fields) > 0 || $this->afterCheckError()) {
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

    public function errorFields($fields) {

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