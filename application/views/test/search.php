<center>
    <table border="1">
        <tr>
            <th>id</th>
            <th>姓名</th>
            <th>班级</th>
            <th>分数</th>
            <th>操作</th>
        </tr>
        <?php foreach ($row as $key => $value): ?>
        <tr>
        <td><?php echo $value['id'] ?></td>	
        <td><?php echo $value['uname'] ?></td>	
        <td><?php echo $value['class'] ?></td>	
        <td><?php echo $value['score'] ?></td>
        <td><a href="<?=site_url('test/del').'/'.$value['id']?>">删除</a></td>
        </tr>
        <?php endforeach ?>
    </table>
</center>