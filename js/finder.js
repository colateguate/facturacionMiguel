var Finder = {
  /*
  * Declaració de variables
  *
  */
  currentPath: "/",
  selectedItems: [],
  clipboardItems: [],
  clipboardAction: "none",
  prevPathArray: [],
  nextPathArray: [],
  itemsUploading: 0,

  /**
  *
  * @param string path Path el qual es vol mostrar al visor de fiters
  * @param boolean first Si es el primer moment d'entrar per tal que no guardi al historic el primer path
  *
  * Funcio per canviar el contingut del visor de fitxers segons el path que se li demani
  */
  changeFolder: function (path, first) {
    $("#finder-main").html("");

    $.ajax({
      url: "/controllers/finderController/ajax.php?action=getDirContents",
      dataType: 'json',
      method: "POST",
      data: {dir: path}
    }).done(function (data) {
      for (var i = 0; i < data.length; i++) {
        Finder.unselectAll();
        var ITEM = data[i];

        //Generem el div que serà la icona de un fitxer

        div = document.createElement("div");
        $(div).addClass("fileContainer")
        .addClass("fileDraggable")
        .attr("data-type", ITEM.type)
        .attr("data-name", ITEM.name)
        .attr("data-size", ITEM.size)
        .attr("data-fsize", ITEM.fsize)
        .attr("data-ctime", ITEM.ctime)
        .attr("data-mtime", ITEM.mtime)
        .attr("data-mime", ITEM.mime)
        .click(function (e) {
          if (!e)
          e = window.event;
          // Posem el cancelBubble per tal que la acció de click no s'extengui al div contenidor de fitxers, per que no faci la acció de des-seleccionar
          e.cancelBubble = true;
          if (e.stopPropagation)
          e.stopPropagation();
          e.preventDefault();

          // Comprovem si la tecla CTRL o WIN/CMND està apretada per poder fer multi-select o no
          if (e.ctrlKey || e.metaKey) {
            var index = Finder.selectedItems.indexOf(Finder.currentPath + "/" + $(this).data("name"));
            if (index === -1) {
              Finder.selectedItems.push(Finder.currentPath + "/" + $(this).data("name"));
              $(this).addClass("fileSelected");
            } else {
              Finder.selectedItems.splice(index, 1);
              $(this).removeClass("fileSelected");
            }

            // Afegim el item al array de seleccionats i li posem la clase per marcar que ho està


          } else {
            // Al fer click a un sol fitxer eliminem tots els altres seleccionats i marquem l'altre
            Finder.selectedItems = [];
            Finder.selectedItems.push(Finder.currentPath + "/" + $(this).data("name"));
            $(".fileContainer").each(function () {
              $(this).removeClass("fileSelected");
            });
            $(this).addClass("fileSelected");
          }
          // Desbloquejem les accions per fitxer
          $("#infoButton").removeClass("noAction");
          $("#deleteButton").removeClass("noAction");
          $("#downloadButton").removeClass("noAction");
          $("#renameButton").removeClass("noAction");
          $("#copyButton").removeClass("noAction");
          $("#cutButton").removeClass("noAction");
          $("#pasteButton").removeClass("noAction");


        })
        .dblclick(function () {
          // Accio de doble click si es una carpeta per navegarhi
          if ($(this).data("type") === "folder") {
            Finder.addPrevHistory();
            Finder.changeFolder(Finder.currentPath + "/" + $(this).data("name"), false);

            Finder.nextPathArray = [];
            $("#nextButton").addClass("noAction");

          }
        });
        //                        .draggable({
        //                            revert: true,
        //                            helper: 'clone',
        //                            start: function (event, ui) {
        //                                $(this).css("cursor", "default");
        //                            },
        //                            stop: function (event, ui) {
        //                                $(this).css("cursor", "default");
        //                            }
        //                        });

        img = document.createElement("img");
        $(img).attr('src', "/img/finder/32x32/mime/" + data[i].type + ".png")
        .appendTo($(div));

        br = document.createElement("br");
        $(br).appendTo(div);

        span = document.createElement("span");
        $(span).addClass("textFile")
        .html(data[i].name)
        .appendTo($(div));

        $("#finder-main").append(div);

      }
      $("#finder-main").append(' <div class="endFileSection"></div>');
      $("#finder-main").append(' <div id="fileDragZone" display="none"></div>');
      path = path.replace("///", "/");
      Finder.currentPath = path;

      Finder.initDrag();

    });
  },

  /**
  *
  * Funcio per carregar el arbre de carpetes de la vista del lateral
  */
  getTreeFolder: function () {
    $("#treeHome").html("");
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=getTreeFolder",
      method: "POST"
    }).done(function (data) {
      $("#treeHome").html(data);
      $('#treeBasic').jstree({
        'core': {
          'themes': {
            'responsive': false
          }
        },
        'types': {
          'default': {
            'icon': 'fa fa-folder'
          },
          'file': {
            'icon': 'fa fa-file'
          }
        },
        'plugins': ['types']
      });
    });


  },
  reloadTreeFolder: function () {
    $("#treeHome").html("");
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=getTreeFolder",
      method: "POST"
    }).done(function (data) {
      var treeNodes = '<div id="treeBasic"><ul>' +
      '<li data-jstree=\'{ "icon" : "/img/finder/16x16/places/home.png","selected" : true }\' >' +
      '<a onclick="Finder.changeFolder(\'/\')">Home</a>' +
      '<ul id="treeHome">' +
      data +
      '</ul>' +
      '</li>' +
      '<li data-jstree=\'{ "icon" : "/img/finder/16x16/places/trash.png" }\'>' +
      'Paperera' +
      '</li>' +
      '<li class="colored" data-jstree=\'{ "icon" : "/img/finder/16x16/places/network.png" }\'>' +
      'Compartit' +
      '</li>' +
      '</ul></div>';

      $(".finder-aside").html(treeNodes);
      $('#treeBasic').jstree({
        'core': {
          'themes': {
            'responsive': false
          }
        },
        'types': {
          'default': {
            'icon': 'fa fa-folder'
          },
          'file': {
            'icon': 'fa fa-file'
          }
        },
        'plugins': ['types']
      });
    });


  },

  /**
  *
  * Funcio inicialitzadora del finder, obté les carpetes i carrega el visor a la arrel
  */

  init: function () {
    this.getTreeFolder();
    this.changeFolder("/", true);


    /**
    *
    * Bind per fer que es puguin des-selecionar fitxers si es clica a l'area blanca del contenidor
    *
    */

    $("#finder-main").click(function (e) {

      Finder.unselectAll();
      // If the clicked element is not the menu
      if (!$(e.target).parents(".custom-menu").length > 0) {

        // Hide it
        $(".custom-menu").hide(100);
      }
    });

    /**
    *
    * Funcio per crear i mostrar el menu contextual, diferenciant si es sobre l'area blanca o un fitxer
    */
    $(function () {

      $.contextMenu({
        selector: '.finder-main',
        build: function ($trigger, e) {
          var tipo = e.target.parentElement.className;

          if (tipo.includes("fileContainer")) { // CLICK SOBRE ICONES
            if (!$(e.target.parentElement).hasClass("fileSelected")) {
              $(e.target.parentElement).addClass("fileSelected");
              Finder.selectedItems.push(Finder.currentPath + "/" + $(e.target.parentElement).data("name"));
            }
            var arrItems = {};
            if (Finder.selectedItems.length === 1) {
              arrItems.rename = {name: "Renombrar", icon: "edit"};
            }
            arrItems.download = {name: "Descarregar", icon: "fa-download"};
            arrItems.cut = {name: "Tallar", icon: "cut"};
            arrItems.copy = {name: "Copiar", icon: "copy"};
            arrItems.delete = {name: "Esborrar", icon: "delete"};
            arrItems.sep1 = "---------";
            arrItems.info = {name: "Informació", icon: "fa-info"};
            return {
              callback: function (key, options) {
                if (key === "delete") {
                  Finder.delete();
                } else if (key === 'copy') {
                  Finder.setCopyCutFiles('copy');
                } else if (key === 'cut') {
                  Finder.setCopyCutFiles('cut');
                } else if (key === 'paste') {
                  Finder.checkOverwrite();
                } else if (key === "download") {
                  Finder.downloadFiles();
                } else if (key === "rename") {
                  Finder.rename();
                } else if (key === "info") {
                  Finder.getInfo();
                }
              },
              items: arrItems
            };
          } else { // CLICK SOBRE EL CONTENIDOR
            var arrItems = {};
            if (Finder.clipboardItems.length === 1) {
              arrItems.paste = {name: "Enganxar", icon: "paste"};
            }
            arrItems.new_folder = {name: "Nova carpeta", icon: "add"};
            return {
              callback: function (key, options) {

                if (key === "new_folder") {
                  Finder.newFolder();
                } else if (key === "paste") {
                  Finder.checkOverwrite();
                }

                var m = "clicked: " + key;
              },
              items: arrItems
            };
          }

        }
      });
      // Funcio per assignar el modal de nova carpeta al div
      $('.modal-new-folder').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#nameFolder',
        modal: true

      });
      $('.modal-delete').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true

      });
      $('.modal-overwrite').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true

      });
      $('.modal-rename').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#renameField',
        modal: true

      });
      $('.modal-alert-rename').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true

      });
      $('.modal-info').magnificPopup({
        type: 'inline',
        preloader: false,
        modal: true

      });

      $("#nameFolder").on('keydown', function(e) {
        if (e.which == 13) {
          $("#acceptNewFolder").click();
          e.preventDefault();
        }
      });

      $("#renameField").on('keydown', function(e) {
        if (e.which == 13) {
          $("#renameButton").click();
          e.preventDefault();
        }
      });

    });



    $(".modal-confirm-new-folder").on('click', function (e) {
      e.preventDefault();
      $.magnificPopup.close();

      Finder.confirmNewFolder();

    });

    $(".modal-confirm-delete").on('click', function (e) {
      e.preventDefault();
      $.magnificPopup.close();
      Finder.confirmDeletes();
    });
    $(".modal-confirm-overwrite").on('click', function (e) {
      e.preventDefault();
      $.magnificPopup.close();
      Finder.confirmCutCopy();
    });
    $(".modal-confirm-rename").on('click', function (e) {
      e.preventDefault();
      $.magnificPopup.close();
      Finder.confirmRename();
    });

    /*
    Modal Dismiss
    */
    $(document).on('click', '.modal-dismiss', function (e) {
      e.preventDefault();
      $.magnificPopup.close();
    });


    //        $(".folder-list-droppable").droppable({
    //            accept: ".fileDraggable",
    //            over: function (event, ui) {
    //                alert("YEGA")
    //                if (event.ctrlKey || event.metaKey)
    //                {
    //
    //                    ui.draggable.css("cursor", "alias");
    //                } else {
    //
    //                    ui.draggable.css("cursor", "cell");
    //                }
    //                $(this).css('background-color', "lime");
    //            },
    //            out: function (event, ui) {
    //                ui.draggable.css("cursor", "no-drop");
    //                $(this).css('background-color', "hotpink");
    //                $(this).html("Drop here");
    //            },
    //            drop: function (event, ui) {
    //                $(this).html("Dropped!");
    //            }
    //        });

  },

  /**
  *
  * Desmarca tots els fitxers seleccionats
  */
  unselectAll: function () {
    Finder.selectedItems = [];
    $(".fileContainer").each(function () {
      $(this).removeClass("fileSelected");
    });


    $("#infoButton").addClass("noAction");
    $("#deleteButton").addClass("noAction");
    $("#downloadButton").addClass("noAction");
    $("#renameButton").addClass("noAction");
    $("#copyButton").addClass("noAction");
    $("#cutButton").addClass("noAction");
  },

  /**
  *
  * Anar cap enrere al historic
  */
  goPrev: function () {
    if (Finder.prevPathArray.length > 0) {
      var prevPath = Finder.prevPathArray[Finder.prevPathArray.length - 1];
      Finder.prevPathArray.splice(-1, 1);
      Finder.changeFolder(prevPath, false);

      Finder.nextPathArray.push(Finder.currentPath);
      $("#nextButton").removeClass("noAction");

      if (Finder.prevPathArray.length === 0) {
        $("#prevButton").addClass("noAction");
      }
    }
  },

  /**
  *
  * Anar cap endavant al historic
  */
  goNext: function () {
    if (Finder.nextPathArray.length > 0) {
      var nextPath = Finder.nextPathArray[Finder.nextPathArray.length - 1];
      Finder.nextPathArray.splice(-1, 1);
      Finder.changeFolder(nextPath, false);

      Finder.prevPathArray.push(Finder.currentPath);
      $("#prevButton").removeClass("noAction");

      if (Finder.nextPathArray.length === 0) {
        $("#nextButton").addClass("noAction");
      }
    }
  },

  /**
  *
  * Afegir un item al array del historic d'enrere
  */
  addPrevHistory: function () {
    Finder.prevPathArray.push(Finder.currentPath);
    $("#prevButton").removeClass("noAction");
  },

  /**
  *
  * Solicitud per descarregar fitxers
  */
  downloadFiles: function () {
    if (Finder.selectedItems.length > 0) {
      var jsonFiles = JSON.stringify(Finder.selectedItems);
      var form = document.createElement("form");
      $(form).attr("action", '/controllers/finderController/ajax.php?action=downloadFiles').attr('method', 'post');
      var input = document.createElement("input");
      $(input).attr('type', 'hidden').attr('name', 'files').val(jsonFiles).appendTo(form);

      $(form).appendTo($(".finder-container"));
      $(form).submit().remove();
    }
  },
  newFolder: function () {
    $("#clickerNewFolder").click();
  },
  confirmNewFolder: function () {
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=newFolder",
      method: "POST",
      data: {dir: Finder.currentPath + "/" + $("#nameFolder").val()}
    }).done(function (data) {
      if (data === "1") {
        Finder.changeFolder(Finder.currentPath);
        Finder.reloadTreeFolder();
        $("#nameFolder").val("");
        new PNotify({
          text: 'Carpeta generada.',
          type: 'success'
        });
      }
    });
  },
  delete: function () {
    if (Finder.selectedItems.length > 0) {
      $("#clickerDelete").click();
    }
  },
  confirmDeletes: function () {
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=deleteSelections",
      method: "POST",
      data: {files: JSON.stringify(Finder.selectedItems)}
    }).done(function (data) {
      if (data === "1") {
        Finder.changeFolder(Finder.currentPath);
        Finder.reloadTreeFolder();
        new PNotify({
          text: 'Selecció eliminada.',
          type: 'success'
        });
      }
    });

  },
  setCopyCutFiles: function (mode) {
    Finder.clipboardItems = Finder.selectedItems;
    Finder.clipboardAction = mode;
    $("#pasteButton").removeClass("noAction");
  },
  checkOverwrite: function () {
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=checkOverwrite",
      method: "POST",
      data: {files: JSON.stringify(Finder.clipboardItems), pasteFolder: Finder.currentPath}
    }).done(function (data) {
      if (data === "1") {
        $("#clickerOverwrite").click();

      } else {
        Finder.confirmCutCopy()
      }
    });
  },
  confirmCutCopy: function () {
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=actionCopyCut",
      method: "POST",
      data: {files: JSON.stringify(Finder.clipboardItems), pasteFolder: Finder.currentPath, mode: Finder.clipboardAction}
    }).done(function (data) {
      if (data === "1") {
        Finder.changeFolder(Finder.currentPath);
        Finder.reloadTreeFolder();
        new PNotify({
          text: 'Moviment realitzat.',
          type: 'success'
        });
        Finder.clipboardAction = "";
        Finder.clipboardItems = [];
        $("#pasteButton").addClass("noAction");
        $("#cutButton").addClass("noAction");
        $("#copyButton").addClass("noAction");
      }
    });
  },
  rename: function () {
    if (Finder.selectedItems.length === 1) {
      $(".fileSelected").data("name");
      $("#renameField").val($(".fileSelected").data("name"));
      $("#clickerRename").click();
    }
  },
  confirmRename: function () {
    $.ajax({
      url: "/controllers/finderController/ajax.php?action=checkRename",
      method: "POST",
      data: {file: Finder.currentPath + "/" + $("#renameField").val()}
    }).done(function (data) {
      if (data === "1") {
        $("#clickerAlertRename").click();
      } else {
        $.ajax({
          url: "/controllers/finderController/ajax.php?action=fileRename",
          method: "POST",
          data: {oldFile: Finder.selectedItems[0], newName: Finder.currentPath + "/" + $("#renameField").val()}
        }).done(function (data) {
          if (data === "1") {

            Finder.changeFolder(Finder.currentPath);
            Finder.reloadTreeFolder();
            new PNotify({
              text: 'Nom de fitxer actualitzat.',
              type: 'success'
            });
            Finder.clipboardAction = "";
            Finder.clipboardItems = [];
            $("#pasteButton").addClass("noAction");
            $("#cutButton").addClass("noAction");
            $("#copyButton").addClass("noAction");
            $("#renameField").val("");
          }
        });
      }
    });
  },
  getInfo: function () {

    if (Finder.selectedItems.length === 1) {
      $("#fileNameLabel").text($(".fileSelected").data("name"));
      $("#fileSizeBytesLabel").text($(".fileSelected").data("size"));
      $("#fileSizeFormatLabel").text(bytesToSize($(".fileSelected").data("size")));
      $("#fileMimeLabel").text($(".fileSelected").data("mime"));
      $("#fileCreationLabel").text($(".fileSelected").data("ctime"));
      $("#fileModifyLabel").text($(".fileSelected").data("mtime"));

      $("#ctime-container").show();
      $("#mtime-container").show();

      $("#clickerInfo").click();
    } else {
      size = 0;
      $(".fileSelected").each(function () {
        size += $(this).data("size");
      });
      $("#fileNameLabel").text(Finder.selectedItems.length + " elements");
      $("#fileSizeBytesLabel").text(size);
      $("#fileSizeFormatLabel").text(bytesToSize(size));
      $("#fileMimeLabel").text("Multiple");
      $("#ctime-container").hide();
      $("#mtime-container").hide();
      $("#clickerInfo").click();
    }

  },
  initDrag: function() {


  },
  openUploads: function () {
    $(".uploadsContainer").toggle();
  }

};


// INICIADOR DEL SCRIPT
$(document).ready(function () {
  Finder.init();
});


function bytesToSize(bytes, decimals) {
  if (bytes == 0)
  return '0 Bytes';
  var k = 1000,
  dm = decimals || 2,
  sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
  i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
