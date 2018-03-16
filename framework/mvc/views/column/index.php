<head>
    <meta charset="UTF-8">
    <title>列比较</title>
</head>

<form action="/index.php/column" method="post">
<table>
    <tr>
        <td>左边列{{dup}}</td>
        <td>右边列{{dup}}</td>
        <td>对比</td>
    </tr>
    <tr>
        <td>
            <textarea name="left" style="min-height: 600px; min-width: 300px">{{left}}</textarea>
        </td>

        <td>
            <textarea name="right" style="min-height: 600px; min-width: 300px">{{right}}</textarea>
        </td>

        <td>
            <input type="submit" value="提交" style="height: 100px;width: 100px"/>
            <input type="button" value="重置" style="height: 100px;width: 100px" onclick="location.href='/index.php/column'"/>
        </td>
    </tr>

    <tr style="display: {{hidden}}">
        <td>左边列中不重合的</td>
        <td>右边列中不重合的</td>
        <td></td>
    </tr>
    <tr style="display: {{hidden}}">
        <td>
            <textarea name="single_left" style="min-height: 600px; min-width: 300px">{{single_left}}</textarea>
        </td>

        <td>
            <textarea name="single_right" style="min-height: 600px; min-width: 300px">{{single_right}}</textarea>
        </td>
        <td></td>
    </tr>

</table>
</form>