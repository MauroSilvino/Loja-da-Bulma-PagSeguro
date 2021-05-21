            @if(isset($lista))
                <div class="row">
                    @foreach($lista as $prod)
                        <div class="col-3 mb-3">
                            <div class="card">
                                <a href="{{ route('pagina_produto', ['idproduto' => $prod->id]) }}">
                                    <img src="{{ asset($prod->foto) }}" class="card-img-top">
                                </a>
                                    <div class="card-body">
                                    <a href="{{ route('pagina_produto', ['idproduto' => $prod->id]) }}"><h6 class="card-title">{{ $prod->nome }} - R${{ $prod->valor }}</h6></a>
                                    <a href="{{ route('adicionar_carrinho', ['idproduto' => $prod->id]) }}" class="btn btn-sm btn-secondary">Adicionar Item</a>
                                    </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif