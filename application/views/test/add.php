<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <form action="<?=site_url('test/add')?>" method="post">
        <tr>
            <td> 姓名</td>
            <td><input type="text" name="uname"></td>
        </tr>
        <tr>
            <td>班级</td>
            <td><input type="text" name="class"></td>
        </tr>
        <tr>
            <td>分数</td>
            <td><input type="text" name="score"></td>
        </tr>
        <tr>
            <td><input type="submit" value="add"></td>
        </tr>
    </form>
</table>
</body>
</html>