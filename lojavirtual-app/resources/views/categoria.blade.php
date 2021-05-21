@extends("layout")
@section("conteudo")
    <div class="col-3">
        @if(isset($listaCategoria))
            <div class="list-group">   
                <a href="{{ route('categoria') }}" class="list-group-item list-group-item-action @if(0 == $idcategoria) active @endif">Todas</a></li>
                    @foreach($listaCategoria as $cat)
                        <a href="{{ route('categoria_por_id', ['idcategoria' => $cat->id]) }}" class="list-group-item list-group-item-action @if($cat->id == $idcategoria) active @endif">{{ $cat->categoria }}</a></li>
                    @endforeach
            </div>
        @endif
    </div>

    <div class="col-9">
        @include("_produtos", ['lista' => $lista])
    </div>
@endsection

