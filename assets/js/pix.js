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

function abrirPix() {
    openModal('loading');

        // let codePix = "00020126360014br.gov.bcb.pix0114+5531971307090520400005303986540510.005802BR5912FAROESTE20056014Belo Horizonte62240520mpqrinter2361507202663048183";
        // let base64 = "iVBORw0KGgoAAAANSUhEUgAABRQAAAUUAQAAAACGnaNFAAAH2ElEQVR42u3dQW7jSAwFUN2g7n9L3aBmFgNYIn+V5KQx6MXzopHEdumpdwTJr2P+9a/zYGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGT8vfGor3H52OVvnzf++/Xzxr+vctTnu9dTyhtPF2dkZGRkZGRkZGRkZHwyjmupUwD/nZl4n+skaDr0dvfLizMyMjIyMjIyMjIyMn5jLKVVq5FGu1iC5ptcvLu8OCMjIyMjIyMjIyMj4y+MH9nn188/xZgBZy68Us3FyMjIyMjIyMjIyMj4J43zfrHFcFubays3eetEXXmMjIyMjIyMjIyMjIx/yth+XZZMn4vN0CC69ZXKXS1bSj+b22NkZGRkZGRkZGRkZEyxAf/jPz/OVWBkZGRkZGRkZGRkZMyvW4Mo/W3Tderzb2my7lnAyMjIyMjIyMjIyMj4ZDxX+QTlireDU+eo9YbKnS4OaP8ZjIyMjIyMjIyMjIyMPzEuV3nKTy3RYK6i2xbxB+VzDz0kRkZGRkZGRkZGRkbGhbFoG3m0oigHEnRoMW7WgDaZBYyMjIyMjIyMjIyMjMmY20ejjcOVwOinBtFbaI9kY2RkZGRkZGRkZGRkfGnclj29v3OuHvI57u2omZ+vk2+tJ74xMjIyMjIyMjIyMjI+G69n9iG4lJTWBt6O57G529/aok/KMWBkZGRkZGRkZGRkZHwypifZ7PMENrXUmaMJEqWkSzMyMjIyMjIyMjIyMn5tvJZR5ad5bxq1UIHHXOhlXTfz03IYGRkZGRkZGRkZGRm/M46QED3uPy22c1bV0pGaRu28I6wVDUZGRkZGRkZGRkZGxtfG1Sd6ikBpOJXNnrF6qk6JrJ7rptHByMjIyMjIyMjIyMj4jbFt8Yz7F87WV0ppbK2COkI89WzxB3k/iJGRkZGRkZGRkZGR8ZUxPRmnKWZ4vucI/ae+GnQ9ecbRtyOFUjMyMjIyMjIyMjIyMr405i88PrJzU2TNfEMpRLptDzEyMjIyMjIyMjIyMr40dl7a8bmWTGm958Vg3PLd+ZBZwMjIyMjIyMjIyMjIuKm5+qM4c6G0mHV79W4PPcjjcIyMjIyMjIyMjIyMjK+MrefT13au7mPTcNokFSx2fFL1xcjIyMjIyMjIyMjI+I2xePahAsvln9x6Wsa5LXmMjIyMjIyMjIyMjIzfGEf46mw5021yrRdt5bx8Q7e+UinLGBkZGRkZGRkZGRkZXxo3I22poErhbMdKNvOjQtvVxnd7SIyMjIyMjIyMjIyMjGUSrlRauX00cuup1Vdnq7Rad2p5NUZGRkZGRkZGRkZGxvfGMsjW+kopHHqGYqyXZa366mNzq0syMjIyMjIyMjIyMjLujdfjRpiJO0JC21wlTpef8u7OkYKqw90zMjIyMjIyMjIyMjK+N6b0gmPTYdq8Sml1hMjqPoG33UNiZGRkZGRkZGRkZGTc7Pj04IK8zzNWH1k+bmcxDpfLMkZGRkZGRkZGRkZGxm+M8+n7y5G2siSU9n42qDM8fYeRkZGRkZGRkZGRkfEbY+flcmu3u9Om6PpN5ieCZgEjIyMjIyMjIyMjI+PemDs9M2/stGZQ6g0tjspF2xFug5GRkZGRkZGRkZGR8aVx5CKr1UPl4FJQHSFEupw8tzN225qLkZGRkZGRkZGRkZFxYSxjbsuSqXyjdH+WcdLLFaLkZmRkZGRkZGRkZGRk/Mb46nXma6fE6fLh9kaak3vuITEyMjIyMjIyMjIyMvaZuE3U85kvkXZ80npPiqfeVFqTkZGRkZGRkZGRkZHxtbGkp7Vw6J6Z1l4jUMq03Qjzb8cm+YCRkZGRkZGRkZGRkfGNsVRfPSY63VCbhButHVUCCdI8XbtJRkZGRkZGRkZGRkbGL41tO2cRprYZpSsPyhmtzZQulN5lZGRkZGRkZGRkZGR8bTxD+MByXq0nquWW0mINKPWL8pYRIyMjIyMjIyMjIyPjk3GZnvaim7Sckyv1VYkmuHaYbsN3D5nSjIyMjIyMjIyMjIyMux7SMqmgdZhmG25rk3VpOu7MZVn4L2BkZGRkZGRkZGRkZHwypnyCM2dA52/0KbrUddoUXunWGBkZGRkZGRkZGRkZn4zXAbVbaVW+mt5IE27plIJK+W1tOo6RkZGRkZGRkZGRkfHZeLSiqBRAKUztemZ/7E0uslKpVi7EyMjIyMjIyMjIyMj43rgpivreTzr9WL8WxvS10KdiZGRkZGRkZGRkZGT8yphrqZEv0VZ+SiNp99yc73LiGBkZGRkZGRkZGRkZ9z2kUiidYf8m1VzjKclt+WScdBuMjIyMjIyMjIyMjIyvjaOVW8v2UY5fG/fbTdEE6cZHuORgZGRkZGRkZGRkZGT8ztgCBM5VPkEZZDtzAkG6g5TV1obvJiMjIyMjIyMjIyMj46+MKVGtxaUl6GiHfk5JbascaM3IyMjIyMjIyMjIyPhbY9/nKe72t5EPTStEDfo0E8fIyMjIyMjIyMjIyPh+Jq7PuuXMtDMsCXVKK7LmKhyBkZGRkZGRkZGRkZHxpXGRWbA/MycQlF2gHaUM0LXajJGRkZGRkZGRkZGR8cn4t74YGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGRkZGX9s/AeQ06s5y3cA6AAAAABJRU5ErkJggg==";
        // let qrCode = ('src', 'data:image/jpeg;base64,' + base64);

        // let valorParcela = 40.15;
        // valorParcela = `Valor: ${valorParcela.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})}`;
        // let fatura = '08/2022';
        // fatura = `Fatura: ${fatura}`;

        // document.getElementById("qrCode").setAttribute('src', qrCode);
        // document.getElementById("codePix").setAttribute('value',codePix);
        // document.getElementById("valor").innerText = valorParcela;
        // document.getElementById("fatura").innerText = fatura;

        // openModal('dv-modal-pix');


    // setTimeout(() => {

    //     let codePix = "00020126360014br.gov.bcb.pix0114+5531971307090520400005303986540510.005802BR5912FAROESTE20056014Belo Horizonte62240520mpqrinter2361507202663048183";
    //     let base64 = "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";
    //     let qrCode = ('src', 'data:image/jpeg;base64,' + base64);

    //     if (codePix) {
    //         closeModal('dv-modal');
    //         Swal.fire({
    //             text: 'Pix copia e cola',
    //             //imageUrl: 'https://c.tenor.com/7K4cTi4-U5QAAAAM/snoopdogg-loading.gif',
    //             imageUrl: qrCode,
    //             imageWidth: 200,
    //             imageHeight: 200,
    //             imageAlt: 'qrcode',
    //             input: 'textarea',
    //             inputValue: codePix,
    //             confirmButtonColor: '#0d6efd',
    //             confirmButtonText: 'Fechar',
    //             inputAttributes: {
    //                 'aria-label': "codePix"
    //             },
    //         })
    //     }

    // }, "3000")

    $.post('payment.php', { pix: true }, function (response) {
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

            // console.log("qr", qrCode);
            // console.log("qr", codePix);
            console.log("retorno>>>",obj.dados.ref);

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
                title: 'Ocorreu um erro, tente novamente....'
            })
        }
    });
};