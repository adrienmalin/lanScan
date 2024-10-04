<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>lanScan</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
        <style>
            .navbar-brand svg {
                margin: -.1em -.6em 0 -.6em;
                fill: currentColor;
            }
            .main.container {
                margin-top: 5em;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand text-white" href=".">
                        lan<? include "logo.svg" ?>can
                    </a>
                </div>
            </div>
        </nav>
        <main class="container my-4">
            <div class="row">
                <div class="col-6 mx-auto">
                    <form id="scanForm" class="card needs-validation" action="scan.php" method="get" novalidate>
                        <div class="card-body">
                            <h5 class="card-title">Scan de découverte</h3>
                                <div class="mb-3">
                                    <label for="targetInput" class="form-label">Cibles</label>
                                    <input type="text" class="form-control" id="targetInput" name="targets" placeholder="scanme.nmap.org microsoft.com/24 192.168.0.1 10.0.0-255.1-254" pattern="[a-zA-Z0-9/. -]+" required
                                        title="Les cibles peuvent être spécifiées par des noms d'hôtes, des adresses IP, des adresses de réseaux, etc."
                                        value="192.168.0.0/24">
                                </div>
                                <div class="mb-3">
                                    <label for="serviceInput" class="form-label">Services</label>
                                    <input type="text" class="form-control" id="serviceInput" name="p" placeholder="ftp,ssh,http,443,T:23-25,139,8080,U:53" pattern="[a-zA-Z0-9,:-]+"
                                        title="Noms de protocole ou numéros de port TCP ou UDP."
                                        value="ftp,ssh,telnet,http,https,137-139,445,8006,8007,9292" data-role="tagsinput">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">Scan</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
        <script>
            scanForm.onsubmit = (event) => {
                if (!scanForm.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                scanForm.classList.add('was-validated')
            }
        </script>
    </body>
</html>