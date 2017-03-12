<?php

return [

    /*
    |--------------------------------------------------------------------------
    | dosjein/chatbot-rnn chatbot url
    |--------------------------------------------------------------------------
    |
    | More info : https://github.com/dosjein/chatbot-rnn
    |
    */
 
    'chatbot_url'  => env('CHATBOT_URL' , false),

    /*
    |--------------------------------------------------------------------------
    | dosjein/chatbot-rnn chatbot token
    |--------------------------------------------------------------------------
    |
    | Response token from CHATBOT_URL/?NEW 
    | { 'ident' : chatbot_token }
    |
    */
 
    'chatbot_token'  => env('CHATBOT_TOKEN' , false)

];