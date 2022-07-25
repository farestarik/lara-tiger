@if (session()->has('success'))
<script>
    Swal.fire({
        title: '{{__("site.done")}} !',
        text: '{{session()->get("success")}}',
        icon: 'success',
        confirmButtonText: '{{__("site.ok")}}'
     });
</script>    
{{session()->forget('success')}}
@endif