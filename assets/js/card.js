(function (win, doc) {
    "use strict";

    //Public Key
    window.Mercadopago.setPublishableKey("TEST-3e3952d9-bfbc-4200-b369-4deb51ffdc48");

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
    }
    if (doc.querySelector('#cardNumber')) {
        let cardNumber = doc.querySelector('#cardNumber');
        cardNumber.addEventListener('keyup', cardBin, false);

    }

    //Set Installments
    function setInstallmentInfo(status, response) {
        console.log("resposen parcela", response);
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
    openModal('dv-modal-card');
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

