function fetchUsers() {

    $.ajax({
        url: "/fetchUsers",
        type: "GET",
        data: { _token: token },
        success: function(response) {

            var data = response.messages;
            var length = data.length;
            var html = '';
            for (var i = 0; i < length; i++) {
                var user_id = data[i].user_id;
                var user_name = data[i].user_name;
                var token = data[i].token;
                var message = data[i].message;
                var time = data[i].time;
                var pic = data[i].user_avatar;

                html += `<div class="chat_list" id="${token}" to_user_id="${user_id}">`;
                html += `<div class="chat_people">`;

                html += `<div class="chat_img"> <img src="${pic}" width="70" height="50" alt="sunil" class="img-circle"> </div>`;

                html += `<div class="chat_ib">`;

                html += `
                <h5> ${user_name} <span class="chat_date">${time}</span></h5>
                <p>${message}</p>`;

                html += `</div>`;

                html += `</div>`;
                html += `</div>`;
            }
            $(".inbox_chat").html(html);
            handleClick();
        }
    });

}

function handleClick() {
    $(".chat_list").each(function() {
        $(this).on("click", function() {
            localStorage.setItem("updown_chat_mate_id", $(this).attr("to_user_id"));
            fetchMessages(localStorage.getItem('updown_chat_mate_id'));
        });
    });
}

function fetchMessages(mate_id) {
    if (mate_id) {
        $.ajax({
            url: "/fetchMessages",
            type: "GET",
            data: { _token: token, mate_id: mate_id },
            success: function(response) {
                var name = response.name;
                var myID = response.myID;
                var pic = response.pic;
                var messages = response.messages;
                var length = messages.length;
                $("#chat_mate_name").empty();
                $("#chat_mate_name").show();
                $("#chat_mate_name").html(name);
                $("#chat_mate_name").attr("mate_id", mate_id);
                var messagesHtml = '';
                for (var i = 0; i < length; i++) {
                    if (messages[i].from_user_id == myID) {
                        messagesHtml += `
                        <div class="outgoing_msg">
                        <div class="sent_msg">
                          <p>${messages[i].message}</p>
                          <span class="time_date"> ${messages[i].time} </span> </div>
                      </div>
                        `;
                    } else {
                        messagesHtml += `
                        <div class="incoming_msg" style="margin-bottom:10px">
                        <div class="incoming_msg_img"> <img src="${pic}" width="54" height="54" class="img-circle" alt="sunil"> </div>
                        <div class="received_msg">
                          <div class="received_withd_msg">
                            <p>${messages[i].message}</p>
                            <span class="time_date"> ${messages[i].time}</span></div>
                        </div>
                      </div>
                        `;
                    }
                }


                $(".msg_history").html(messagesHtml);
                $('.msg_history').scrollTop($('.msg_history')[0].scrollHeight);
            }
        });
    }
}

function updateChatHistory() {
    $('.chat_list').each(function() {
        var to_user_id = localStorage.getItem("updown_chat_mate_id");
        fetchMessages(to_user_id);
    });
}

$("#msgForm").on("submit", function(e) {
    e.preventDefault();
    var form = $(this);
    var formdata;
    formdata = new FormData(form[0]);
    var mate_id = $("#chat_mate_name").attr('mate_id');
    var file_length = $("#file")[0].files.length;
    formdata.append("to_user_id", mate_id);
    if (file_length == 0) {
        $.ajax({
            url: $(this).attr("action"),
            type: 'POST',
            data: formdata,
            enctype: 'multipart/form-data',
            processData: false, // Important!
            contentType: false,
            cache: false,
            success: function(response) {
                $("#message").val("");
                form[0].reset();
            }
        });
    } else {
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                $(".progress").show();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: $("#msgForm").attr("action"),
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            enctype: "multipart/form-data",
            beforeSend: function() {
                $(".progress-bar").width('0%');
                $('#uploadStatus').html('<center><img width="100" height="100" src="' + loading + '"/></center>');
            },
            error: function() {
                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
            },
            success: function(response) {
                if (response == 'INSERTED') {
                    $('#msgForm')[0].reset();
                    $(".progress").css("display", 'none');
                    $("#uploadStatus").empty();
                } else {
                    $('#msgForm')[0].reset();
                    $(".progress").css("display", 'none');
                    $("#uploadStatus").empty();
                    alert(response);
                }
            }
        });
    }


});


fetchUsers();

setInterval(function() {
    fetchUsers();
    updateChatHistory();
    var chat_list = $(".chat_list[to_user_id]").attr("to_user_id");
    var mate_id = localStorage['updown_chat_mate_id'];
    $(".chat_list[name*='" + mate_id + "']").addClass("chat_active");
}, 2000);