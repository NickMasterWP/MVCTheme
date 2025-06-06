<?php

namespace MVCTheme\Traits;

trait MVCLogTrait {

    private $sidebars;

    private function log($level, $message) {
        $log_message = sprintf(
            "[%s] [%s] %s\n",
            current_time('mysql'),
            strtoupper($level),
            $message
        );

        // Записываем лог в файл

        $log_file = WP_CONTENT_DIR . '/mvc.log';
        file_put_contents($log_file, $log_message, FILE_APPEND);
    }

    public function debug($message) {
        $this->log('DEBUG', $message);
    }

    public function info($message) {
        $this->log('INFO', $message);
    }

    public function error($message) {
        $this->log('ERROR', $message);
    }
}
