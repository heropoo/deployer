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
</style>

<div class="container-fluid">
    <div class="row">
        <h2>项目列表</h2>
       <ul>
           <?php foreach ($projects as $projectKey => $item):?>
           <li class="bg-info project-item"><a href="/?project=<?= $projectKey?>"><?= $item['name']?></a></li>
           <?php  endforeach;?>
       </ul>
    </div>
</div>
