<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image to Text Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.1/css/buttons.dataTables.min.css">
    <style>
        body {
            box-sizing: border-box;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="px-2 py-2">
        <form id="frm-convert" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="formFileMultiple" class="form-label">Upload Images</label>
                <input class="form-control" type="file" id="formFileMultiple" name="images[]" multiple>
            </div>
            <button class="btn-spinner btn btn-primary btn-upload" type="button">
                <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                <span role="status">Upload and Convert</span>
            </button>
        </form>
        @if (!empty($data))
            <div class="mt-4">
                <table class="table table-striped" id="data-customers">
                    <thead class="table-dark">
                        <tr>
                            <th align="left" width="1%">No</th>
                            <th align="left">Customer</th>
                            <th align="left">Phone</th>
                            <th align="left">Link</th>
                            <th align="left">Sale</th>
                            <th align="left">Time</th>
                            <th align="left">Content</th>
                            <th align="left">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value['customer'] }}</td>
                                <td>{{ $value['phone'] }}</td>
                                <td>{{ $value['link'] }}</td>
                                <td>{{ $value['sale'] }}</td>
                                <td>{{ $value['time'] }}</td>
                                <td>{{ $value['content'] }}</td>
                                <td>{{ $value['file'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
          $('#data-customers').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'csvHtml5',
              'excelHtml5',
              'pdfHtml5'
            ]
          });
        }).on('click', '#frm-convert .btn-upload', function(e) {
            const images = $('#formFileMultiple').val()
            if(!images.length) return alert('Vui lòng chọn file !')
            $('#frm-convert').submit()  
        })
    </script>
</body>
</html>
