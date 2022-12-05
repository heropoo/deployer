<div class="container">
    <h2>Change Password</h2>
    <form action="/user/cpwd" method="post" class="form" id="myForm">
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" name="old_password" value="" class="form-control" style="width: 300px" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" value="" class="form-control" style="width: 300px" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    $("#myForm").submit(function(){
        var url =  $(this).attr('action');
        var data = $(this).serialize();
        $.post(url, data, function(res){
            //console.log(res);
            if(res.code === 0){
                alert(res.message);
                window.location.href = '/';
            }else{
                alert(res.message);
            }
        }, 'json');
        return false;
    });
</script>