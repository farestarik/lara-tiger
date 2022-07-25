@if (session()->has('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: '{{session()->get("error")}}',
        icon: 'danger',
        confirmButtonText: 'Ok'
     });
</script>    
{{session()->forget('error')}}
@endif