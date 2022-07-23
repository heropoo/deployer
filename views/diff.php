<?php
/** @var \Moon\View $this */
?>
<style>
    .pre-output{
        background-color: #fff;
        margin-bottom: 3rem;
    }
</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/vs.min.css">
<div class="container-fluid">
    <div class="row">
        <?php if($res['code'] === 0):?>
        <pre class="pre-output">
            <code class="language-diff"><?= $this->e($res['stdout'])?></code>
        </pre>
        <?php else: ?>
        <pre><?= $res['msg'] . $res['stdout'] ?></pre>
        <?php endif;?>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
<script type="text/javascript">
    window.onload = function(){
        hljs.highlightAll();
    };
</script>