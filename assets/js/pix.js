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

function buscaValor() {
    openModal('dv-modal-cpf');
}
function abrirPix() {
    let cpf = document.getElementById("cpf").value;

    if (validarCPF(cpf) === true) {
        closeModal('dv-modal-cpf');

        openModal('loading');

        $.post(`payment.php?doc=${cpf}`, { pix: true }, function (response) {
            try {

                let obj = JSON.parse(response);
                if (typeof obj.pix.status != "undefined") {
                    if (obj.pix.status) {
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
                            title: 'Ocorreu um erro, tente novamente!'
                        })
                    } else {

                        if (obj.pix.transaction_data.qr_code_base64 == NULL ||
                            typeof obj.pix.transaction_data.qr_code_base64 == "undefined") {
                            closeModal('loading');
                            closeModal('dv-modal-pix');
                            return false;
                        }

                    }
                }
                let base64 = obj.pix.transaction_data.qr_code_base64;
                let codePix = obj.pix.transaction_data.qr_code;

                let qrCode = ('src', 'data:image/jpeg;base64,' + base64);

                //console.log("retorno>>>",obj);

                if (obj) {
                    let valorParcela = Number(obj.dados.valor);
                    valorParcela = `Valor: ${valorParcela.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })}`;
                    let fatura = obj.dados.ref;
                    fatura = `Fatura: ${fatura}`;

                    document.getElementById("qrCode").setAttribute('src', qrCode);
                    //document.getElementById("codePix").setAttribute('value',codePix);
                    document.getElementById("codePix").innerText = codePix;
                    document.getElementById("valor").innerText = valorParcela;
                    document.getElementById("fatura").innerText = fatura;
                    closeModal('loading');
                    openModal('dv-modal-pix');
                }

            } catch (e) {
                closeModal('loading');
                closeModal('dv-modal-pix');
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
                    title: 'Ocorreu um erro, tente novamente.'
                })
            }
        });
    } else {
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
            title: 'CPF digitado não é válido'
        })
    }
};