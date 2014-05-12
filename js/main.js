// The root URL for the RESTful services
var rootURL = "http://localhost/ws/api/posts";

var currentPost;

// Retrieve post list when application starts 
findAll();

// Nothing to delete in initial application state
$('#btnDelete').hide();
$('.feedListleft').hide();


// Register listeners
$('#btnSearch').click(function() {
	search($('#searchKey').val());
	return false;
});
//Autocomplete stuff
$('#searchKey').keyup(function() {
	search($('#searchKey').val());
	return false;
});
$("#searchKey").keyup(function(){

  if($("#searchKey").val() ==''){
	$("#searchKey").css("background-color","white");
  }
  else{
   $("#searchKey").css("background-color","pink");
  }
});

// Trigger search when pressing 'Return' on search key input field
$('#searchKey').keypress(function(e){
	if(e.which == 13) {
		search($('#searchKey').val());
		e.preventDefault();
		return false;
    }
});

$('#btnAdd').click(function() {
	newPost();
	return false;
});

$('#btnSave').click(function() {
	if ($('#postId').val() == '')
		addPost();
	else
		updatePost();
	return false;
});

$('#btnDelete').click(function() {
	deletePost();
	return false;
});

$('#postList a').live('click', function() {
	findById($(this).data('identity'));
});

// Replace broken images with generic post image
$("img").error(function(){
  $(this).attr("src", "pics/generic.jpg");

});
$('#showFeeds').click(function() {
	alert('Button Clicked');
	$('.leftArea').remove();
	$('.feedListleft').show();
	return false;
});



function search(searchKey) {
	if (searchKey == '') 
		findAll();
	else
		findByName(searchKey);
}

function newPost() {
	$('#btnDelete').hide();
	currentPost = {};
	renderDetails(currentPost); // Display empty form
}

function findAll() {
	console.log('findAll');
	$.ajax({
		type: 'GET',
		url: rootURL,
		dataType: "json", // data type of response
		success: renderList
	});
}


function findByName(searchKey) {
	console.log('findByName: ' + searchKey);
	$.ajax({
		type: 'GET',
		url: rootURL + '/search/' + searchKey,
		dataType: "json",
		success: renderList 
	});
}



function findById(id) {
	console.log('findById: ' + id);
	$.ajax({
		type: 'GET',
		url: rootURL + '/' + id,
		dataType: "json",
		success: function(data){
			$('#btnDelete').show();
			console.log('findById success: ' + data.name);
			currentPost = data;
			renderDetails(currentPost);
		}
	});
}



function addPost() {
	console.log('addPost');
	$.ajax({
		type: 'POST',
		contentType: 'application/json',
		url: rootURL,
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Post created successfully');
			$('#btnDelete').show();
			$('#postId').val(data.id);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('addPost error: ' + textStatus);
		}
	});
}

function updatePost() {
	console.log('updatePost');
	$.ajax({
		type: 'PUT',
		contentType: 'application/json',
		url: rootURL + '/' + $('#postId').val(),
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Post updated successfully');
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('updatePost error: ' + textStatus);
		}
	});
}

function deletePost() {
	console.log('deletePost');
	$.ajax({
		type: 'DELETE',
		url: rootURL + '/' + $('#postId').val(),
		success: function(data, textStatus, jqXHR){
			alert('Post deleted successfully');
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('deletePost error');
		}
	});
}

function renderList(data) {
	// JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
	var list = data == null ? [] : (data.post instanceof Array ? data.post : [data.post]);

	$('#postList li').remove();
	$.each(list, function(index, post) {
		$('#postList').append('<li><a href="#" data-identity="' + post.post_id + '">'+post.post_title+'</a></li>');
	});
}

function renderDetails(post) {
	$('#postId').val(post.post_id);
	$('#name').val(post.post_title);
	$('#URL').val(post.post_link);
	//$('#other').val(post.post_description);
	// $('#pic').attr('src', 'pics/' + post.picture);
	$('#description').val(post.post_description);
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON() {
	return JSON.stringify({
		"id": $('#postId').val(), 
		"name": $('#name').val(), 
		"URL": $('#URL').val(),
		"other": $('#other').val(),
		// "picture": currentPost.picture,
		"description": $('#description').val()
		});
}

