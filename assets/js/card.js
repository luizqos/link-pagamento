(function (win, doc) {
    "use strict";
    let getUrl = window.location;
    let baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    let contrasenha = '$2a$10$g9y0p121fpWpgALlFnFux.MGK90y.5RA57/RKxkaWClcICnleT0iO';
            
    //Public Key
   $.getJSON(baseUrl + "/config/getKey.php?req=" + contrasenha, function( data ) {
    window.Mercadopago.setPublishableKey(data.key);
    });

    //Docs Type
    window.Mercadopago.getIdentificationTypes();

    //Card bin
    function cardBin(event) {
        let textLength = event.target.value.replace(" ", "").length;

        if (textLength >= 6) {
            let bin = event.target.value.replace(" ", "").substring(0, 6);
            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);

            Mercadopago.getInstallments({
                "bin": bin,
                "amount": parseFloat(document.querySelector('#amount').value),
            }, setInstallmentInfo);
        }
        if (textLength >= 16) {
            document.getElementById("Parcelamento").innerText = 'Parcelamento';
            document.getElementById("installments").removeAttribute("hidden");
        }
    }
    if (doc.querySelector('#cardNumber')) {
        let cardNumber = doc.querySelector('#cardNumber');
        cardNumber.addEventListener('keyup', cardBin, false);

    }

    //Set Installments
    function setInstallmentInfo(status, response) {

        let label = response[0].payer_costs;
        let installmentsSel = doc.querySelector('#installments');
        installmentsSel.options.length = 0;

        label.map(function (elem, ind, obj) {
            let txtOpt = elem.recommended_message;
            let valOpt = elem.installments;
            installmentsSel.options[installmentsSel.options.length] = new Option(txtOpt, valOpt);
        });

    }

    //Set Payment
    function setPaymentMethodInfo(status, response) {
        if (status == 200) {
            const paymentMethodElement = doc.querySelector('input[name=paymentMethodId]');
            paymentMethodElement.value = response[0].id;
            doc.querySelector('.brand').innerHTML = "<img src='" + response[0].thumbnail + "' alt='Bandeira do Cartão'>";
        } else {
            alert(`payment method info error: ${response}`);
        }
    }

    //Create Token
    function sendPayment(event) {
        event.preventDefault();
        window.Mercadopago.createToken(event.target, sdkResponseHandler);
    }
    function sdkResponseHandler(status, response) {
        if (status == 200 || status == 201) {
            let form = doc.querySelector('#pay');
            let card = doc.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', response.id);
            form.appendChild(card);
            form.submit();
        }
    }
    if (doc.querySelector('#pay')) {
        let formPay = doc.querySelector('#pay');
        formPay.addEventListener('submit', sendPayment, false);
    };
})(window, document);

function openModal(mn) {
    let modal = document.getElementById(mn);

    if (typeof modal == 'undefined' || modal === null)
        return;

    modal.style.display = 'Block';
    document.body.style.overflow = 'hidden';
}

function closeModal(mn) {
    let modal = document.getElementById(mn);

    if (typeof modal == 'undefined' || modal === null)
        return;

    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function buscaValorCard() {
    openModal('dv-modal-cpf-card');
}

function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf == '') return false;
    // Elimina CPFs invalidos conhecidos	
    if (cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999")
        return false;
    // Valida 1o digito	
    add = 0;
    for (i = 0; i < 9; i++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(9)))
        return false;
    // Valida 2o digito	
    add = 0;
    for (i = 0; i < 10; i++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(10)))
        return false;
    return true;
}

function abrirCard() {
    let cpf = document.getElementById("cpf-card").value;

    if (validarCPF(cpf) === true) {
        closeModal('dv-modal-cpf-card');

        openModal('loading');
        
        $.post(`./payment/Card.php?doc=${cpf}`, { card: true }, function (response) {
       
            try {

                let obj = JSON.parse(response);

                closeModal('loading');
                openModal('dv-modal-card');
                
                    if (obj) {
                        let cpfCadastro = obj.dados.cpf;
                        let email = obj.dados.email;
                        let descricao = obj.dados.descricao;
                        let idvenda = obj.dados.idVenda;
                        
                        let valorParcela = Number(obj.dados.valor);
                        valorParcelaFormat = `Valor: ${valorParcela.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}`;
                        
                        let fatura = obj.dados.ref;
                        fatura = `Fatura: ${fatura}`;
                        
                        document.getElementById("descricao-titulo").innerText = descricao + ' | ' + valorParcelaFormat;
                        document.getElementById("docNumber").value = cpfCadastro;
                        document.getElementById("docNumber").innerText = cpfCadastro;
                        document.getElementById("email").value = email;
                        document.getElementById("email").innerText = email;
                        document.getElementById("amount").value = valorParcela;
                        document.getElementById("amount").innerText = valorParcela;
                        document.getElementById("description").value = descricao;
                        document.getElementById("description").innerText = descricao;
                        document.getElementById("idvenda").value = idvenda;
                        document.getElementById("idvenda").innerText = idvenda;
                        closeModal('loading');
                    }else{
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        closeModal('loading');
                        closeModal('dv-modal-card');
                        Toast.fire({
                            icon: 'info',
                            title: 'Não identificamos débitos pendentes para este CPF'
                        })
                    }
    
            } catch (e) {
                //console.log("erro>>>", e);
                closeModal('loading');
                closeModal('dv-modal-card');
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'info',
                    title: 'Não identificamos débitos pendentes para este CPF'
                })
            }
        });
    }
    else{
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: 'CPF Inválido.'
        })
    }

}

$("select[id$=EMonth]").blur(function () {

    let expirationM = $(this).val($('select[id$=EMonth]').val());
    expirationM = expirationM[0].value;
    document.getElementById("cardExpirationMonth").value = expirationM;
    
});

$("select[id$=EYear]").blur(function () {

    let expirationY = $(this).val($('select[id$=EYear]').val());
    expirationY = expirationY[0].value;
    document.getElementById("cardExpirationYear").value = expirationY;

});

/* POPULA CAMPO ANO CARTÃO DE CREDITO*/
let anos = [];
let dataAtual = new Date();
let anoAtual = dataAtual.getFullYear();

let ano = anos.push();

for(let i = 0; i < 25; i++){
    ano = anos.push(anoAtual);
    anoAtual = anoAtual + 1;
}
let s = document.getElementById('EYear');
anos.forEach(function(chave) {
    s.appendChild(new Option(chave, chave));
});

function botaoBloqueado() {
    setTimeout(() => {       
        document.getElementById("confirmar").hidden = true;
        document.getElementById("aguarde").removeAttribute("hidden");
      }, "500");
}