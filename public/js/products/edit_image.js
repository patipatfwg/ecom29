var tmpImages = {};
var myDropzone;
var totalFiles = 0;

Dropzone.autoDiscover = false;
$('[delete-id]').on('click', function (e) {
	e.preventDefault();
	var id = $(this).attr('delete-id');
	console.log(id);
    swal({
        title: 'Are you sure?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    },
    function(isConfirm){
        if (isConfirm) {
            $.ajax({
		        type: 'DELETE',
		        url: appurl + "/deleteImage",
		        data:{id,id},
		        dataType: 'json',
		        success:function(data){
		            console.log(data);
		            if (data.status.code == 200) {
		                swal('Deleted!', data.messages, 'success');

		                $('[image-id="'+id+'"]').remove();

		                
		                editNoImages();
		            } else {
		                swal('Deleted!', data.messages, 'warning');
		            }
		        },
		        error: function(){
		            swal('Deleted!', 'Error connection', 'error');
		        }
		    });
        }
    });
});
$("#dropzone").dropzone({
    url: appurl + "/"+ product_id +"/uploadimage",
    autoProcessQueue: true,
    addRemoveLinks: true,
    maxFilesize: 20, //limit file size
    uploadMultiple: false,
    maxFiles: 5, //max file
    acceptedFiles: '.png,.jpg,.gif,.bmp,.jpeg',
    accept: function(file, done) {
        done();
    },
    init: function() {
        myDropzone   = this;
        //add delete in image
        myDropzone.options.addRemoveLinks = true;
        myDropzone.options.dictRemoveFile = "Delete";

        myDropzone.on("maxfilesexceeded", function(file){
            swal('File!', 'No more files please!', 'warning');
        });

        myDropzone.on("removedfile", function (file) {
        	console.log('remove');
            removeImageToTmp(file.name);
        });
    },
    success: function(file, response, action) {
        if (response.success) {
            //SUCCESS add short url to images
            // console.log(response);
            addImageToTmp(file, response.image);
            console.log(response);
        } else {
            //IF ERROR REMOVRE FILE
            myDropzone.removeFile(file);
        } 

    },
    error: function(file, response) {
        swal('File!', response, 'warning');
        myDropzone.removeFile(file);

    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function addImageToTmp(file, image)
{
    if (file.name in tmpImages) {
        //remove image
        myDropzone.removeFile(file);
    } else {
        tmpImages[file.name] = image;
        console.log(tmpImages);
    }
}

function removeImageToTmp(filename)
{
    delete tmpImages[filename];
}

function addTmpImageToInput()
{
    var images = "";
    for (filename in tmpImages) {
        if (images != "") {
            images = images + ",";
        }
        images = images + tmpImages[filename];
    }
    if (images != ""){
        //add to input
        console.log(images);
        $('#productimages').val(images);
    }
}
$('#sor-table').sortable({
    placeholder: 'ui-state-highlight',
    items: 'tbody tr',
    // handle: '.row_move',
    forcePlaceholderSize: true,
    update : function (event, ui) {
        var order = $('#sor-table').sortable('serialize', {
            key: 'items[]', 
            attribute: 'data-key'
        });
        $.ajax({
            url : appurl + "/moveImage",
            type: "POST",
            data: order,
            success: function(data){
            	if(data.status){
            		editNoImages();
            	}
            },
            error: function (jqXHR, textStatus, errorThrown){
                noty({layout:"top",type:"error",text:"เกิดข้อผิดพลาด!"})
            }
        });
    },
    helper: function(e, ui) {
        ui.children().each(function() { $(this).width($(this).width()); });
        return ui;
    },
});
function editNoImages(){
	$.each( $('.image-no'), function( key, value ) {
		$(this).text(key+1);
	});
}