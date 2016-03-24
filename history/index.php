<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />       
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>        
    </head>
    <body>
        <!--Блок ссылок для переключения страниц--> 
        <div id='control'>
            <a href="/history/page1.php">Показать страницу 1</a>
            <a href="/history/page2.php">Показать страницу 2</a>
        </div>      
        
        <div id='data'>
        <!--Блок для вывода динамического контента--> 
        </div>  
        
        <!--Таймер для демонстрации динамического обновления данных-->    
        <div id='timer'>0</div>         
    </body>
</html>