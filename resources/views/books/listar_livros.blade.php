@extends('layouts.main')
@section('title', 'Read Books - Livros')

@section('content')
    <div class="container container-livros">

        @if (empty($compartilhado))
            @section('meta_title', 'Read Books - Meus livros')
            @section('meta_description', 'Visualize todos os meus livros que estão cadastrados no Read Books')
            @section('meta_keywords', 'livros, leitura, rede social, Read Books, meus livros')
            @section('meta_image', asset('img/estante_icon_fundo.png'))
            @section('meta_url', url()->current())

            @php
                $text = "Meus livros"
            @endphp
            <input type="text" style="position: absolute; top: 0; z-index: -1;" id="link" value="{{ config("app.url") }}/compartilhar-livro/{{auth()->user()->id}}">

        @else
            @section('meta_title', 'Read Books - livros de ' . $user->name)
            @section('meta_description', 'Visualize todos os livros de ' . $user->name . ' que estão cadastrados no Read Books')
            @section('meta_keywords', 'livros, leitura, rede social, Read Books, ' . $user->name)
            @section('meta_image', asset('img/estante_icon_fundo.png'))
            @section('meta_url', url()->current())

            @php
                $text = "Livros de $user->name"
            @endphp

        @endif

        @if(isset($_GET['filtro']) && $_GET['filtro'])
                @if($_GET['filtro'] == 'lidos')
                    <h1  class="text-center titulo-livros">{{ $text }} - lidos</h1>

                @elseif($_GET['filtro'] == 'nao_lidos')
                    <h1 class="text-center titulo-livros">{{ $text }} - não finalizados</h1>

                @elseif($_GET['filtro'] == 'lista_desejo')
                    <h1  class="text-center titulo-livros">{{ $text }} - lista de desejo</h1>
                @endif

                <p class="text-center text-quantidade-livros">livros encontrados {{$quantidadeLivrosLidos}}</p>
        @else
            @if ($livros != "[]")
                <h1  class="text-center titulo-livros">{{ $text }}</h1>
                @if(isset($_GET['filtro']) && $_GET['filtro'])
                    <p class="text-center text-quantidade-livros">livros encontrados {{$quantidadeLivrosLidos}}</p>
                @else
                    <p class="text-center text-quantidade-livros">Terminou de ler {{$quantidadeLivrosLidos}} de {{$quantidadeLivros}} livros</p>
                    @if($quantidadeListaDesejo)
                        <p class="text-center text-quantidade-livros" style="margin-top: -10px">Lista de desejo: {{$quantidadeListaDesejo}}</p>
                        <p class="text-center text-quantidade-livros" style="margin-top: -10px">Total de livros: {{count($livros)}}</p>
                    @endif
                @endif
            @endif
        @endif

        <!-- NOTIFICACAO -->
        @include('components.message_danger')
        @include('components.message_success')


        @if(empty($compartilhado))
            <div class="btn-group dropup" style="border: 0">
                <button style="border: 0; z-index: 1" type="button" class="btn-adicionar-livro" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg id="svg-adicionar-livro" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    <li><a id="btnCopiar" data-bs-toggle="modal" data-bs-target="#compartilhar-livro" class="dropdown-item" id="btnCopiar" href="#">
                         <i style="height: 20px" data-lucide="share-2"></i>
                         Compartilhar
                        </a></li>
                    <li><a class="dropdown-item" href="/criar">
                         <i style="height: 20px" data-lucide="circle-plus"></i>
                        Adicionar livro
                    </a></li>
                </ul>
            </div>
        @endif

        {{-- PESQUISA --}}
        @if (empty($compartilhado))
            @if (count($livros) && !isset($_GET['filtro']) || isset($_GET['filtro']))
                <div class="row row-pesquisa-livro" style="margin-bottom: 100px">
                    <div class="col">
                        <form class="d-flex" method="GET" action="/pesquisa">
                            @csrf
                            <input class="form-control me-2" type="search" id="livro" name="livro" placeholder="Pesquisar meus livros" aria-label="Search">
                            <button class="btn" style="background: @if (auth()->check() && auth()->user()->primary_color) {{auth()->user()->primary_color}} @else #5bb4ff @endif!important; color: #fff; padding: 0; min-width: 50px;" type="submit">
                                <img height="40px" src="{{asset('img/search.png')}}" alt="" srcset="">
                            </button>
                        </form>
                        <form action="{{ route('livros.filtrar') }}" method="get">
                            <select name="filtro" class="form-select mt-4" aria-label="Default select example" onchange="this.form.submit()">
                                <option value="todos" {{ request('filtro') == 'todos' ? 'selected' : '' }}>Meus Livros</option>
                                <option value="lidos" {{ request('filtro') == 'lidos' ? 'selected' : '' }}>Lidos</option>
                                <option value="nao_lidos" {{ request('filtro') == 'nao_lidos' ? 'selected' : '' }}>Não lidos</option>
                                <option value="lista_desejo" {{ request('filtro') == 'lista_desejo' ? 'selected' : '' }}>Lista de desejo</option>
                            </select>
                        </form>
                    </div>
                </div>
            @endif
        @endif


        <div class="row row-list-livros">
            @if ($livros == "[]" && !isset($_GET['filtro']))
                <h1 class="text-center">Você não tem nenhum livro cadastrado</h1>
                <a class="btn mt-5" href="/criar" style="background: @if (auth()->check() && auth()->user()->primary_color) {{auth()->user()->primary_color}} @else #5bb4ff @endif!important; color: #fff">Clique aqui para cadastrar</a>
            @else
                @foreach ($livros as $livro)
                    @php
                        if(empty($compartilhado))
                            $link = "/livro/$livro->id_livro";
                        else
                            $link = env('APP_URL') . "/compartilhar-um-livro/$livro->id_livro";
                    @endphp

                    <div class="col col-livro">
                        <a class="link-livro" href="{{$link}}">
                            <div class="livro  @if ($livro->img_livro == '../img/book_transparent.png') img-capa-default @endif"  @if ($livro->img_livro != '../img/book_transparent.png') style="background-image:url('{{ asset($livro->img_livro) }}');" @else style="background-image:url('../img/book_transparent.png');" @endif>
                                @if ($livro->img_livro == '../img/book_transparent.png')
                                    <h1>{{ $livro->nome_livro }}</h1>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    @if(auth()->check())
        <!-- Modal de apar livro -->
        <div class="modal fade" id="compartilhar-livro" tabindex="-1" aria-labelledby="compartilhar-livro" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title compartilhar-livro" id="compartilhar-livro">Compartilhar todos os livros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Link: <a href="{{ config("app.url") }}/compartilhar-livro/{{auth()->user()->id}}">{{ config("app.url") }}/compartilhar-livro/{{auth()->user()->id}}</a>
                        De todos os livros.
                    </div>
                    <div class="modal-footer" style="flex-wrap: nowrap">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100%">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Adiciona um evento de clique ao botão
        document.getElementById("btnCopiar").addEventListener("click", function() {
            // Seleciona o texto da área de texto
            var texto = document.getElementById("link");
            texto.select();
            texto.setSelectionRange(0, 99999); // Para dispositivos móveis

            // Tenta copiar o texto para a área de transferência
            try {
                document.execCommand("copy");
                document.querySelector(".compartilhar-livro").innerHTML = "Link Copiado"
                // alert("Link copiado, agora você pode compartilhar para quem quiser");
            } catch (err) {
                // alert("Erro ao copiar texto: " + err);
                document.querySelector(".compartilhar-livro").innerHTML = "Erro ao copiar link"
            }
        });
    });
    </script>
@endsection
