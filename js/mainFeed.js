// The root URL for the RESTful services
var rootURL = "http://localhost/ws/api/posts";
alert(rootURL);

$('#btnAdd').click(function() {
  alert('clicked');
		// addFeed();
		// $('.leftArea').hide();
	// return false;
});

function addFeed() {
	console.log('addFeed');
	$.ajax({
		type: 'POST',
		contentType: 'application/json',
		url: rootURL + '/' + $('#idUser').val(),,
		dataType: "json",
		data: formToJSON(),
		success: function(data, textStatus, jqXHR){
			alert('Feed created successfully');
			$('#idUser').val(data.id);
		},
		error: function(jqXHR, textStatus, errorThrown){
			alert('addFeed error: ' + textStatus);
		}
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON() {
	return JSON.stringify({
		"id": $('#idUser').val(), 
		"URL": $('#URL').val(),
		});
}

