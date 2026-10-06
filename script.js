const menuButton = document.getElementById("menuButton");
const navigation = document.getElementById("navigation");


/* =========================
   MOBILE NAVIGATION
========================= */

if (menuButton && navigation) {

    menuButton.addEventListener("click", function () {

        navigation.classList.toggle("open");

        const menuIsOpen =
            navigation.classList.contains("open");

        menuButton.setAttribute(
            "aria-expanded",
            menuIsOpen
        );

    });


    const navigationLinks =
        navigation.querySelectorAll("a");


    navigationLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            navigation.classList.remove("open");

            menuButton.setAttribute(
                "aria-expanded",
                "false"
            );

        });

    });

}


/* =========================
   FLOATING CONTACT
========================= */

const floatingContact =
    document.getElementById("floatingContact");

const floatingClose =
    document.getElementById("floatingClose");


if (floatingContact && floatingClose) {

    floatingClose.addEventListener(
        "click",
        function () {

            floatingContact.style.display = "none";

        }
    );

}


/* =========================
   FORM SUCCESS MESSAGE
========================= */

const formSuccess =
    document.getElementById("formSuccess");


const urlParameters =
    new URLSearchParams(window.location.search);


if (
    formSuccess &&
    urlParameters.get("sent") === "1"
) {

    formSuccess.hidden = false;


    window.setTimeout(function () {

        formSuccess.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });

    }, 200);


    /*
        Remove ?sent=1 from the address bar
        only when the site is running through
        http or https.

        This avoids a browser security error
        when testing locally with file://
    */

    if (window.location.protocol !== "file:") {

        const cleanUrl =
            window.location.pathname +
            window.location.hash;


        window.history.replaceState(
            {},
            document.title,
            cleanUrl
        );

    }

}


/* =========================
   WINDOW RESIZE
========================= */

window.addEventListener("resize", function () {

    if (
        window.innerWidth > 760 &&
        navigation &&
        menuButton
    ) {

        navigation.classList.remove("open");

        menuButton.setAttribute(
            "aria-expanded",
            "false"
        );

    }

});