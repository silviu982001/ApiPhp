<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.js"></script>
</head>
<body>
<input type="text" id="nume"/>
<input type="text" id="prenume"/>
<input type="text" id="varsta"/>
<input type="text" id="oras"/>
<button type="button" onclick="AdaugaPersoana()">Add</button>
<button type="button" onclick="UpdatePersoana()">Update</button>
<button type="button" onclick="RemovePersoana()">Remove</button>
<table border="1" id="myTable">
    <tbody>
    <tr>
        <td>
            Nume
        </td>
        <td>
            Prenume
        </td>
        <td>
            Varsta
        </td>
        <td>
            Oras
        </td>
        <td>
            Edit
        </td>
        <td>
            Remove
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>

<script type="text/javascript">
    var patternTable = '<tr><td>@nume</td><td>@prenume</td><td>@varsta</td><td>@oras</td><td><a href="#" onclick="EditPersoana(@idEdit)">Edit</a></td><td><a href="#" onclick="RemovePersoana(@idRemove)">Remove</a> </td></tr>';
    
    $(document).ready(
        function () {
            $.ajax({
                type: "GET",
                url: "http://localhost/Bit/API.php/GetPersons",
                success: function(response)
                {
                    $('#nume').val('');
                    $('#prenume').val('');
                    $('#varsta').val('');
                    $('#oras').val('');
                    if (response.success === true){
                        $('#myTable tr:not(:first)').remove();
                        if (response.data !== null){
                            $(response.data).each(function (index, value) {
                                var row = patternTable.replace("@nume", value.nume).replace("@prenume", value.prenume).replace("@varsta", value.varsta)
                                    .replace("@oras", value.oras).replace("@idEdit", value.id).replace("@idRemove", value.id);
                                $("#myTable tr:last").after(row);
                            })
                        }
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    )

    function EditPersoana(id) {
        window.location.replace("http://localhost/Bit/EditPersoana.php?id=" + id);
    }
    
    function AdaugaPersoana() {
        $.ajax({
            type: "POST",
            url: "http://localhost/Bit/API.php/AddPerson",
            data: { nume: $('#nume').val(), prenume: $('#prenume').val(), varsta: $('#varsta').val(), oras: $('#oras').val() },
            success: function(response)
            {
                $('#nume').val('');
                $('#prenume').val('');
                $('#varsta').val('');
                $('#oras').val('');
                if (response.success === true){
                    $('#myTable tr:not(:first)').remove();
                    if (response.data !== null){
                        $(response.data).each(function (index, value) {
                            var row = patternTable.replace("@nume", value.nume).replace("@prenume", value.prenume).replace("@varsta", value.varsta)
                                .replace("@oras", value.oras).replace("@idEdit", value.id).replace("@idRemove", value.id);
                            $("#myTable tr:last").after(row);
                        })
                    }
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    function UpdatePersoana() {
        $.ajax({
            type: "PUT",
            url: "http://localhost/Bit/API.php/UpdatePerson",
            data: { id: 1, nume: $('#nume').val(), prenume: $('#prenume').val(), varsta: $('#varsta').val(), oras: $('#oras').val() },
            success: function(response)
            {
                $('#nume').val('');
                $('#prenume').val('');
                $('#varsta').val('');
                $('#oras').val('');
                if (response.success === true){
                    $('#myTable tr:not(:first)').remove();
                    if (response.data !== null){
                        $(response.data).each(function (index, value) {
                            var row = patternTable.replace("@nume", value.nume).replace("@prenume", value.prenume).replace("@varsta", value.varsta)
                                .replace("@oras", value.oras).replace("@idEdit", value.id).replace("@idRemove", value.id);
                            $("#myTable tr:last").after(row);
                        })
                    }
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    function RemovePersoana(idPers) {
        $.ajax({
            type: "delete",
            url: "http://localhost/Bit/API.php/RemovePerson",
            data: { id: idPers },
            success: function(response)
            {

                $('#nume').val('');
                $('#prenume').val('');
                $('#varsta').val('');
                $('#oras').val('');
                if (response.success === true){
                    $('#myTable tr:not(:first)').remove();
                    if (response.data !== null){
                        $(response.data).each(function(index, value){
                           var row = patternTable.replace("@nume", value.nume).replace("@prenume", value.prenume).replace("@varsta", value.varsta)
                               .replace("@oras", value.oras).replace("@idEdit", value.id).replace("@idRemove", value.id);
                           $("#myTable tr:last").after(row);
                        });
                    }
        }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
</script> 