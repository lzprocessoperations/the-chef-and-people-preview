<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}


$recipient =
    "info@thechefandpeople.co.uk";


$maxFileSize =
    5 * 1024 * 1024;


$allowedExtensions = [
    "pdf",
    "doc",
    "docx"
];


$name =
    trim(
        $_POST["name"] ?? ""
    );


$email =
    trim(
        $_POST["email"] ?? ""
    );


$phone =
    trim(
        $_POST["phone"] ?? ""
    );


$enquiryType =
    trim(
        $_POST["enquiry_type"] ?? ""
    );


$message =
    trim(
        $_POST["message"] ?? ""
    );


$privacyAccepted =
    isset($_POST["privacy"]);


if (
    $name === "" ||
    $email === "" ||
    $enquiryType === "" ||
    $message === "" ||
    !$privacyAccepted
) {
    redirectWithError();
}


if (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {
    redirectWithError();
}


if (
    preg_match("/[\r\n]/", $name) ||
    preg_match("/[\r\n]/", $email)
) {
    redirectWithError();
}


$subject =
    "Website enquiry - " .
    $enquiryType .
    " - " .
    $name;


$body = "";


$body .=
    "New enquiry from The Chef & People website\n\n";


$body .=
    "Name:\n";

$body .=
    $name .
    "\n\n";


$body .=
    "Email:\n";

$body .=
    $email .
    "\n\n";


$body .=
    "Phone:\n";

$body .=
    (
        $phone !== ""
        ? $phone
        : "Not provided"
    ) .
    "\n\n";


$body .=
    "Enquiry type:\n";

$body .=
    $enquiryType .
    "\n\n";


$body .=
    "Message:\n";

$body .=
    $message .
    "\n\n";


$body .=
    "Privacy consent:\n";

$body .=
    "Accepted\n";


$headers = [];


$headers[] =
    "From: The Chef & People Website <" .
    $recipient .
    ">";


$headers[] =
    "Reply-To: " .
    $email;


$headers[] =
    "MIME-Version: 1.0";


$hasAttachment =
    isset($_FILES["cv"]) &&
    $_FILES["cv"]["error"] !==
    UPLOAD_ERR_NO_FILE;


if ($hasAttachment) {

    if (
        $_FILES["cv"]["error"] !==
        UPLOAD_ERR_OK
    ) {
        redirectWithError();
    }


    if (
        $_FILES["cv"]["size"] >
        $maxFileSize
    ) {
        redirectWithError();
    }


    $originalFileName =
        basename(
            $_FILES["cv"]["name"]
        );


    $extension =
        strtolower(
            pathinfo(
                $originalFileName,
                PATHINFO_EXTENSION
            )
        );


    if (
        !in_array(
            $extension,
            $allowedExtensions,
            true
        )
    ) {
        redirectWithError();
    }


    $temporaryFile =
        $_FILES["cv"]["tmp_name"];


    if (
        !is_uploaded_file(
            $temporaryFile
        )
    ) {
        redirectWithError();
    }


    $fileContent =
        file_get_contents(
            $temporaryFile
        );


    if (
        $fileContent === false
    ) {
        redirectWithError();
    }


    $boundary =
        md5(
            uniqid(
                (string) mt_rand(),
                true
            )
        );


    $headers[] =
        'Content-Type: multipart/mixed; boundary="' .
        $boundary .
        '"';


    $emailBody = "";


    $emailBody .=
        "--" .
        $boundary .
        "\r\n";


    $emailBody .=
        "Content-Type: text/plain; charset=UTF-8\r\n";


    $emailBody .=
        "Content-Transfer-Encoding: 8bit\r\n\r\n";


    $emailBody .=
        $body .
        "\r\n";


    $encodedFile =
        chunk_split(
            base64_encode(
                $fileContent
            )
        );


    $safeFileName =
        preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $originalFileName
        );


    $emailBody .=
        "--" .
        $boundary .
        "\r\n";


    $emailBody .=
        'Content-Type: application/octet-stream; name="' .
        $safeFileName .
        '"' .
        "\r\n";


    $emailBody .=
        "Content-Transfer-Encoding: base64\r\n";


    $emailBody .=
        'Content-Disposition: attachment; filename="' .
        $safeFileName .
        '"' .
        "\r\n\r\n";


    $emailBody .=
        $encodedFile .
        "\r\n";


    $emailBody .=
        "--" .
        $boundary .
        "--";

} else {

    $headers[] =
        "Content-Type: text/plain; charset=UTF-8";


    $emailBody =
        $body;
}


$mailSent =
    mail(
        $recipient,
        $subject,
        $emailBody,
        implode(
            "\r\n",
            $headers
        )
    );


if ($mailSent) {

    header(
        "Location: contact.html?sent=1#contact-form"
    );

    exit;
}


redirectWithError();


function redirectWithError(): void
{
    header(
        "Location: contact.html?error=1#contact-form"
    );

    exit;
}

?>