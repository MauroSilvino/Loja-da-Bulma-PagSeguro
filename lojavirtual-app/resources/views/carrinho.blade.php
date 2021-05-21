@extends("layout")
@section("conteudo")

    @if($message = Session::get("err"))
        <div class="col-12 alert alert-danger">{{ $message }}</div>
    @endif

    @if($message = Session::get("ok"))
        <div class="col-12 alert alert-success">{{ $message }}</div>
    @endif

    @if(isset($cart))

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Foto</th>
                <th>Valor</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            @php $total =0; @endphp
            @foreach($cart as $indice => $p)
                <tr>
                    <td><a href="{{ route('carrinho_excluir', ['indice' => $indice]) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
                    <td>{{$p->nome}}</td>
                    <td><a href="{{ route('quantidade', ['quant' => '1']) }}"><i class="fas fa-plus-circle"></i></a>{{$p->quantidade}}<a href="{{ route('quantidade', ['quant' => '-1']) }}"><i class="fas fa-minus-circle"></i></a></td>
                    <td><img src="{{ asset($p->foto) }}" height="50"/></td>
                    <td>{{$p->valor}}</td>
                    <td>{{$p->descricao}}</td>
                </tr>
                @php $total += $p->valor; @endphp
            @endforeach
        </tbody>
        <tfooter>
            <tr>
                <td colspan="5"> Total do carrinho: R${{ $total }}</td>
            </tr>
        </tfooter>
    </table>

    <form action="{{ route('carrinho_finalizar') }}" method="POST"> 
        @csrf
        <input type="submit" value="Finalizar Comprar" class="btn btn-lg btn-success">
    </form>

    @else
        <p> Nenhum item no carrinho </p>

    @endif
@endsection
