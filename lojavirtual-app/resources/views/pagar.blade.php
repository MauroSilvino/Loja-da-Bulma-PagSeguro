@extends("layout")
@section("script")
<script>
    function carregar(){
        PagSeguroDirectPayment.setSessionId('{{ $sessionID }}') 
    }

    $(function(){
        carregar();
        $(".ncredito").on('blur', function(){
            PagSeguroDirectPayment.onSenderHashReady(function(response){
                if(response.status == 'error'){
                    console.log(response.message)
                    return false
                }
                
                var hash = response.senderHash
                $(".hashseller").val(hash)
            })

            let ncartao = $(this).val()
            
            $(".bandeira").val("")
            if(ncartao.length > 6){
                let prefixcartao = ncartao.substr(0, 6)
                PagSeguroDirectPayment.getBrand({
                    cardBin : prefixcartao,
                    success : function(response){
                        $(".bandeira").val(response.brand.name)
                    },
                    error : function(response){
                        alert("Numero do cartão inválido")
                    }

                })
            }
        })

        $(".nparcela").on('blur', function(){
            var bandeira = $(".bandeira").val();
            var totalParcelas = $(this).val();

            if(bandeira == ""){
                alert("Preencha o numero do cartão válido")
                return;
            }
        
            PagSeguroDirectPayment.getInstallments({
                amount : $(".totalfinal").val(),
                maxInstallmentNoInterest : 1,
                brand : bandeira,
                success : function(response){
                    console.log(response);
                    let status = response.error
                    if(status){
                        alert("Não foi encontrado a opção de parcelamento")
                        return;
                    }

                    let indice = totalParcelas - 1;
                    let totalapagar = response.installments[bandeira][indice].totalAmount
                    let valorTotalParcela = response.installments[bandeira][indice].installmentAmount

                    $(".totalparcela").val(valorTotalParcela)
                    $(".totalapagar").val(totalapagar)
                }

            })
        
        })

        $(".nomecartao").on('click', function(){
            var numerocartao = $(".ncredito").val()
            var iniciocartao = numerocartao.substr(0, 6)
            var ncvv = $(".ncvv").val()
            var mesexp = $(".mesexp").val()
            var anoexp = $(".anoexp").val()
            var hashseller = $(".hashseller").val()
            var bandeira = $(".bandeira").val()

            PagSeguroDirectPayment.createCardToken({
                cardNumber : numerocartao,
                brand : bandeira,
                cvv : ncvv,
                expirationMonth : mesexp,
                expirationYear : anoexp,
                success : function(response){
                    var cardtoken = response.card.token
                    $(".cardtoken").val(cardtoken)
                    
                    
                },
                error : function(err){
                    alert("Erro ao buscar token do cartão")
                    console.log(err)
                }

            })

        })

    })

    


    </script>
@endsection
@section("conteudo")
        @php $total =0; @endphp
        @if(isset($cart))

        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Foto</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $indice => $p)
                    <tr>
                        <td>{{$p->nome}}</td>
                        <td><img src="{{ asset($p->foto) }}" height="50"/></td>
                        <td>{{$p->valor}}</td>
                    </tr>
                    @php $total += $p->valor; @endphp
                @endforeach
            </tbody>
        </table>
        @endif
        <form action="{{ route('pagseguro') }}" method="POST">
        <div class="row">

            <input type="hidden" name="hashseller" class="hashseller"/>
            <input type="hidden" name="cardtoken" class="cardtoken"/>
            <input type="hidden" name="nparcela"  value="1"class="nparcela form-control" />
            <input type="hidden" name="totalparcela" value="{{ $total }}" class="totalparcela form-control" readonly/>
            <input type="hidden" name="totalapagar" value="{{ $total }}" class="totalapagar form-control" readonly/>
            
            <div class="col-2">
                Bandeira:
                <input type="text" name="bandeira" class="bandeira form-control" readonly/>
            </div>
            <div class="col-7">
                Cartão de Crédito:
                <input type="text" name="ncredito" class="ncredito form-control"/>
            </div>
            <div class="col-3">
                CVV:
                <input type="text" name="ncvv" class="ncvv form-control"/>
            </div>
            <div class="col-2">
                Mês de expiração:
                <input type="text" name="mesexp" class="mesexp form-control"/>
            </div>
            <div class="col-2">
                Ano de expiração:
                <input type="text" name="anoexp" class="anoexp form-control"/>
            </div>
            <div class="col-8">
                Nome no Cartão:
                <input type="text" name="nomecartao" class="nomecartao form-control"/>
            </div>
            <div class="col-2">
                Valor Total:
                <input type="text" name="totalfinal" value="{{ $total }}" class="totalfinal form-control" readonly/>
            </div>
            @csrf
        </div>
        
        <input type="submit" value="Pagar" class="btn btn-lg btn-success pagar"/>
        
    </form>
@endsection