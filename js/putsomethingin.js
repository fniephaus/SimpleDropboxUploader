$(function () {
    'use strict';

    var upload_count = 0;
    var done_count = 0;

    $('#fileupload').fileupload({
        url: 'upload_file.php',
        dataType: 'json',
        add: function (e, data) {
            upload_count++;
            if(data.files.length>0){
                $("#uploads").show();
                $("#upload_count").text(upload_count);
                $.each(data.files, function (index, file) {
                    var row = '<tr class="file_' + convertFilename(file.name) + '">' +
                    '<td class="text-center">' + upload_count + '</td>' +
                    '<td>' + file.name + '</td>' +
                    '<td class="process_status"><span class="label label-warning pull-right">Processing</span></td>' +
                    '</tr>';
                    $('#file_list tbody').append(row);
                });
            }
            data.submit();
        },
        done: function (e, data) {
            if(data.files.length>0){
                $.each(data.files, function (index, file) {
                    var label = $('.file_' + convertFilename(file.name) + ' td .label');
                    label.removeClass('label-warning');
                    label.addClass('label-success');
                    label.text('Done');
                    done_count++;
                });
                if(upload_count==done_count){
                    $("#progress").removeClass('active');
                    $("#progress").removeClass('progress-striped');
                    $("#upload_status").text('Upload done!');
                }
            }
        },
        fail: function (e, data) {
            console.log(data);
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
    return filename.trim().replace(/[^a-z0-9]+/gi, '-');
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

    