<!doctype html>
<html lang="{{ app()->getLocale() }}">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>RC4</title>
      <!-- Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
      <script src="jquery.hexdump.js"></script>
      <link rel="stylesheet" type="text/css" href="jquery.hexdump.css">
      <!-- Styles -->
      <style media="screen">
      .hex-content {
        width: 1000px;
      }
      </style>
   </head>
   <body>
      <div class="container">
         <div class="row">
           <div class="col-md-12">
             <h4 class="text-center">Hi</h4>
           </div>
           <div class="col-md-12">
             <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                   <a class="nav-link active" id="home-tab" data-toggle="tab" href="#encript-tab" role="tab"aria-selected="true">Encript</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" id="profile-tab" data-toggle="tab" href="#decript-tab" role="tab"aria-selected="false">Decript</a>
                </li>
             </ul>
           </div>
           <div class="col-md-12">
             <div class="tab-content" id="myTabContent" style="display: block;">
               <div class="tab-pane fade show active" id="encript-tab">
                  <div class="col-md-12">
                     <h4>RC4 Encript</h4>
                     <form action="" id="enc_form">
                       <select class="form-control" name="input-type-enc">
                         <option value="enc_text" selected="selected">Text</option>
                         <option value="enc_file">File</option>
                       </select>
                        <div class="form-group input-data" id="enc_text">
                           <label for="plaintext">Plaintext</label>
                           <input type="text" class="form-control" name="text-data">
                        </div>
                        <div class="form-group  input-data" id="enc_file">
                           <label for="plaintext">File</label>
                           <input type="file" class="form-control" name="file-data">
                        </div>
                        <div class="form-group">
                           <label for="key">Key</label>
                           <input type="text" class="form-control" name="key">
                        </div>
                        <div class="enc_file_download_link">

                        </div>

                        <button type="submit" class="btn btn-primary" name="enc-submit-btn">Submit</button>
                     </form>
                  </div>
                  <div class="hex-content" id="enc_content">

                  </div>
               </div>
               <div class="tab-pane fade show" id="decript-tab">
                  <div class="col-md-12">
                     <h4>RC4 Decript</h4>
                     <form action="" id="dec_form">
                       <select class="form-control" name="input-type-dec">
                         <option value="dec_text" selected="selected">Text</option>
                         <option value="dec_file">File</option>
                       </select>
                        <div class="form-group input-data" id="dec_text">
                           <label for="plaintext">Plaintext</label>
                           <input type="text" class="form-control" name="text-data">
                        </div>
                        <div class="form-group  input-data" id="dec_file">
                           <label for="plaintext">File</label>
                           <input type="file" class="form-control" name="file-data">
                        </div>
                        <div class="form-group">
                           <label for="key">Key</label>
                           <input type="text" class="form-control" name="key">
                        </div>
                        <div class="dec_file_download_link">

                        </div>

                        <button type="submit" class="btn btn-primary" name="dec-submit-btn">Submit</button>
                     </form>
                     <div class="hex-content" id="dec_content">

                     </div>
                  </div>
               </div>

            </div>
           </div>
         </div>
         <script type="text/javascript">
          function parseHexString(str) {
              var buffer = new Array();
              var i = 0;
              while (str.length >= 2) {
                buffer[i] = str.substring(0, 2);
                str = str.substring(2, str.length);
                i++;
              }
              return buffer;
          }
            $('.input-data').css('display', 'none');
            $('#' + $('select[name=input-type-enc]').val()).css('display', 'block');
            $('#' + $('select[name=input-type-dec]').val()).css('display', 'block');
            $("button[name=enc-submit-btn]").on('click', function(e) {
                e.preventDefault();
                var data = new FormData($('#enc_form')[0]);
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: 'encript',
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
                    data: data,
                    success: function(result) {
                      if (result.code == 0) {
                        $("#enc_content").empty();
                        $("#enc_content").hexDump(parseHexString(result.data));
                        $('.enc_file_download_link').empty();
                        $('.enc_file_download_link').append("<a href='/download?filename=" + result.filename + "'>Download Encripted file</a>")
                        $("#enc_content").append("Download file to see full");
                      } else {
                        $('.enc_file_download_link').empty();
                        $('.enc_file_download_link').append("<h3>" + result.error_message + "</h3>");
                      }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                })
            })
            $("button[name=dec-submit-btn]").on('click', function(e) {
                e.preventDefault();
                var data = new FormData($('#dec_form')[0]);
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: 'decript',
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
                    data: data,
                    success: function(result) {
                      console.dir(result.data);
                        $('#dec_content').empty();
                        $('#dec_content').hexDump(parseHexString(result.data));
                        $('.dec_file_download_link').empty();
                        $('.dec_file_download_link').append("<a href='/download?filename=" + result.filename + "'>Download Decripted file</a>")
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                })
            })
            $('select[name=input-type-enc]').on('change', function() {
              $('.input-data').css('display', 'none');
              $('#' + $('select[name=input-type-enc]').val()).css('display', 'block');
              $('#' + $('select[name=input-type-dec]').val()).css('display', 'block');
            })
            $('select[name=input-type-dec]').on('change', function() {
              $('.input-data').css('display', 'none');
              $('#' + $('select[name=input-type-enc]').val()).css('display', 'block');
              $('#' + $('select[name=input-type-dec]').val()).css('display', 'block');
            })
         </script>
      </div>
   </body>
</html>
