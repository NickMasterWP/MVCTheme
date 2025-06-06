<?php

class MVCComments {

    public $id;
    public $comment;

    /**
     * Создание нового комментария
     * @throws Exception
     */
    static function create($post_id, $author_id, $content, $rating = null) {
        $comment_data = [
            'comment_post_ID' => $post_id,
            'user_id' => $author_id,
            'comment_content' => $content,
            'comment_approved' => 1,
        ];

        $comment_id = wp_insert_comment($comment_data);

        if (!$comment_id) {
            throw new Exception("Ошибка при создании комментария");
        }

        return new static($comment_id);
    }

    /**
     * Получение текущего комментария
     * @throws Exception
     */
    static function getCurrentComment($comment_id) {
        return new static($comment_id);
    }

    /**
     * Конструктор
     * @throws Exception
     */
    public function __construct($data) {
        if (is_numeric($data)) {
            $this->comment = get_comment($data);
        } else if (is_object($data)) {
            $this->comment = $data;
        } else {
            throw new Exception("MVCComments: data wrong format");
        }

        if (!$this->comment) {
            throw new Exception("Комментарий не найден");
        }
    }

    public function id() {
        return $this->comment->comment_ID ?? false;
    }

    public function authorId() {
        return $this->comment->user_id ?? false;
    }

    public function content() {
        return $this->comment->comment_content ?? '';
    }

    public function saveContent($content) {
        $data = [
            'comment_ID' => $this->comment->comment_ID,
            'comment_content' => $content,
        ];

        return wp_update_comment(wp_slash($data));
    }

    public function date() {
        return $this->comment->comment_date ?? '';
    }


    public function getProp($name) {
        $value = get_comment_meta($this->id(), $name, true);
        return $value;
    }

    public function saveProp($name, $value) {
        update_comment_meta($this->id(), $name, $value);
    }

}