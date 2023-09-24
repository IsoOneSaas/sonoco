@if ($errors->any())
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $errors->first() }}');
    </script>    
@endif

@if ($message = Session::get('error'))
    <script>
        setSuccessNotification('error', 'Oops!', '{{ $message }}');
    </script> 
@endif
