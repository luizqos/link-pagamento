const mp = new MercadoPago('APP_USR-aae0e43d-3f9a-46a6-883e-ab670dc5ccf2');

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

