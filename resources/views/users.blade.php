@extends('layouts.app')
@section('content')
<div class="container">
	<a href="{{ url('create') }}" class="btn btn-sm btn-primary">CREATE</a>
	<button type="button" onclick="add()" class="btn btn-sm btn-info">CREATE-AJAX</button>
	<div id="list"></div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	var APP_URL= {!! json_encode(url('/')) !!};
	var save_method;
	var table;
	$(document).ready(function(){
		var list = function()
	    {
	      $.ajax({
	        type:"GET",
	        url:APP_URL+'/user',
	        success:function(data){
	          $('#list').empty().html(data);
	        }
	      });
	    }
	    list();
	});
	function add()
	{
		save_method = 'add';
	    $('#form')[0].reset(); // reset form on modals
	    $('#form').attr('action',APP_URL+'/new');
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('#modal').modal('show'); // show bootstrap modal
	    $('.modal-title').text('FORM DATA USER'); // Set Title to Bootstrap modal title
	    $('#btn-save').text('Save');
	    $('.error').empty();
	}

	function edit(id)
	{
	    save_method = 'update';
	    $('#form')[0].reset(); // reset form on modals
	    $('.form-group').removeClass('has-error'); // clear error class
	    $('.error').empty(); // clear error string

	    //Ajax Load data from ajax
	    $.ajax({
	        url : APP_URL+'/edit/' + id,
	        type: "GET",
	        dataType: "JSON",
	        success: function(data)
	        {
	        	$('#form').attr('action',APP_URL+'/edit/'+id);
	            $('[name="name"]').val(data.name);
	            $('[name="email"]').val(data.email);
	            $('#modal').modal('show'); // show bootstrap modal when complete loaded
	            $('.modal-title').text('FORM DATA USER'); // Set title to Bootstrap modal title
	            $('#btn-save').text('Save Changes');
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error get data from ajax');
	        }
	    });
	}

	function save()
	{
	    $('#btn-save').html('Menyimpan...'); //change button text
	    $('#btn-save').attr('disabled',true); //set button disable 
	    var url;
	    var listData = function()
	    {
	    	$.ajax({
		        type:"GET",
		        url:APP_URL+'/user',
		        success:function(data){
		          $('#list').empty().html(data);
		        }
		      });
	    }

	    if(save_method == 'add') {
	        url = $('#form').attr('action');
	    } else {
	        url = $('#form').attr('action');
	    }

	    // ajax adding data to database
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: $('#form').serialize(),
	        dataType: "JSON",
	        success: function(data)
	        {

	            if(data.status == 'true') //if success close modal and reload ajax table
	            {
	                $('#modal').modal('hide');
	                 listData();
	            }

	            $('#btn-save').text('Save'); //change button text
	            $('#btn-save').attr('disabled',false); //set button enable 
	            $('.form-group').addClass('has-error');


	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error adding / update data');
	            $('#btn-save').text('save'); //change button text
	            $('#btn-save').attr('disabled',false); //set button enable 

	        }
	    });
	}
	function del(id)
	{
		var listData = function()
	    {
	    	$.ajax({
		        type:"GET",
		        url:APP_URL+'/user',
		        success:function(data){
		          $('#list').empty().html(data);
		        }
		      });
	    }
	    if(confirm('Are you sure delete this data?'))
	    {
	        // ajax delete data to database
	        $.ajax({
	        	type: "POST",
                url :APP_URL +'/delete/'+id,
                    beforeSend:function(xhr){
                      var token = $('meta[name="csrf-token"]').attr('content');

                      if(token){
                        return xhr.setRequestHeader('X-CSRF-TOKEN',token);
                      }
                    },
                success : function(data){
                  if(data.status =='true'){
                      $('#modal').modal('hide');
		              listData();
                  }
                }
              });

	    }
	}
</script>
@endsection
@section('modal')
<div id="modal" class="modal fade">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <form id="form" method="POST" action="#">
			{{ csrf_field() }}
			<div class="form-group">
				<label>Username :</label>
				<input type="text" name="name" placeholder="Username" class="form-control">
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label>Email :</label>
				<input type="email" name="email" placeholder="E-mail" class="form-control">
				<span class="error"></span>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button id="btn-save" type="button" onclick="save()" class="btn btn-sm btn-primary"></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection