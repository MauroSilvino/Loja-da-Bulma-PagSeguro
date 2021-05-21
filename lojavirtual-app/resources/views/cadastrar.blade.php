@extends("layout")
@section("conteudo")
        
        @if($message = Session::get("err"))
                <div class="col-12 alert alert-danger">{{ $message }}</div>
        @endif

        @if($message = Session::get("ok"))
            <div class="col-12 alert alert-success">{{ $message }}</div>
        @endif

        <form action="{{ route('cadastrar_cliente') }}" method="POST">
            @csrf
            <fieldset>
                <legend>Cadastrar Cliente</legend>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group ">
                            Nome:<input type="text" name="nome" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            Email:<input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            Cpf:<input type="text" name="cpf" id="cpf" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            Senha:<input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            Endereço:<input type="text" name="endereco" class="form-control">
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                            Número:<input type="text" name="numero" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            Complemento:<input type="text" name="complemento" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            Cep:<input type="text" name="cep" id="cep" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            Cidade:<input type="text" name="cidade" class="form-control">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            Estado:<input type="text" name="estado" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <input type="submit" value="Cadastrar" class="btn btn-success btn-sm">
                        </div>
                    </div>
                
                </fieldset>
            </form>
    </div>
@endsection
