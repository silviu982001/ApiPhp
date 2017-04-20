<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.js"></script>
    <title>Edit Persoana</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>
                Nume:
            </td>
            <td>
                <input type="text" id="nume"/>
            </td>
        </tr>
        <tr>
            <td>
                Prenume:
            </td>
            <td>
                <input type="text" id="prenume"/>
            </td>
        </tr>
        <tr>
            <td>
                Varsta:
            </td>
            <td>
                <input type="text" id="varsta"/>
            </td>
        </tr>
        <tr>
            <td>
                Oras:
            </td>
            <td>
                <input type="text" id="oras"/>
            </td>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <button type="button" onclick="UpdatePersoana()">Update</button>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        $(document).ready(
            function () {
                $.ajax({
                   type: 'get',
                    cache:false,
                    url: "/Bit/Api.php/GetPerson",
                    data: {id: <?php echo $_GET['id']; ?> },
                    success: function (response) {
                        if (response.success === true){
                            $('#nume').val(response.data.nume);
                            $('#prenume').val(response.data.prenume);
                            $('#varsta').val(response.data.varsta);
                            $('#oras').val(response.data.oras);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        )
        function UpdatePersoana() {
            var idVal = <?php echo $_GET['id']; ?>;
            $.ajax({
                type: "PUT",
                cache: false,
                url: "/Bit/API.php/UpdatePerson",
                data: {id: idVal, nume: $('#nume').val(), prenume: $('#prenume').val(), varsta: $('#varsta').val(), oras: $('#oras').val() },
                success: function (response) {
                    if (response.success === true) {
                        window.location.replace("index.php");
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            })
        }
    </script>
</body>
</html>

