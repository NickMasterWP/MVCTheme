<?php

namespace MVCTheme\Traits;

trait MVCLogTrait {

    private function log($level, $message) {
        $log_message = sprintf(
            "[%s] [%s] %s\n",
            current_time('mysql'),
            strtoupper($level),
            $message
        );

        $upload_dir = wp_upload_dir();
        $log_file = $upload_dir['basedir'] . '/mvc.log';
        file_put_contents($log_file, $log_message, FILE_APPEND);
    }

    public function debug($message) {
        $this->log('DEBUG', $message);
    }

    public function info($message) {
        $this->log('INFO', $message);
    }

    public function errorLog($message) {
        $this->log('ERROR', $message);
    }
}
