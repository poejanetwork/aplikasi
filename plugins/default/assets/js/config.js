! function() {
    var t = sessionStorage.getItem("__ABUPUJABORON_CONFIG__"),
        e = document.getElementsByTagName("html")[0],
        i = {
            theme: "light",
            nav: "vertical",
            layout: {
                mode: "fluid"
            },
            topbar: {
                color: "light"
            },
            menu: {
                color: "dark"
            },
            sidenav: {
                size: "default",
                user: !1
            }
        },
        n = (this.html = document.getElementsByTagName("html")[0], config = Object.assign(JSON.parse(JSON.stringify(i)), {}), this.html.getAttribute("data-bs-theme")),
        n = (config.theme = null !== n ? n : i.theme, this.html.getAttribute("data-layout")),
        n = (config.nav = null !== n ? "topnav" === n ? "horizontal" : "vertical" : i.nav, this.html.getAttribute("data-layout-mode")),
        n = (config.layout.mode = null !== n ? n : i.layout.mode, this.html.getAttribute("data-topbar-color")),
        n = (config.topbar.color = null != n ? n : i.topbar.color, this.html.getAttribute("data-sidenav-size")),
        n = (config.sidenav.size = null !== n ? n : i.sidenav.size, this.html.getAttribute("data-sidenav-user")),
        n = (config.sidenav.user = null !== n || i.sidenav.user, this.html.getAttribute("data-menu-color"));
    if (config.menu.color = null !== n ? n : i.menu.color, window.defaultConfig = JSON.parse(JSON.stringify(config)), null !== t && (config = JSON.parse(t)), window.config = config) {
        if ("vertical" == config.nav) {
            let t = config.sidenav.size;
            window.innerWidth <= 767 ? t = "full" : 767 <= window.innerWidth && window.innerWidth <= 1140 && "full" !== self.config.sidenav.size && "fullscreen" !== self.config.sidenav.size && (t = "condensed"), e.setAttribute("data-sidenav-size", t), config.sidenav.user && "true" === config.sidenav.user.toString() ? e.setAttribute("data-sidenav-user", !0) : e.removeAttribute("data-sidenav-user")
        }
        e.setAttribute("data-bs-theme", config.theme), e.setAttribute("data-menu-color", config.menu.color), e.setAttribute("data-topbar-color", config.topbar.color), e.setAttribute("data-layout-mode", config.layout.mode)
    }
}();

function swalSuccess(msg) {
    return Swal.fire({
        position: "top-end",
        icon: "success",
        text: msg,
        showConfirmButton: false,
        timer: 2000
    });
}

function swalError(msg, btn = 'OK') {
    return Swal.fire({
        icon: "error",
        text: msg,
        confirmButtonText: btn,
        buttonsStyling: false,
        customClass: { confirmButton: "btn btn-danger" }
    });
}
function swalDelete(title, msg) {
    return Swal.fire({
            title: title,
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        });
}
function swalRestore(title, msg) {
    return Swal.fire({
            title: title,
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, restore',
            cancelButtonText: 'Batal'
        });
}

