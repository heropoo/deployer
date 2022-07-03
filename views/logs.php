<style>
    .logs-list{
        margin-bottom: 5rem;
    }
    .log-item{
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <ul class="logs-list">
    <?php foreach ($lines as $line):?>
    <?php $line = json_decode($line, 1);?>
        <div class="log-item bg-success">

            <div>时间: <?= $line['time']?></div>
            <div>项目: <?= $line['project']?></div>
            <div>用户: <?= $line['user']?></div>
            <div>结果:
                <ul>
                <?php foreach ($line['data'] as $item):?>
                    <li>
                        <div><?= $item['msg']?> <?= $item['code'] === 0 ? '✔️ Success' : '❌ Failed'?></div>
                    </li>
                <?php endforeach;?>
                </ul>
            </div>

        </div>
    <?php endforeach;?>
        </ul>
    </div>
</div>
