@extends("layout")
@section("conteudo")

    @if($message = Session::get("err"))
        <div class="col-12 alert alert-danger">{{ $message }}</div>
    @endif

    @if($message = Session::get("ok"))
        <div class="col-12 alert alert-success">{{ $message }}</div>
    @endif


    

    <form action="{{ route('logar') }}" method="POST">
        @csrf
            <fieldset>
                <legend>Login no Sistema</legend>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group ">
                            Login:<input type="text" name="login" id="login" class="form-control">
                        </div>
                        <div class="form-group ">
                            Senha:<input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Fazer Login" class="btn btn-lg btn-primary">
                        </div>
                    </div>
                </div>
            </fieldset>
    </form>

@endsection