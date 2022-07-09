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

function buscaValor() {
    openModal('dv-modal-cpf');
}
function abrirPix() {
    let cpf = document.getElementById("cpf").value;

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
                valorParcela = `Valor: ${valorParcela.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})}`;
                let fatura = obj.dados.ref;
                fatura = `Fatura: ${fatura}`;
        
                document.getElementById("qrCode").setAttribute('src', qrCode);
                document.getElementById("codePix").setAttribute('value',codePix);
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
};