<?php

class base {
    protected function json_return($code, $des, $value) {
        $res['code'] = $code;
        $res['des'] = $des;
        if ($value != null) {
            $res['value'] = $value;
        } else {
            $res['value'] = null;
        }
        
        $ret = json_encode($res);
        log_trace($ret);
        echo $ret;
        exit();
    }
    
    private function get_name($file) {
        $name = $this->microtime_float() . "_" . md5($file) . ".png";
        return $name;
    }
    
    private function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec) * 10000;
    }
        
    protected function save_img($file, $path) {
        if ($file["error"] > 0) { 
            log_error("Error: " . $file["error"]); 
            $this->json_return(erron::ERROR_SAVE_IMG_ERROR, err_des::ERROR_SAVE_IMG_ERROR, null);
        } else {
            if ($file["size"] > 1024 * 1024 * 1024 || $file["size"] <= 0) {
                log_error("file is too lager " . $file["size"]);
                $this->json_return(erron::ERROR_SAVE_IMG_ERROR, err_des::ERROR_SAVE_IMG_ERROR, null);
            }
            
            $name = $this->get_name($file["tmp_name"]);
            if(move_uploaded_file($file['tmp_name'], "{$_SERVER['DOCUMENT_ROOT']}/static/mobile/{$path}/{$name}")) {
                log_trace("move_uploaded_file {$file['tmp_name']} to {$_SERVER['DOCUMENT_ROOT']}/static/mobile/{$path}/{$name} success");
                $url = "/static/mobile/{$path}/{$name}";
                return $url;
            }
            
            log_error("move_uploaded_file {$file['tmp_name']} to {$_SERVER['DOCUMENT_ROOT']}/static/mobile/{$path}/{$name} error");
            $this->json_return(erron::ERROR_SAVE_IMG_ERROR, err_des::ERROR_SAVE_IMG_ERROR, null);
        } 
    }
}
