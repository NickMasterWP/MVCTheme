<style>
    .fc-post-container ul.fc-comment-list {
        padding-left: 15px;
        margin-bottom: 0px;
    }
    .fc-comment {
        list-style: none;
    }
    .fc-comments-container {
        background: #E8F9FF;
        padding: 50px 0px 0px;
    }
    .fc-comment {
        border-bottom: 1px solid #ccc;
        padding-bottom: 1.25rem;
    }
    .fc-comment-body {
        word-wrap: break-word;
        margin: 0.625rem 0 0;
        padding: 0.625rem 0 1.25rem;
    }
    .fc-comment-author {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }
    .fc-comment-a-img {
        width: 40px;
        height: 40px;
        border-radius: 20px;
        margin-right: 0.8rem;
        overflow: hidden;
    }
    .fc-author {
        transition: all 0.2s ease-in-out 0s;
        color: rgb(187, 80, 3);
        background-color: transparent;
    }
    .fc-reply {
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #878787;
        padding: 0;
        font-size: 0.875rem;
        border: none;
        background: none;
        outline: none;
        cursor: pointer;
    }
    .comment-content p {
        padding-left: 0px;
    }
    .fc-comment-child {
        border-left: 2px solid #c7e6bd;
    }
    .fc-comment-child .fc-comment:last-child {
        border-bottom: 0px;
    }
</style>
<div class="fc-comments-container">

    <ul class="fc-comment-list">
        <?php $comments = get_comments(['post_id' => get_the_ID(),
            'status' => 'approve',
            'hierarchical' => 'threaded',
            'order' => 'ASC'
            ]);?>
        <?php foreach ($comments as $comment) {
            echo View::partial("single/comment-item", ["comment" => $comment] );
        }?>
    </ul>

</div>
