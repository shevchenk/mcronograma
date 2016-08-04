@extends('layouts/main')

@section('body')
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/chat">Chat</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img class="img-circle" width="30" height="30" src="img/user/<?= md5('u'.Auth::user()->id).'/'.Auth::user()->imagen; ?>"  alt="User Image" />

                            {{ Auth::user()->nombre.' '.Auth::user()->paterno  }} 
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Home</a></li>

                            <li class="divider"></li>
                            <li><a href="{{ action('AuthController@logout') }}">Cerrar  sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 new-message text-right">
                <a id="btnNewMessage" class="btn btn-sm btn-default" role="button"><i class="fa fa-plus"></i> Nuevo mensaje</a>
            </div>	        
        </div>
        <div class="row">
            <div id="conversationList">
                @include('templates/conversations', array('conversations' => $conversations, 'current_conversation' => $current_conversation))
            </div>
            <div class="col-lg-8">
                @if($current_conversation)
                    <div class="panel panel-default">
                        <div id="messageList" class="panel-body messages-panel">
                            @include('templates/messages', array('messages' => $current_conversation->messages))
                        </div>
                    </div>
                    {{ Form::open(array('action' => 'MessageController@store')) }}
                        <textarea id="messageBox" class="form-control send-message" rows="3" placeholder="Escribe una respuesta..."></textarea>
                        <div class="send-message">
                            <a id="btnSendMessage" class="text-right btn btn-sm btn-danger pull-right" role="button"><i class="fa fa-send"></i> Enviar mensaje</a>
                        </div>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var 
            current_conversation = "{{ Session::get('current_conversation') }}",
            user_id   = "{{ Auth::user()->id }}";
    </script>
    <script src="{{ asset('/js/chat.js')}}"></script>
@stop

@include('templates/new_message_modal', array('recipients' => $recipients))
