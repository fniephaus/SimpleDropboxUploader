var upload_count = 0;
var done_count = 0;

$(function () {
    'use strict';

    $('#fileupload').fileupload({
        url: 'upload_file.php',
        dataType: 'json',
        submit: function (e, data) {
            data.formData = {access_code: $('#access_code').val(), id: upload_count};
        },
        add: function (e, data) {
            upload_count++;
            if(data.files.length>0){
                $("#uploads").show();
                $("#upload_count").text(upload_count);
                $.each(data.files, function (index, file) {
                    var row = '<tr id="file_' + upload_count + '">' +
                    '<td class="process_count text-center">' + upload_count + '</td>' +
                    '<td id="filename_' + upload_count + '">/' + file.name + '</td>' +
                    '<td class="process_status"><span class="label label-warning pull-right">Processing</span></td>' +
                    '</tr>';
                    $('#file_list tbody').append(row);
                });
            }
            data.submit();
        },
        done: function (e, data) {
            if(data.result.files && data.result.files.length>0){
                $.each(data.result.files, function (index, file) {
                    var label = $('#file_' + file.id + ' td .label');
                    label.removeClass('label-warning');
                    if(file.error){
                        console.log(file.error);
                        label.addClass('label-danger');
                        label.html('Failed');
                    } else {
                        $('#filename_' + file.id).text(file.name);
                        label.addClass('label-success');
                        label.text('Done');
                    }
                    done_count++;
                });
                if(upload_count==done_count){
                    $("#progress").removeClass('active');
                    $("#progress").removeClass('progress-striped');
                    $("#upload_status").text('Upload done!');
                }
            }else if(data.result.error){
                $("#progress").removeClass('active');
                $("#progress").removeClass('progress-striped');
                $("#upload_status").text('Upload failed!');
                var label = $('.process_status .label');
                label.removeClass('label-warning');
                label.addClass('label-danger');
                label.html('Failed');
            }
        },
        start:  function (e, data) {
            $("#progress").show();
            $("#upload_status").text('Upload started...');
        },
        progressall: function (e, data) {

            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
            $("#upload_progress").text(progress + '%');
            if(data.loaded < data.total){
                $("#upload_speed").text(formatBitrate(data.bitrate));
            }else{
                $("#upload_status").text('Uploading to Dropbox...');
            }
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


    $(document).bind('dragover', function (e) {
        var dropZone = $('#dropzone'),
            timeout = window.dropZoneTimeout;
        if (!timeout) {
            dropZone.addClass('in');
        } else {
            clearTimeout(timeout);
        }
        var found = false,
            node = e.target;
        do {
            if (node === dropZone[0]) {
                found = true;
                break;
            }
            node = node.parentNode;
        } while (node != null);
        if (found) {
            dropZone.addClass('hover');
        } else {
            dropZone.removeClass('hover');
        }
        window.dropZoneTimeout = setTimeout(function () {
            window.dropZoneTimeout = null;
            dropZone.removeClass('in hover');
        }, 100);
    });

});

function convertFilename(filename) {
    return filename.trim().replace("/","").replace(/[^a-z0-9]+/gi, '-');
}

function formatBitrate(bits) {
    if (typeof bits !== 'number') {
        return '';
    }
    if (bits >= 1000000000) {
        return (bits / 1000000000).toFixed(2) + ' Gbit/s';
    }
    if (bits >= 1000000) {
        return (bits / 1000000).toFixed(2) + ' Mbit/s';
    }
    if (bits >= 1000) {
        return (bits / 1000).toFixed(2) + ' kbit/s';
    }
    return bits.toFixed(2) + ' bit/s';
}

    