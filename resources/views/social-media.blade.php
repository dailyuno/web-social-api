<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.23.0/axios.min.js"></script>
    <script src="{{ asset('js/SimpleDate.js') }}"></script>
    <script src="{{ asset('js/YoutubeApi.js') }}"></script>
    <script src="{{ asset('js/App.js') }}"></script>
    <script>
        window.onload = function() {
            new App();
        }
    </script>

</body>
</html>