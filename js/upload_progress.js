function upload_files()
{
    var bar = $('#bar');
    var percent = $('#percent');
    $('#uploadForm').ajaxForm({
            beforeSubmit: function() {
                document.getElementById("progress_div").style.display="block";
                var percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },

            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },

            success: function() {
                var percentVal = '100%';
                bar.width(percentVal)
                percent.html(percentVal);
            },

            complete: function(xhr) {
                progresscell = "";
                if(xhr.responseText)
                {
                    msg = xhr.responseText;

                    if (msg.startsWith("success:")) {
                        splitmsg = msg.split(';');
                        for (result in splitmsg) {
                            progressindexkey = splitmsg[result].split(":")[1];
                            if (progressindexkey != undefined) {
                                progressindex = progressarray[progressindexkey]
                                progresscell = filetable.rows[progressindex + 1].cells[3];
                                progresscell.innerHTML = "Success";
                            }
                            console.log(progressindexkey + " " + progressindex);
                        }
                    } else if (msg.startsWith("failed:")) {
                        splitmsg = msg.split(';');
                        for (result in splitmsg) {
                            progressindexkey = splitmsg[result].split(":")[1];
                            if (progressindexkey != undefined) {
                                progressindex = progressarray[progressindexkey]
                                progresscell = filetable.rows[progressindex + 1].cells[3];
                                progresscell.innerHTML = "Failed";
                            }
                            console.log(progressindexkey + " " + progressindex);
                        }
                    } else if (msg.startsWith("exists:")) {
                        splitmsg = msg.split(';');
                        for (result in splitmsg) {
                            progressindexkey = splitmsg[result].split(":")[1];
                            if (progressindexkey != undefined) {
                                progressindex = progressarray[progressindexkey]
                                progresscell = filetable.rows[progressindex + 1].cells[3];
                                progresscell.innerHTML = "File already exists!";
                            }
                            console.log(progressindexkey + " " + progressindex);
                        }
                    } else {
                        console.log(msg);
                    }
                }
            }
    }   );
}
