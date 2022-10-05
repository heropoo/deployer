<?php
?>

<div class="container-fluid">
    <div class="row">
        <h2>快速代码发布</h2>
        <span class="help-block">快速使用主分支代码发布</span>
        <form action="/publish" method="post" class="form form-inline" id="fastPublishForm">
            <div class="form-group">
                <label for="">项目:</label>
                <select name="project" class="form-control project" required>
                    <option value="">请选择</option>
                    <?php foreach ($projects as $project_id => $project): ?>
                        <option value="<?= $project_id ?>" <?= $project_id == $current_project ? 'selected' : ''?> ><?= $project['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for=""></label>
                <button type="submit" id="queryBtn" class="btn btn-info" style="margin-right: 1rem">查询状态</button>
                <button type="submit" id="publishBtn" class="btn btn-primary">发布</button>
            </div>

            <input type="hidden" name="action" id="action" value="fast_publish">

        </form>
        <hr>
    </div>

    <div class="row" id="result" style="margin-bottom: 4rem;"></div>

</div>

<script>
    // $("#queryForm").submit(function(){
    //     var data = $(this).serialize();
    //     $("#result").html("Loading");
    //     $.post("/publish", data, function(res){
    //         show_result(res)
    //     }, 'json');
    //     return false;
    // });
    $("#queryBtn").click(function(){
        $("#action").val('status');
    });
    $("#publishBtn").click(function(){
        $("#action").val('fast_publish');
    });
    $("#fastPublishForm").submit(function(){
        var data = $(this).serialize();
        $("#result").html("Loading");
        $.post("/publish", data, function(res){
            show_result(res)
        }, 'json');
        return false;
    })

    function show_result(res){
        let tpl = '';
        for (var i=0; i < res.length; i++){
            //console.log(i);
            tpl += '<div>';
            var item = res[i];
            if (item.code === 0) {
                tpl += "<div class=\"result-message\">"+item.msg+"  ✔️ Success </div>";
            }else{
                tpl += "<div class=\"result-message\">"+item.msg+"  ❌ Failed </div>";
            }
            tpl += "<div>output: <pre>" + item.stdout + "</pre>"
                + "<div>error: <pre>" + item.stderr + "</pre>"
            tpl += '</div>';
        }
        //console.log(tpl);
        $("#result").html(tpl);
    }
</script>