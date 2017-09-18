
function replyTo(postId)
{	
	$("#postId").val(postId);
	$("#replyTo").show();
	$("#newPost").hide();
	$("#comment").focus();
}

function newThread(categoryId)
{
	$(".addThread").val(postId);
	$(".addThread").show();
	$("#addThread").show();
	$("#newThreadTitle").focus();
}

function addCategory()
{
	var newCategory = $("#newCategory").val();
	
	$.ajax(
	{
	    // la URL para la petición
	    url : '/controllers/forumControllers/addCategoryController.php',
	 
	    // la información a enviar
	    // (también es posible utilizar una cadena de datos)
	    data : { newCategory : newCategory },
	 
	    // especifica si será una petición POST o GET
	    type : 'POST',
	 
	    // el tipo de información que se espera de respuesta
	    //dataType : 'json',
	 
	    // código a ejecutar si la petición es satisfactoria;
	    // la respuesta es pasada como argumento a la función
	    success : function(json) {
	   		alert('Categoria afegida correctament');
            window.location.href='/dashboard/dashboard.php';
            
	    },
	 
	    // código a ejecutar si la petición falla;
	    // son pasados como argumentos a la función
	    // el objeto de la petición en crudo y código de estatus de la petición
	    error : function(xhr, status) {
	    	console.log(xhr);
	        alert('Error al guardar la nova categoria');
	    }
	});
}