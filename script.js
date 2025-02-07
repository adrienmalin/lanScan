function toggleTheme() {
    if (document.body.classList.contains('inverted')) {
        $(".inverted").addClass("light").removeClass("inverted")
        $("#toggleThemeButton i").addClass("moon").removeClass("sun")
        localStorage.setItem("laScanTheme", "light")
    } else {
        $(".light").addClass("inverted").removeClass("light")
        $("#toggleThemeButton i").addClass("sun").removeClass("moon")
        localStorage.setItem("laScanTheme", "dark")
    }
}

if (localStorage.getItem("laScanTheme") === "light") {
    toggleTheme()
}