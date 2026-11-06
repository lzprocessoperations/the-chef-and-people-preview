document.addEventListener("DOMContentLoaded", () => {

  const menuToggle =
    document.querySelector(".menu-toggle");

  const mobileNav =
    document.querySelector(".mobile-nav");


  if (menuToggle && mobileNav) {

    menuToggle.addEventListener("click", () => {

      const isOpen =
        mobileNav.classList.toggle("active");

      menuToggle.setAttribute(
        "aria-expanded",
        isOpen ? "true" : "false"
      );

    });


    mobileNav.querySelectorAll("a").forEach((link) => {

      link.addEventListener("click", () => {

        mobileNav.classList.remove("active");

        menuToggle.setAttribute(
          "aria-expanded",
          "false"
        );

      });

    });


    document.addEventListener("keydown", (event) => {

      if (event.key === "Escape") {

        mobileNav.classList.remove("active");

        menuToggle.setAttribute(
          "aria-expanded",
          "false"
        );

      }

    });


    window.addEventListener("resize", () => {

      if (window.innerWidth > 760) {

        mobileNav.classList.remove("active");

        menuToggle.setAttribute(
          "aria-expanded",
          "false"
        );

      }

    });

  }


  const params =
    new URLSearchParams(
      window.location.search
    );


  const role =
    params.get("role");

  const sent =
    params.get("sent");

  const error =
    params.get("error");


  const enquiryType =
    document.querySelector("#enquiry_type");

  const message =
    document.querySelector("#message");


  if (role && enquiryType && message) {

    enquiryType.value = "Candidate";


    if (
      role.toLowerCase() ===
      "general application"
    ) {

      message.value =
        "Hi, I would like to send my CV and register my interest in future hospitality opportunities.";

    } else {

      message.value =
        `Hi, I would like to apply for the ${role} position.`;

    }

  }


  const formSuccess =
    document.querySelector("#formSuccess");

  const formError =
    document.querySelector("#formError");


  if (sent === "1" && formSuccess) {

    formSuccess.style.display = "block";

    formSuccess.scrollIntoView({
      behavior: "smooth",
      block: "center"
    });

  }


  if (error === "1" && formError) {

    formError.style.display = "block";

    formError.scrollIntoView({
      behavior: "smooth",
      block: "center"
    });

  }

});