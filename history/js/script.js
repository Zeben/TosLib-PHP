$(document).ready(function(){

    // получаем текущий uri, если uri не существует то присваиваем uri первой страницы 
    var thisUri = getThisUri()?getThisUri():'page1.php';
    
    //сразу задаем параметры для текущего состояния
    history.replaceState({uri:thisUri}, null, thisUri);
    
    // клик на ссылки переключения страниц
    $('#control a').click(function(){    
    
      // получем путь к запрашиваемой странице
      var uri = $(this).attr('href');
      
      //создаем новую запись в истории только когда кликаем по ссылке
      history.pushState({uri:uri}, null, uri);
      
      // открываем страницу
      openPage(uri);      
      return false;
    });
    
    // обработчик нажатий на кнопки браузера назад/вперед 
    $(window).bind('popstate', function(event) { 
      openPage(history.state.uri);  
    });

    /**
     * Загрузка запрашиваемых страниц с сервера
     */
    function openPage(uri){
    // динамическая загрузка контента
      $.ajax({
        type: "POST",
        url: "/history/ajax.php",
        data: {
          uri: uri
        },
        cache: false,        
        success: function(data){  
          // вывод в блок <div id="data">
          $('#data').html(data);
        }
      });
    }
    
    /**
     * Возвращает текущий URI страницы
     */
    function getThisUri(){
       var loc = event.location 
        || ( event.originalEvent && event.originalEvent.location )
        || document.location;       
        return loc.pathname.substr(1);
    }     

    // Вывод таймера в конце страницы
    var sec = 0;
    function incSec() {
      $('#timer').text(sec++);
    }
    setInterval(incSec, 1000);
});