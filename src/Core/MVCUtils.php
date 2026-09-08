<?php

namespace MVCTheme\Core;

class MVCUtils {

	static public function uploadImage($fileArray, $postId = false) {
		$result = media_handle_sideload( $fileArray, $postId  );
		if (is_wp_error( $result )) {
			return false;
		}
		return $result;
	}

	static public function uploadImageMulti($file_array, $post_id) {

		$result_ids = [];
		foreach ($file_array["name"] as $index => $name) {
			$file_upload = [];
			$file_upload["name"] = $file_array["name"][$index];
			$file_upload["type"] = $file_array["type"][$index];
			$file_upload["tmp_name"] = $file_array["tmp_name"][$index];
			$file_upload["error"] = $file_array["error"][$index];
			$file_upload["size"] = $file_array["size"][$index];
			$media_id = media_handle_sideload( $file_upload, $post_id  );
			if (is_wp_error( $media_id )) {
				return false;
			}
			$result_ids[] = $media_id;
		}
		return $result_ids;
	}


	static function date($date) {
		return date_i18n("d F Y", strtotime($date) );
	}

	static function date_time($date) {
		return date_i18n("d F Y, H:i", strtotime($date) );
	}
	static function current_time($time) {
		return date_i18n("d.m.Y H:i:s",  $time );
	}
	static function bd_time() {
		return date_i18n("Y-m-d H:i:s",  true );
	}
    static function bd_date() {
        return date_i18n("Y-m-d",  true );
    }

	static function bd_time_value($time) {
		return date_i18n("Y-m-d H:i:s",  $time );
	}

    static function bdAddPeriodToDate($date, $period) {
        if ($date === 'now') {
            $date = self::bd_time();
        }

        try {
            $dateTime = new \DateTime($date);
            $dateTime->modify($period);
            return date_i18n("Y-m-d H:i:s", $dateTime->getTimestamp());
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

	static function get_padej($val, $args = array(" часов"," час"," часа")) {
		$d = $val % 10;
		$dd = $val % 100;
		$str = $args[0];

		if ( $val > 9 && $val < 21) {

		} else if (  $d == 1 && $dd != 11  ) {
			$str = $args[1];
		} else if ( $d > 1 && $d < 5 ) {
			$str = $args[2];
		}
		return $str;
	}

	static function get_paginations($items, $page = 1, $limit = 10) {

		$result_items = [];
		$total_items = count($items);
		$total_pages = ceil($total_items/$limit);

		$page = $page ? :  1;

		$start = ($page - 1) * $limit;
		$end =  $page * $limit;

		$end = $end > $total_items ? $total_items : $end;


		for( $i = $start; $i < $end; $i++ ) {
			$result_items[] = $items[$i];
		}

		return [   "items" => $result_items,
				    "total_items" => $total_items ,
				    "total_pages" => $total_pages,
				    "page_current" => $page,
				    "pagination" => true,
				    "limit" => $limit
				];

	}


    static function get_limit() {
        return get_option('posts_per_page');
    }

    public static function text2html($text): string
    {
        if (empty($text)) {
            return '';
        }

        return wpautop($text);
    }

}