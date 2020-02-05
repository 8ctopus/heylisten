import Swal from 'sweetalert2'

const _toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})

const toast = ({ icon = 'success', title = '' }) => {
    _toast.fire({
        icon,
        title
    })
}

const swal = async ({ title = '', text = '', icon = 'warning', showCancelButton = true, confirmButtonText = 'ok', cancelButtonText = 'cancel' }) => {
    const { value } = await Swal.fire({ title, text, icon, showCancelButton, confirmButtonText, cancelButtonText, })
    return value
}

export { toast, swal }
