function sendFileToServer(formData,status)
{
  Finder.itemsUploading++;
  var uploadURL ="/controllers/finderController/uploads.php"; //Upload URL
  var extraData ={}; //Extra Data.
  var jqXHR=$.ajax({
    xhr: function() {
      var xhrobj = $.ajaxSettings.xhr();
      if (xhrobj.upload) {
        xhrobj.upload.addEventListener('progress', function(event) {
          var percent = 0;
          var position = event.loaded || event.position;
          var total = event.total;
          if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
          }
          //Set progress
          status.setProgress(percent);
        }, false);
      }
      return xhrobj;
    },
    url: uploadURL,
    type: "POST",
    contentType:false,
    processData: false,
    cache: false,
    data: formData,
    success: function(data){
      status.setProgress(100);
      Finder.itemsUploading--;
      if (Finder.itemsUploading === 0) {
        new PNotify({
          text: 'Tots els fitxers s\'han pujat.',
          type: 'success'
        });
        $("#uploadsButton").hide();
        Finder.changeFolder(Finder.currentPath, false);
      }
      $("#status1").append("File upload Done<br>");
    }
  });

  status.setAbort(jqXHR);
}

var rowCount=0;
function createStatusbar(obj)
{

  /*

  <div class="itemUpload even">
  <div class="nameUpload">Fitxer 1</div>
  <div class="progressUpload">45%</div>
  <div class="sizeUpload">1,73Kb</div>
  </div>

  */
  rowCount++;
  var row="odd";
  if(rowCount %2 ==0) row ="even";
  this.statusbar = $("<div class='itemUpload "+row+"'></div>");
  this.filename = $("<div class='nameUpload'></div>").appendTo(this.statusbar);
  this.abort = $("<div class='abortUpload'>Aturar</div>").appendTo(this.statusbar);
  this.progressBar = $("<div class='progressUpload progress progress-striped light active'><div class='progress-bar progress-bar-dark' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%;'></div></div>").appendTo(this.statusbar);
  this.size = $("<div class='sizeUpload'></div>").appendTo(this.statusbar);


  $(".uploadsContainer").append(this.statusbar);

  this.setFileNameSize = function(name,size)
  {
    var sizeStr="";
    var sizeKB = size/1024;
    if(parseInt(sizeKB) > 1024)
    {
      var sizeMB = sizeKB/1024;
      sizeStr = sizeMB.toFixed(2)+" MB";
    }
    else
    {
      sizeStr = sizeKB.toFixed(2)+" KB";
    }

    this.filename.html(name);
    this.size.html(sizeStr);
  }
  this.setProgress = function(progress)
  {
    var progressBarWidth =progress*this.progressBar.width()/ 100;
    this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
    if(parseInt(progress) >= 100)
    {
      this.abort.hide();
    }
  }
  this.setAbort = function(jqxhr)
  {
    var sb = this.statusbar;
    this.abort.click(function()
    {
      jqxhr.abort();
      sb.hide();
    });
  }
}
function handleFileUpload(files,obj)
{

  $("#uploadsButton").show();

  for (var i = 0; i < files.length; i++)
  {
    filename = files[i].name;
    filesize = files[i].size;
    file = files[i];
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=checkRename",
      method: "POST",
      data: {file: Finder.currentPath + "/" + files[i].name}
    }).done(function (data) {
      if (data === "1") {
        var conf = confirm("El fitxer "+filename+" ja existeix. El vols sobreescriure?");
        if (conf) {
          var fd = new FormData();
          fd.append("path",Finder.currentPath);
          fd.append('file', file);

          var status = new createStatusbar(obj); //Using this we can set progress.
          status.setFileNameSize(filename,filesize);
          sendFileToServer(fd,status);
        }
      } else {
        var fd = new FormData();
        fd.append("path",Finder.currentPath);
        fd.append('file', file);

        var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(filename,filesize);
        sendFileToServer(fd,status);
      }
    });
  }
}
$(document).ready(function()
{
  var obj = $("#finder-main");
  obj.on('dragenter', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
    $(this).css('background-color', '#fabada');
  });
  obj.on('dragover', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
    $(this).css('background-color', '#fabada');
  });
  obj.on('dragleave', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '0px');
    $(this).css('background-color', '#FFFFFF');
  });
  obj.on('drop', function (e)
  {

    $(this).css('border', '0px');
    $(this).css('background-color', '#FFFFFF');
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;

    //We need to send dropped files to Server

    handleFileUpload(files,obj);
  });
  $(document).on('dragenter', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
  });
  $(document).on('dragover', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
  });
  $(document).on('drop', function (e)
  {
    e.stopPropagation();
    e.preventDefault();
  });

});
