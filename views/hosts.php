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
        <h2>机器列表</h2>
        <div class="pd20">
            <button class="btn btn-success" data-toggle="modal" data-target="#createModal">新增</button>
        </div>
       <ul>
           <?php foreach ($hosts as $hostKey => $item):?>
           <li class="bg-info project-item">
               <a href="#"><?= $item['name']?></a>
               &nbsp;&nbsp;
               <a href="#" class="edit-project"
                  data-id="<?= $hostKey?>"
                  data-name="<?= $item['name']?>"
                  data-host="<?= $item['host']?>"
                  data-port="<?= $item['port']?>"
                  data-user="<?= $item['user']?>"
               ><i class="glyphicon glyphicon-edit"></i></a>
               <a href="#" class="delete-project" data-id="<?= $hostKey?>"><i class="glyphicon glyphicon-trash"></i></a>
           </li>
           <?php  endforeach;?>
       </ul>
    </div>
</div>

<div class="modal" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create/Update host</h4>
            </div>
            <div class="modal-body">
                <div>
                    <form class="form-horizontal" id="createForm">
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
                            <label for="" class="col-sm-2 control-label">Host</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="host" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Port</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="port" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">User</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="user" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                <?php if($publicKey):?>
                    <div>
                        <p>Please put the following public key into the `~/.ssh/authorized_keys` file of the target server</p>
                        <pre><?= $publicKey?></pre>
                    </div>
                <?php endif;?>
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
    $("#createForm").submit(function(){
        var data = $(this).serialize();
        $.post("/host/create", data, function(res){
            if(res.code === 0){
                alert(res.message);
                window.location.reload();
            }else{
                alert(res.message);
            }
        }, "json");
        return false;
    });
    $(".edit-project").click(function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var host = $(this).data('host');
        var port = $(this).data('port');
        var user = $(this).data('user');
        $("#createForm input[name=id]").val(id);
        $("#createForm input[name=name]").val(name);
        $("#createForm input[name=host]").val(host);
        $("#createForm input[name=port]").val(port);
        $("#createForm input[name=user]").val(user);
        $("#createModal").modal("show");
    });
    $(".delete-project").click(function(){
        var id = $(this).data('id');
        if(confirm("Confirm to delete this item '"+id+"'?")){
            $.post("/host/delete", {id:id}, function(res){
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
