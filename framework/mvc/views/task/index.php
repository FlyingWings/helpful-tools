<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="http://cdn.alexfu.cc/css/{{site}}/normalize.css" />
    <link rel="stylesheet" type="text/css" href="http://cdn.alexfu.cc/css/{{site}}/htmleaf-demo.css">
    <style type="text/css">
        #wrap{
            width: 400px;
            height: 470px;
            margin: 50px auto;
            padding-top: 20px;
            border: 1px solid #ccc;
            overflow: auto;
            overflow-x: hidden;
            position: relative;
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
<div class="htmleaf-container">
    <header class="htmleaf-header">
        <h1>DDSort-简单实用的jQuery拖拽排序插件 <span>A simple, lightweight drag and drop sorting jQuery plugin</span></h1>

    </header>
    <div id="wrap">
        <ul>
            <li>
                世界上最美好的相遇是恰好，你洽好青春年少，我恰好青春芳华；世界上最唯美的惊艳是偶然，你偶然出现，我偶然发现，然后清纯的两颗心，慢慢地慢慢地靠近；
            </li>
            <li>
                你飘逸的裙裾逶迤在我寂寞里飞马行空的诗行，<br>
                跟随你的优雅舒缓的步履，
            </li>
            <li>
                在相濡以沫心心相印的平凡日子里心香如故，<br>
                刻写一个个浪漫和感动记忆的满面皱纹。
            </li>
            <li>
                除了怀念，只剩怀念；<br>
                除了无言，只能无言！
            </li>
            <li>一些事情，你愈是去遮掩愈是容易清晰，原本以为的瞒天过海，结果却是欲盖弥彰。</li>
            <li>
                世界上最美好的相遇是恰好，你洽好青春年少，我恰好青春芳华；世界上最唯美的惊艳是偶然，你偶然出现，我偶然发现，然后清纯的两颗心，慢慢地慢慢地靠近；
            </li>
            <li>
                你飘逸的裙裾逶迤在我寂寞里飞马行空的诗行，<br>
                跟随你的优雅舒缓的步履，
            </li>
            <li>
                在相濡以沫心心相印的平凡日子里心香如故，<br>
                刻写一个个浪漫和感动记忆的满面皱纹。
            </li>
            <li>
                除了怀念，只剩怀念；<br>
                除了无言，只能无言！
            </li>
            <li>一些事情，你愈是去遮掩愈是容易清晰，原本以为的瞒天过海，结果却是欲盖弥彰。</li>
        </ul>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.11.0.min.js"><\/script>')</script>
<script src="http://cdn.alexfu.cc/js/{{site}}/js/ddsort.js"></script>
<script>
    $( '#wrap' ).DDSort({
        target: 'li',
        floatStyle: {
            'border': '1px solid #ccc',
            'background-color': '#fff'
        }
    });
</script>
</body>
</html>