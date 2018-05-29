<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="http://cdn.alexfu.cc/css/{{site}}/normalize.css" />
    <link rel="stylesheet" type="text/css" href="http://cdn.alexfu.cc/css/{{site}}/htmleaf-demo.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <style type="text/css">
        .wrap{
            width: 30%;
            min-height: 800px;
            margin: 50px 10px;
            padding-top: 20px;
            border: 1px solid #ccc;
            overflow: auto;
            overflow-x: hidden;
            position: relative;display: inline;
            float: left
        }
        ul{
            padding: 0;
            margin: 0;
        }
        li{
            padding: 10px;
            margin-bottom: 20px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            font: 14px/1.5 "微软雅黑";
            list-style: none;
            cursor: move;
        }
        li:hover{
            background-color: #f6f6f6;
        }
    </style>
    <!--[if IE]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="page">
    <header class="htmleaf-header">
        <h1>Task Arrangement<span>All the tasks displayed below</span></h1>

    </header>
    <div class="container">
            <div class="wrap">
                <span style="text-align:center">To Do List</span>
                <ul>
                    {{todo_list}}
                </ul>
            </div>

            <div class="wrap">
                <span style="text-align:center">Doing List</span>
                <ul>
                    {{doing_list}}
                </ul>
            </div>


            <div class="wrap">
                <span style="text-align:center">Done List</span>
                <ul>
                    {{done_list}}
                </ul>
            </div>
    </div>

    <div class="container">
        <form>
            <input type="text" class="form-control">
        </form>
    </div>


</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.11.0.min.js"><\/script>')</script>
<script src="http://cdn.alexfu.cc/js/{{site}}/ddsort.js"></script>
<script>
    $( '.wrap' ).DDSort({
        target: 'li',
        floatStyle: {
            'border': '1px solid #ccc',
            'background-color': '#fff'
        }
    });
</script>
</body>
</html>