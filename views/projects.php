<?php
?>
<style>
    ul{
        margin: 0;
        padding: 0;
    }
    ul li{
        list-style: none;
    }
    .project-item{
        padding: 1rem;
        margin-bottom: 4px;
    }
    .pd20{
        padding: 20px;
    }
</style>

<div class="container-fluid">
    <div>
        <h2>项目列表</h2>
        <div class="pd20">
            <button class="btn btn-success" data-toggle="modal" data-target="#createProjectModal">新增</button>
        </div>
       <ul>
           <?php foreach ($projects as $projectKey => $item):?>
           <li class="bg-info project-item"><a href="/?project=<?= $projectKey?>"><?= $item['name']?></a></li>
           <?php  endforeach;?>
       </ul>
    </div>
</div>

<div class="modal" id="createProjectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create/Update project</h4>
            </div>
            <div class="modal-body">
                <div>
                    <form class="form-horizontal" id="createProjectForm">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">ID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="id" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Path</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="project_path" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Branch</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="branch" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Group</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="group" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Host</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="host" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $("#newProjectBtn").click(function(){
        $("#createProjectModal").modal("show");
    });
    // $("#createProjectModal").on("shown.bs.modal", function(e){
    // });
    $("#createProjectForm").submit(function(){
        var data = $(this).serialize();
        $.post("/project/create", data, function(res){
            if(res.code === 200){
                alert(res.message);
                window.location.reload();
            }else{
                alert(res.message);
            }
        }, "json");
        return false;
    });
</script>
