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
        <h2>用户列表</h2>
        <div class="pd20">
            <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal">新增</button>
        </div>
       <ul>
           <?php foreach ($users as $userKey => $item):?>
<!--           <li class="bg-info project-item">-->
<!--               <a href="/?project=--><?//= $userKey?><!--">--><?//= $item['name']?><!--</a>-->
<!--               &nbsp;&nbsp;-->
<!--               <a href="#" class="edit-project"-->
<!--                  data-id="--><?//= $projectKey?><!--"-->
<!--                  data-name="--><?//= $item['name']?><!--"-->
<!--                  data-path="--><?//= $item['path']?><!--"-->
<!--                  data-branch="--><?//= $item['branch']?><!--"-->
<!--                  data-hosts="--><?//= implode(',', $item['hosts'])?><!--"-->
<!--                  data-group="--><?//= $item['group'] ?? ''?><!--"-->
<!--               ><i class="glyphicon glyphicon-edit"></i></a>-->
<!--               <a href="#" class="delete-project" data-id="--><?//= $projectKey?><!--"><i class="glyphicon glyphicon-trash"></i></a>-->
<!--           </li>-->
           <li class="bg-info project-item">
               <a href="#"><?= $userKey ?></a>
               <a href="#" class="edit-user" data-id="<?= $userKey?>" data-name="<?= $userKey?>"><i class="glyphicon glyphicon-edit"></i></a>
               <a href="#" class="delete-user" data-id="<?= $userKey?>"><i class="glyphicon glyphicon-trash"></i></a>
           </li>
           <?php  endforeach;?>
       </ul>
    </div>
</div>

<div class="modal" id="createUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create/Update user</h4>
            </div>
            <div class="modal-body">
                <div>
                    <form class="form-horizontal" id="createUserForm">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" value="" required>
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
    // $("#newProjectBtn").click(function(){
    //     $("#createProjectModal").modal("show");
    // });
    // $("#createProjectModal").on("shown.bs.modal", function(e){
    // });
    $("#createUserForm").submit(function(){
        var data = $(this).serialize();
        $.post("/user/create", data, function(res){
            if(res.code === 0){
                alert(res.message);
                window.location.reload();
            }else{
                alert(res.message);
            }
        }, "json");
        return false;
    });
    $(".edit-user").click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $("#createUserForm input[name=name]").val(name);
        $("#createUserModal").modal("show");
    });
    $(".delete-user").click(function(){
        var id = $(this).data('id');
        if(confirm("确认要删除用户'"+id+"'?")){
            $.post("/user/delete", {id:id}, function(res){
                if(res.code === 0){
                    alert(res.message);
                    window.location.reload();
                }else{
                    alert(res.message);
                }
            }, "json");
        }
    });
</script>
