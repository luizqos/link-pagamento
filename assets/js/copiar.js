function copiar() {
    let copiado = document.getElementById("codePix").value;
    //console.log("copiado>>>", copiado.value);

    navigator.clipboard.writeText(copiado);

    if (navigator.clipboard.writeText(copiado)) {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: 'Pix copiado'
        })

        setInterval(function () {
            closeModal('dv-modal-pix');
        }, 30000
        );
    }
    else {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: 'Ocorreu um erro ao copiar Pix'
        })
    }


}