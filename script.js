$(document).ready(function () {

    $('#loginButton').click(function () {
        var username = $('#username').val();
        //document.getElementById('username').value;
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
            // response = $.JSON.stringify(data);
            //console.log(response);
            $(function () {
                $.each(data, function (i, item) {
                    var $tr = $('<tr>').append(
                        $('<td>').text(item)
                    ).css("cursor", "pointer"); //.appendTo('#records_table');
                    console.log($tr.wrap('<p>').html());
                    $('#userTable').find('tbody').append($tr);
                });
            });
        });
    }

    $('#userTable').on('click', 'td', function () {
        var target = $(this).text()
        localStorage.setItem('targetUser', target);
        window.location.href = 'chat.html';
    });

    if (window.location.href.indexOf('chat.html') >= 0) {

        var activeUser = localStorage.getItem('activeUser');
        var targetUser = localStorage.getItem('targetUser');

        getChats(activeUser, targetUser);
        window.setInterval(function () {
            getLastChats(activeUser, targetUser);
        }, 2000);


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


    function getChats(activeUser, targetUser) {
        $('table').find('tbody').find('tr').remove();
        $.getJSON("ajax/chats.php", {
            user1: activeUser,
            user2: targetUser
        }, function (data) {
            $(function () {
                $.each(data, function (i, item) {
                    var $tr = $('<tr>').append(
                            $('<td>').text(item.user1 + " sagte(" + item.time + "):"))
                        .append($('<td>').text(item.text));
                    console.log($tr.wrap('<p>').html());
                    $('table').find('tbody').append($tr);
                });
            });

            localStorage.setItem('lastUpdate', data[data.length - 1].id);
        });
    }


    function getLastChats(activeUser, targetUser) {
        $.getJSON("ajax/chats.php", {
            user1: activeUser,
            user2: targetUser
        }, function (data) {
            var lastSaved = localStorage.getItem('lastUpdate');
            if (lastSaved != data[data.length - 1].id) {
                var newestItem = data.length - 1;
                var $tr = $('<tr>').append(
                        $('<td>').text(data[newestItem].user1 + " sagte(" + item.time + "):"))
                    .append($('<td>').text(data[newestItem].text));
                //console.log($tr.wrap('<p>').html());
                $('table').find('tbody').append($tr);
                localStorage.removeItem('lastUpdate');
                localStorage.setItem('lastUpdate', data[data.length - 1].id);
            }
        });

    }

});