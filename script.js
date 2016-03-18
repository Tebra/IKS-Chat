$(document).ready(function () {
    
    $('#loginButton').click(function () {
        var username = $('#username').val();
        $.getJSON("ajax/login.php", {
            user: username,
            password: ''
        }, function (data) {
            console.log(data);
        });

        localStorage.setItem('activeUser', username);
        window.location.href = 'list.html';
    });

    if (window.location.href.indexOf("list") >= 0) {

        $.getJSON("ajax/users.php", {}, function (data) {
            $(function () {
                $.each(data, function (i, item) {
                    var $tr = $('<tr>').append(
                        $('<td>').text(item)
                    ).css("cursor", "pointer");
                    $('#userTable').find('tbody').append($tr);
                });
            });
        });
    }

    //open chat.html on click and store the active and 
    //target user. 
    $('#userTable').on('click', 'td', function () {
        var target = $(this).text()
        localStorage.setItem('targetUser', target);
        window.location.href = 'chat.html';
    });

    if (window.location.href.indexOf('chat.html') >= 0) {

        var activeUser = localStorage.getItem('activeUser');
        var targetUser = localStorage.getItem('targetUser');

        getChats(activeUser, targetUser);

        // Check the last messages every 2 seconds
        window.setInterval(function () {
            getLastChats(activeUser, targetUser);
        }, 2000);

        //Send message
        $('#sendButton').click(function () {
            var text = $('#chatText').val();
            $.getJSON("ajax/chat.php", {
                user1: activeUser,
                user2: targetUser,
                text: text
            }, function (data) {
                getLastChats(activeUser, targetUser);
            });

        });
    }

    //Gets all Chats between the activeUser and the targetUser
    //and appends them to the table
    function getChats(activeUser, targetUser) {
        $('table').find('tbody').find('tr').remove();
        $.getJSON("ajax/chats.php", {
            user1: activeUser,
            user2: targetUser
        }, function (data) {
            $.each(data, function (i, item) {
                var tr = $('<tr>').append(
                        $('<td>').text(item.user1 + " sagte(" + item.time + "):"))
                    .append($('<td>').text(item.text));
                $('table').find('tbody').append(tr);
            });

            localStorage.setItem('lastUpdate', data[data.length - 1].id);
        });
    }

    // Gets the last Chat message that hasn't been loaded yet.
    // Checks if the Id of the JSON responses match. 
    function getLastChats(activeUser, targetUser) {
        $.getJSON("ajax/chats.php", {
            user1: activeUser,
            user2: targetUser
        }, function (data) {
            var lastSaved = localStorage.getItem('lastUpdate');
            if (lastSaved != data[data.length - 1].id) {
                var newestItem = data.length - 1;
                var $tr = $('<tr>').append(
                        $('<td>').text(data[newestItem].user1 + " sagte(" + data[newestItem].time + "):"))
                    .append($('<td>').text(data[newestItem].text));
                $('table').find('tbody').append($tr);
                localStorage.removeItem('lastUpdate');
                localStorage.setItem('lastUpdate', data[data.length - 1].id);
            }
        });

    }

});
