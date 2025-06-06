<?php
    $id = $comment->comment_ID;
    $url = get_comment_author_url( $id );

?>

<li id="comment-<?= $id ;?>" class="fc-comment ">
    <article id="div-comment-<?= $id ;?>" class="fc-comment-body">
        <footer class="fc-comment-meta">
            <div class="fc-comment-author  ">
                <figure class="fc-comment-a-img">
                    <picture>
                        <img src="<?= get_avatar_url(get_comment_author_email($id));?>" alt="<?= get_comment_author( $id );;?>"  />
                    </picture>
                </figure>
                <b class="fc-author">
                    <?php if ( $url ) { ?>
                        <a href="<?= $url;?>" rel="external nofollow ugc" class="fc-comment-url"><?= get_comment_author( $id );?></a>
                    <?php } else {
                        echo get_comment_author( $id );
                    } ?>
                </b>
            </div>
        </footer>
        <div class="comment-content">
            <div>
                <?php comment_text($id); ?>
            </div>
        </div>
        <div class="reply">
            <div data-commentid="<?= $id ;?>"  data-belowelement="div-comment-<?= $id ;?>" data-postid="<?= get_the_ID() ;?>" data-respondelement="respond" aria-label="Reply to undefined"   class="comment-reply-link fc-reply js-fc-reply">Ответить</div>
            <div></div>
        </div>
    </article>
<?php $children = $comment->get_children();?>
<?php if ( $children && count($children ) > 0 ) { ?>
        <ul class="fc-comment-list fc-comment-child">
            <?php
            foreach ( $children as $comment) {
                echo View::partial("single/comment-item", ["comment" => $comment] );
            }?>
        </ul>
<?php } ?>

</li>
