const positionTopEnd = document.querySelector("#position-top-end");
positionTopEnd && (positionTopEnd.onclick = function() {
    Swal.fire({
        position: "top-end",
        icon: "success",
        text: "Your work has been saved",
        showConfirmButton: !1,
        timer: 1500,
        customClass: {
            confirmButton: "btn btn-primary"
        },
        buttonsStyling: !1
    })
});