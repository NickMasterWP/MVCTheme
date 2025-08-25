<?php

namespace MVCTheme\Core;

use WP_User;

class MVCUser extends WP_User
{

    function id() {
        return $this->ID;
    }

    function getRole() {
        if ( isset($this->roles[0]) ) {
            return $this->roles[0];
        }
    }

    function setRole($roleNew) {

        $userData = array(
            'ID' => $this->ID,
            'role' => $roleNew
        );

        $result = wp_update_user($userData);

        if ( is_wp_error($result) ) {
            return $result;
        } else {
            return true;
        }

    }

    static function getCurrentUserID() {
        return get_current_user_id();
    }

    static function getCurrentUser() {
        $userId = get_current_user_id();
        if ($userId) {
            return new static($userId);
        }

        return false;
    }

    function isAdmin() {
        return $this->getRole() === "administrator";
    }

    public function getProp($name) {
        $value = get_user_meta($this->id(), $name, true);
        return $value;
    }

    public function saveProp($name, $value) {
        update_user_meta($this->id(), $name, $value);
    }

    public function setFirstName($firstName = "") : bool {
        $this->saveProp("first_name", sanitize_text_field($firstName));
        $this->updateDisplayName();
        return true;
    }

    public function getFirstName() : string {
        return $this->getProp("first_name");
    }

    public function setLastName($lastName = "") : bool {
        $this->saveProp("last_name", sanitize_text_field($lastName));
        $this->updateDisplayName();
        return true;
    }

    public function getLastName() : string {
        return $this->getProp("last_name");
    }

    public function getLogin() {
        return $this->user_login;
    }

    public function getFIO() : string {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public function getDisplayName() {
        return $this->display_name;
    }

    public function updateDisplayName() {
        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();

        // Форматируем отображаемое имя
        $displayName = trim("$firstName $lastName");

        // Обновляем поле display_name
        wp_update_user(array(
            'ID' => $this->id(),
            'display_name' => $displayName,
        ));

        return true; // Успешное сохранение
    }

    public function getEmail() : string {
        return $this->user_email;
    }

    public function saveEmail($email) {
        if ($this->user_email === $email) {
            return true; // Емейл не изменился
        }

        // Проверяем уникальность нового адреса электронной почты
        if (email_exists($email)) {
            return false; // Новый емейл уже существует
        }

        wp_update_user(array(
            'ID' => $this->id(),
            'user_email' => $email,
        ));

        return true;
    }

    static public function defaultAvatarUrl() {
        global $MVCTheme;
        return $MVCTheme->getThemeParentFileURL("assets/images/default-avatar.svg");
    }

    public function getAvatarUrl($size = 96) {
        $avatarId = $this->getProp('avatarId');
        $avatarUrl = $this->getProp('avatarUrl'); //Если фото из соц. сетей

        if ($avatarId) {
            return wp_get_attachment_image_url($avatarId, $size, true);
        } else if ($avatarUrl) {
            return $avatarUrl;
        }

        return self::defaultAvatarUrl();
    }

    public function saveAvatar($avatarId) {
        $this->saveProp('avatarId', $avatarId);
        return true;
    }

    public function saveAvatarUrl($avatarUrl) {
        $this->saveProp('avatarUrl', $avatarUrl);
        return true;
    }

    public function getLink() {
        return get_author_posts_url( $this->id() );
    }

    static function getUserByEmail($email) {
        $user = get_user_by('email', $email);

        if ($user) {
            return new static($user->ID);
        }

        return false;
    }
}