
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yah Café - Straight outta heaven</title>



<!-- Library CSS -->
<link href="assets/css/lecker_library.css" rel="stylesheet">

<!-- Icons CSS -->
<link href="assets/fonts/themify-icons.css" rel="stylesheet">
<link href="assets/fonts/selima/stylesheet.css" rel="stylesheet">

<!-- Theme CSS -->
<link href="assets/css/lecker_style.css" rel="stylesheet">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Bree+Serif|Lato" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134562265-3"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-134562265-3');
</script>

</head>

@yield('styles')

@section('scripts')
<!-- JQuery -->
<script src="assets/js/jquery-1.12.4.min.js"></script> 
<!-- Library JS -->
<script src="assets/js/lecker_library.js"></script> 

<!-- Theme JS -->
<script src="assets/js/lecker_script.js"></script>
   
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
       <script type="text/javascript">
           @if(isset($status) && $status == 'success')
           swal("Obrigado!!", "Seu email foi enviado com sucesso, logo entraremos em contato", "success");

            @elseif(isset($status) && $status == 'danger')
           swal("Ops!", "Alguma informação está equivocada, verifique as informações e tente novamente.", "error");

            @endif

  </script>
  <script type="text/javascript">
    
    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
      }

      function validate() {
        var $result = $("#result");
        var email = $("#email").val();
        $result.text("");

        if (validateEmail(email)) {
        return view('site/home',compact('status'));
        } else {
          $result.text("Esse email não é valido :(");
          $result.css("color", "red", "font-size","15px");
        }
        return false;
      }
      $("#validate").on("click", validate);
  </script>
@stop