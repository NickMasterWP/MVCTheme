<?php
/**
 * Мета-«боксы» (поля) для таксономий / term-meta.
 *
 * @author  MVCTHEME
 */

namespace MVCTheme\Traits;

use MVCTheme\Core\MVCView;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait MVCMetaBoxesTaxonomyTrait {

    private array $taxonomyMetaBoxes = [];

    private function initializeMetaboxTaxonomy()
    {
        add_action( 'init', [$this, 'registerTaxonomyMetaBoxes'], 1000 );
        add_action( 'admin_enqueue_scripts', [$this, 'enqueueTaxonomyMetaBoxAssets'] );
    }

    public function enqueueTaxonomyMetaBoxAssets( $hook_suffix ): void {

        if ( str_contains( $hook_suffix, 'edit-tags.php' )
            || str_contains( $hook_suffix, 'term.php' ) ) {

            wp_enqueue_media();
        }
    }

    public function addTaxonomyMetaBox( string $id, string $title, array|string $taxonomies ): void {
        $this->taxonomyMetaBoxes[ $id ] = [
            'title'      => $title,
            'taxonomies' => (array) $taxonomies,
            'fields'     => [],
        ];
    }

    public function addTaxonomyMetaBoxField(
        string       $metaBox,
        string       $name,
        string       $label,
        string       $type = 'text',
        null|array   $options = null,
        bool         $required = false
    ): void {
        if ( ! isset( $this->taxonomyMetaBoxes[ $metaBox ] ) ) {
            return;
        }
        $this->taxonomyMetaBoxes[ $metaBox ]['fields'][] = [
            'name'     => $name,
            'title'    => $label,
            'type'     => $type,
            'options'  => $options,
            'required' => $required,
        ];
    }

    public function registerTaxonomyMetaBoxes(): void {

        foreach ( $this->taxonomyMetaBoxes as $box ) {
            foreach ( $box['taxonomies'] as $tax ) {

                add_action(
                    "{$tax}_add_form_fields",
                    function () use ( $tax ) {
                        $this->renderTaxonomyMetaBoxAdd( $tax );
                    }
                );

                add_action(
                    "{$tax}_edit_form_fields",
                    function ( $term ) use ( $tax ) {
                        $this->renderTaxonomyMetaBoxEdit( $term, $tax );
                    }
                );

                add_action( "created_{$tax}", [ $this, 'saveTaxonomyMetaBoxes' ], 10, 2 );
                add_action( "edited_{$tax}",  [ $this, 'saveTaxonomyMetaBoxes' ], 10, 2 );
            }
        }
    }

    private function getFieldsByTaxonomy( string $taxonomy ): array {
        foreach ( $this->taxonomyMetaBoxes as $box ) {
            if ( in_array( $taxonomy, $box['taxonomies'], true ) ) {
                return $box['fields'];
            }
        }
        return [];
    }

    private function renderTaxonomyMetaBoxAdd( string $taxonomy ): void {

        $fields = $this->getFieldsByTaxonomy( $taxonomy );
        if ( ! $fields ) {
            return;
        }

        echo '<div class="form-field term-group ">';

        wp_nonce_field( 'mvc_taxonomy_meta_' . $taxonomy, 'mvc_taxonomy_meta_nonce' );

        echo MVCView::adminPart( 'form/start-fields', [ 'class' => 'b-meta-box' ] );

        foreach ( $fields as $field ) {
            echo self::printField( $field, '' );
        }

        echo MVCView::adminPart( 'form/end-fields' );

        echo '</div>';
    }

    private function renderTaxonomyMetaBoxEdit( \WP_Term $term, string $taxonomy ): void {

        $fields = $this->getFieldsByTaxonomy( $taxonomy );
        if ( ! $fields ) {
            return;
        }

        echo '<tr class="form-field term-group-wrap"><th colspan="2">';

        wp_nonce_field( 'mvc_taxonomy_meta_' . $taxonomy, 'mvc_taxonomy_meta_nonce' );

        echo MVCView::adminPart( 'form/start-fields', [ 'class' => 'b-meta-box' ] );

        foreach ( $fields as $field ) {
            $value = get_term_meta( $term->term_id, $field['name'], true );
            echo self::printField( $field, $value );
        }

        echo MVCView::adminPart( 'form/end-fields' );

        echo '</th></tr>';
    }

    /* --------------------------------------------------
     * СОХРАНЕНИЕ
     * --------------------------------------------------*/
    public function saveTaxonomyMetaBoxes( int $term_id, int $tt_id ): void { // phpcs:ignore

        $taxonomy = sanitize_key( $_POST['taxonomy'] ?? '' );

        if (
            ! isset( $_POST['mvc_taxonomy_meta_nonce'] ) ||
            ! wp_verify_nonce( $_POST['mvc_taxonomy_meta_nonce'], 'mvc_taxonomy_meta_' . $taxonomy )
        ) {
            return;
        }

        $fields = $this->getFieldsByTaxonomy( $taxonomy );

        foreach ( $fields as $field ) {
            $name = $field['name'];

            if ( ! isset( $_POST[ $name ] ) ) {
                continue;
            }

            // ---- подготовка значения ----
            if ( 'repeater' === $field['type'] ) {
                $value = [];
                foreach ( $_POST[ $name ] as $index => $item ) {
                    if ( '__index__' === $index ) {
                        continue;
                    }
                    $row = [];
                    foreach ( $field['options'] as $subField ) {
                        $row[ $subField['name'] ] = sanitize_text_field( $item[ $subField['name'] ] ?? '' );
                    }
                    $value[] = $row;
                }
            } elseif ( 'tinymce' !== $field['type'] ) {
                $value = sanitize_text_field( $_POST[ $name ] );
            } else {
                $value = wp_kses_post( $_POST[ $name ] );
            }

            update_term_meta( $term_id, $name, $value );

        }
    }

}