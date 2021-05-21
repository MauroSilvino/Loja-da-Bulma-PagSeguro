@extends("layout")
@section("conteudo")

<div class="col-6">
    <img src="{{ asset($prod->foto) }}" class="card-img-top"/>
</div>
<div class="col-6">
    <h3>{{ $prod->nome }} </h3>
    <p>{{ $prod->descricao }}</p>
    <a href="{{ route('adicionar_carrinho', ['idproduto' => $prod->id]) }}" class="btn btn-sm btn-secondary">Adicionar Item</a>
</div>

@endsection