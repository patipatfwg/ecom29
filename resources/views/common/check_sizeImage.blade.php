<script>
	var form_name = "{{$form_name}}";
	var post_max_size = "{{parse_size(ini_get('post_max_size'))}}";
	var upload_max_filesize = "{{parse_size(ini_get('upload_max_filesize'))}}";


	function toMB(size){
		return size = ((size/ 1024) /1024);
	}

	post_max_size = toMB(post_max_size);
	upload_max_filesize = toMB(upload_max_filesize);


console.log(post_max_size);
console.log(upload_max_filesize);

	$('input[type="file"]').on('change', function(e) {
		if(this.files[0] != null){
			var filesize_ = this.files[0].size;
			  var filesize_mb_ = (( filesize_ / 1024 ) / 1024);
			   if(filesize_mb_ > upload_max_filesize){
				        swal({
						  title: 'Upload file size is exceeded',
						  text: "Image allow max size 2 mb.",
						  type: 'warning',
						  showCancelButton: false,
						  confirmButtonColor: '#3085d6',
						  confirmButtonText: 'ok'
						})
						$(this).val('');
			   }
		}
		check_post_size();
	});

	function check_post_size(){
				var total = 0;
				var total_mb = 0;
				$('input[type="file"]').each(function(i) {

				    // Get an array of the files for this input
				    var files = $(this).get(0).files;

				    // Loop through files
				    for (var j=0; file = files[j]; j++) {

				        // File size, in bytes
				       total += file.size;
				    }
				});
				// console.log('total '+ ( ( total / 1024 ) / 1024 ) +'MB.');
				total_mb = ( ( total / 1024 ) / 1024 );
				if(total_mb > post_max_size){
						swal({
						  title: 'Sorry form size is exceed',
						  text: "form allow all image max size 8 mb.",
						  type: 'warning',
						  showCancelButton: false,
						  confirmButtonColor: '#3085d6',
						  confirmButtonText: 'ok'
						})
				}
	}


</script>