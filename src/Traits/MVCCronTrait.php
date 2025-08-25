<?php

namespace MVCTheme\Traits;

use MVCTheme\MVCTheme;

trait MVCCronTrait {

    private $cronJobs = [];

    public function initializeCron() {
        add_action('init', array($this, 'registerCronSchedules'));
        add_action('init', array($this, 'scheduleCronJobs'));
        $this->setCronHooks();
    }

    public function addCron($hook,  $controllerClass, $interval = MVCTheme::CRON_INTERVAL_HOURLY) {
        $this->cronJobs[] = [
            "hook" => $hook,
            "interval" => $interval,
            "controllerClass" => $controllerClass
        ];
    }

    public function registerCronSchedules() {
        add_filter('cron_schedules', function($schedules) {

            foreach ($this->cronJobs as $job) {
                if (!isset($schedules[$job["interval"]])) {
                    $schedules[$job["interval"]] = array(
                        'interval' => $this->getIntervalInSeconds($job["interval"]),
                        'display' => ucfirst($job["interval"])
                    );
                }
            }

            return $schedules;
        });
    }

    private function getIntervalInSeconds($interval) {
        switch ($interval) {
            case MVCTheme::CRON_INTERVAL_MINUTES:
                return 60;
            case MVCTheme::CRON_INTERVAL_DAILY:
                return 86400;
            case MVCTheme::CRON_INTERVAL_WEEKLY:
                return 604800;
            default:
                return 3600;
        }
    }

    public function scheduleCronJobs() {
        foreach ($this->cronJobs as $job) {
            if (!wp_next_scheduled($job["hook"])) {
                wp_schedule_event(time(), $job["interval"], $job["hook"]);
            }
        }
    }

    public function setCronHooks() {
        foreach ($this->cronJobs as $job) {
            add_action($job["hook"], function() use ($job) {
                $this->executeCronJob($job["controllerClass"]);
            });
        }
    }

    private function executeCronJob($className) {

        $MVCTheme = MVCTheme::getInstance();
        $fileCron = $this->getThemeChildFilePath("app/Controller/Cron/".$className.".php");

        if (file_exists($fileCron)) {
            include_once $fileCron;
            $controller = new $className();
            $MVCTheme->debug("start cron $className");
            $controller->run();
            $MVCTheme->debug("stop cron $className");
        }

    }
}