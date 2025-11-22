(function ($) {
    'use strict';

    $(function () {
        const btnEvent = $('#notificationDropdown');
        const evController = $(".inner-body-card-notify");
        
        function scrollNotifyHandler() {
            var $el = evController;
            // Detecta si el scroll está al fondo (con tolerancia de 1px)
            if ($el[0].scrollHeight - $el.scrollTop() - $el.outerHeight() <= 1) {
                Checknotify($el[0].scrollHeight);
                $el.off('scroll', scrollNotifyHandler);
            }
        }

        evController.on('scroll', scrollNotifyHandler);
        function Checknotify(LastScroll)
        {
            // e.preventDefault();
            $(`<div class="dropdown-body loadMoreSpinNotify"><a href="javascript:;" class="dropdown-item"><div class="icon"><i data-feather="alert-circle"></i></div><div class="content"><p class="loading-name"></p><p class="sub-text text-muted loading-time"></p></div></a></div>`).appendTo(".inner-body-card-notify");
            $(`<div class="dropdown-body loadMoreSpinNotify"><a href="javascript:;" class="dropdown-item"><div class="icon"><i data-feather="alert-circle"></i></div><div class="content"><p class="loading-name"></p><p class="sub-text text-muted loading-time"></p></div></a></div>`).appendTo(".inner-body-card-notify");
            $(`<div class="dropdown-body loadMoreSpinNotify"><a href="javascript:;" class="dropdown-item"><div class="icon"><i data-feather="alert-circle"></i></div><div class="content"><p class="loading-name"></p><p class="sub-text text-muted loading-time"></p></div></a></div>`).appendTo(".inner-body-card-notify");
           
            var allElements = $('div.card-notify-list').length;
            var url_pagination = '/notifications';
    
            $.ajax({
                url: url_pagination,
                data: { skip: allElements},
            }).done(function (data) {
                console.log(data);
                if(data){
                    $('.loadMoreSpinNotify').remove();
                    $(data).appendTo(".inner-body-card-notify");
                    evController.scrollTop(LastScroll - 350);
                    if (window.feather) feather.replace(); 

                    setTimeout(function() { // Si tu carga es asíncrona, pon esto en el callback de éxito
                        evController.on('scroll', scrollNotifyHandler);
                    }, 100);
                }else{
                    console.log("Sin elemeneots")
                    $('.loadMoreSpinNotify').remove();
                    let emptyNotify = `
                        <a href="javascript:;" class="dropdown-item wrap_no_notifications">
                            <div class="icon">
                                <i data-feather="alert-circle"></i>
                            </div>
                            <div class="content">
                                <p>Sin Notificaciones</p>
                                <p class="sub-text text-muted">
                                   No tienes notificaciones pendientes
                                </p>
                            </div>
                        </a>
                    `;
                    $(emptyNotify).appendTo(".inner-body-card-notify");
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log("Eror Ajax More Notify => ",thrownError);
            });//<--- AJAX	
        }

        btnEvent.on('click', function (ev) {
            console.log("Event Click en Notificaciones");
        if (evController[0].classList.contains('show')) {
                // Si ya está abierto, el click lo cerraría, así que prevenimos la acción
                ev.preventDefault();
                ev.stopPropagation();
                // No haces nada más
            }else {
                // Si no está abierto, el click lo abrirá normalmente
                $(".card-notify-list").remove(); // Eliminamos todas las notificaciones
                $('.wrap_no_notifications').remove(); // Eliminamos todos los nulls
                Checknotify(0);
            }
        });
    });
})(jQuery);