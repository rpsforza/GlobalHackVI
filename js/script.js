var messages;

function refreshM() {
	window.setInterval(refreshMessages, 3000);
}

function refreshMessages() {
	$.post( "../updateMessages.php", {Group: GroupID}).done(function(data) {
	    messages = JSON.parse(data);
	  });
	changeMessages(messages);
}

function changeMessages(msgs) {
	$("#messages").innerHTML = msgs;
	console.log(msgs);
}

function sendMessage(messaged) {
	$.post( "sendMessage.php", { GroupID: GroupID, Message: messaged, UserID: userID }).done(function( data ) {

	});
}