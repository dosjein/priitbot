<link href="/priit/chat.css" rel="stylesheet">
<script   src="https://code.jquery.com/jquery-3.1.1.js"   integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="   crossorigin="anonymous"></script>

<div class="container hidden forpriit">
    <div class="row">
        <div class="panel panel-chat">
        <div class="panel-heading">
            <a href="#" class="chatMinimize" onclick="return false"><span>Drunk Buddy</span></a>
            <a href="#" class="chatClose" onclick="return false"><i class="glyphicon glyphicon-remove"></i></a>
            <div class="clearFix"></div>
        </div>
        <div class="panel-body">
            <div class="messageHer anymessage">
                <img src="/img/pub_bar-05-512.png" alt=""/>
                <span>Priit - whana drink and talk ???</span>
                <div class="clearFix"></div>
            </div>
            <div class="clearFix"></div>
        </div>
        <div class="panel-footer">
            <textarea class='textparts' name="textMessage" cols="0" rows="0"></textarea>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){

            var lastMsg = '';

            var lastResponse = '';

            var counter = 0;

            var responseUpdateRequested = 0;

            var processes = ['drinking' , 'sleeping' , 'vomit' , 'deleting' , 'texting'];

            function firstRemove(){
                while($('.anymessage').length >= 5){
                    if ($('.anymessage').length >= 5){
                        $('.anymessage').first().remove();
                    }
                }
            }

            function callOnMe(){

                if (!lastMsg || lastMsg.trim() == ''){
                    lastMsg = 'I am drunk';
                }

                $.getJSON( "/priit/beer/budy" , 
                    { message : lastMsg }, 
                    function( data ) {
                    if (data.msg && data.msg.response){

                       if (counter == 30){
                          lastMsg = data.progress;
                       }

                       if (lastResponse == data.msg.response){
                            if (responseUpdateRequested == 0){
                                lastMsg = lastMsg + ' attempt x';    
                            }else{
                                responseUpdateRequested = 1;
                            }

                            counter = counter + 1;
                            $('.typing_text').text($('.typing_text').text() + '....');

                            if (parseInt(counter/3) == (counter/3)){
                                $('.typing_text').text($('.typing_text').text() +' writting poem...');
                            }

                            if (parseInt(counter/5) == (counter/5)){
                                $('.typing_text').text($('.typing_text').text() + processes[Math.floor(Math.random()*processes.length)] + '...');
                            }

                            if (counter > 10){
                                $.getJSON( "/priit/beer/cache");
                            }

                            setTimeout(callOnMe , 100);
                       }else if (data.msg.response.indexOf(">") > -1){
                            lastMsg = lastMsg + ' attempt 2';
                            counter = counter + 1;
                            lastResponse = data.msg.response;
                            $('.typing_text').text(processes[Math.floor(Math.random()*processes.length)] + '...');
                            setTimeout(callOnMe , 100);                        
                       }else{
                           responseUpdateRequested = 0;
                           lastResponse = data.msg.response;
                           firstRemove();
                           $('.typing-block').first().remove();                      
                           $('.panel-body').append('<div class="anymessage messageHer"><img src="/img/pub_bar-05-512.png"/></a><span>'+ data.msg.response +'</span><div class="clearFix"></div></div>'
                            );
                           $('.anymessage').last().focus();
                           $(".textparts").prop('disabled', false).focus();
                           $(".textparts").prop('disabled', false).focus();
                       }
                    }else{
                        console.log('pukk');
                        counter = counter + 1;
                        if (counter == 10){
                            lastMsg = data.progress;
                            counter = 0;
                        }
                        setTimeout(callOnMe , 700);
                    }
                });
            }

            $('.textparts').keypress(function (e) {
              if (e.which == 13) {
                    //ajax request
                    lastMsg = $('.textparts').val();

                    callOnMe();

                    $('.panel-body').append('<div class="anymessage messageMe"><a href="https://www.facebook.com/salumaa"  alt="Priit" target="_blank"><img src="/img/priit.jpg" alt=""/></a><span>'+ $('.textparts').val() +'</span><div class="clearFix"></div></div>'
                    );

                   $('.panel-body').append('<div class="anymessage messageHer typing-block hidden"><img src="/img/pub_bar-05-512.png" alt=""/><span class="typing_text">Typing...</span><div class="clearFix"></div></div>'
                    );

                    setTimeout(function() {
                        firstRemove(); 
                        $('.typing-block').removeClass('hidden');
                    }, 1000);

                   $('.textparts').val('');
                   $(".textparts").prop('disabled', true);
              }
            });

            $(".panel.panel-chat > .panel-heading > .chatMinimize").click(function(){
                if($(this).parent().parent().hasClass('mini'))
                {
                    $(this).parent().parent().removeClass('mini').addClass('normal');

                    $('.panel.panel-chat > .panel-body').animate({height: "250px"}, 500).show();

                    $('.panel.panel-chat > .panel-footer').animate({height: "75px"}, 500).show();
                }
                else
                {
                    $(this).parent().parent().removeClass('normal').addClass('mini');

                    $('.panel.panel-chat > .panel-body').animate({height: "0"}, 500);

                    $('.panel.panel-chat > .panel-footer').animate({height: "0"}, 500);

                    setTimeout(function() {
                        $('.panel.panel-chat > .panel-body').hide();
                        $('.panel.panel-chat > .panel-footer').hide();
                    }, 500);


                }

            });
            $(".panel.panel-chat > .panel-heading > .chatClose").click(function(){
                $(".forpriit").addClass("hidden");
            });
        })
</script>