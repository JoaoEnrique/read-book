@extends('layouts.main')
@section('title', 'Read Books - Livros')

@section('content')
    <div class="container">

        <h1  class="text-center mb-5 mt-5">Adicionar Livro</h1>

        <form class="d-flex mb-5" method="POST" action="/create">
            @csrf
            <input class="form-control me-2 @error('nome1') is-invalid @enderror" type="text" id="nome1" name="nome1" value="{{$book_title}}" placeholder="Pesquisar novo livro" aria-label="Search">
            <button class="btn" style="background: @if (auth()->check() && auth()->user()->primary_color) {{auth()->user()->primary_color}} @else #5bb4ff @endif!important; color: #fff; padding: 0; min-width: 50px;" type="submit">
                <img height="40px" src="{{asset('img/search.png')}}" alt="" srcset="">
            </button>
        </form>
        @error('nome1')
            <span class="invalid-feedback" style="display: block!important" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        {{-- <form action="/create" method="post">
            @csrf
            <div class="row mb-5">
                <div class="col-10 mb-3">
                    <input placeholder="Pesquisar meus livros" value="{{$book_title}}" type="text" class="form-control @error('nome1') is-invalid @enderror" id="nome1" name="nome1">
                    @error('nome1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-2">
                    <button class="btn btn-blue" type="submit">
                      <img height="20px" src="{{asset('img/search.png')}}" alt="" srcset="">
                    </button>
                </div>
            </div>
        </form> --}}

        <div class="row row-list-livros">
            @if (!empty($livros))
                <div class="col col-livro cursor-pointer">
                    <a class="link-livro" href="/create/outro-livro/{{$book_title}}">
                        <div class="livro livro-outro"  style="background-image:url('../img/book_transparent.png');">
                            <h1>Outro</h1>
                        </div>
                    </a>
                </div>
                
                @foreach ($livros as $livro)
                    <div class="col col-livro">
                        <a class="link-livro" href="/google-livro/{{$livro['id']}}">
                            <div class="livro" style="background-image:url('{{ asset($livro['thumbnail']) }}');">
                                @if ($livro['thumbnail'] == '../img/book_transparent.png')
                                    <h1>{{ $livro['title']}}</h1>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif

        @if(auth()->check())
            <!-- Modal de apar livro -->
            <div class="modal fade" id="compartilhar-livro" tabindex="-1" aria-labelledby="compartilhar-livro" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title compartilhar-livro" id="compartilhar-livro">Lik copiado</h5>
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

        @if(auth()->check())
            <div class="modal fade" id="outro-livro" tabindex="-1" aria-labelledby="outro-livro" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-cadastrar-livro">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title outro-livro" id="outro-livro">Cadastrar livro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="form-cadastrar-livro-google" action="/createDois" method="post" enctype="multipart/form-data">
                                @csrf
                                <h1 class="text-center">Cadastre um Livro</h1>
                                <div class="mb-3">
                                    <label for="nome_livro" class="form-label">Nome do Livro</label>
                                    <input type="text" class="form-control" id="nome_livro" name="nome_livro">
                                    @error('nome_livro')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="img_livro_usuario" data-label-image class="form-label">Capa do Livro
                                        <input type="file" class="d-none" data-input-image id="img_livro_usuario" name="img_livro_usuario">
                                        <div class="img-escolher-capa">
                                            Clique para escolher imagem
                                            <img data-image-preview height="300px" width="200px" class="img-escolher-capa">
                                        </div>
                                    </label>
                                </div>

                                <div class="mb-4">
                                    <label for="data_inicio" class="form-label">Data de inicio da leitura</label>
                                    <input id="data_inicio" name="data_inicio" placeholder="06/06/2023" type="date" class="form-control">
                                    <div id="passwordHelpBlock" class="form-text">
                                        Deixe esse campo em branco para o livro ser adicionado na lista de desejos. Preecnha esse campo se você já começou a ler esse livro
                                    </div>

                                    @error('data_inicio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="mb-4">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" value="" id="tempo_leitura" checked>
                                        <label class="form-check-label" for="tempo_leitura">
                                        Calcular tempo de leitura
                                        </label>
                                    </div>
                                    <div id="passwordHelpBlock" class="form-text">
                                        Deixe esse campo em branco para colocar o tempo de leitura manualmente
                                    </div>
                                </div>

                                <input type="text" class="d-none form-control" id="pagina_total" name="pagina_total" value="0">
                                <div class="mb-4">
                                    <label for="descricao" class="form-label">Descrição do Livro</label>
                                    <textarea placeholder="Temas do livro ou sinopse" class="form-control @error('descricao_livro') is-invalid @enderror" id="descricao_livro" required name="descricao_livro" rows="1"></textarea>

                                    @error('descricao_livro')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror

                                </div>

                                <button type="submit" style="width: 100%" class="btn btn-blue">Cadastrar</button>
                            </form>
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
    </div>

@if(isset($livro?->id_livro))
    <input type="text" style="position: absolute; top: 0; z-index: -1;" id="link" value="{{ config("app.url") }}/compartilhar-um-livro/{{$livro?->id_livro}}">
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
