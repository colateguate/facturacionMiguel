<link rel="stylesheet" href="/css/jquery.contextMenu.css">
<link rel="stylesheet" href="/css/finder.css">
<link rel="stylesheet" href="/assets/vendor/jstree/themes/default/style.css" />
<link rel="stylesheet" href="/assets/vendor/pnotify/pnotify.custom.css" />
<div class="row">
  <div class="col-md-6 col-lg-12 col-xl-6">
    <section class="panel">
      <header class="panel-heading">
        <div class="panel-actions">
          <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
          <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
        </div>

        <h2 class="panel-title">FITXERS</h2>
      </header>
      <div class="panel-body">

        <div class="finder-container" id="finder-container">

          <!-- INICI BARRA D'EINES -->

          <div class="finder-tools">
            <div class="spacer15"></div>
            <div class="iconToolbar noAction" id="prevButton" onclick="Finder.goPrev()" alt="Endarrera"><img src="/img/finder/16x16/actions/prev.png" /></div>
            <div class="iconToolbar noAction" id="nextButton" onclick="Finder.goNext()" alt="Endavant"><img src="/img/finder/16x16/actions/next.png" /></div>
            <div class="spacer15 border-right"></div>
            <div class="spacer15"></div>
            <!--<div class="iconToolbar" alt="Nou fitxer"><img src="/img/finder/16x16/actions/new_file.png" /></div>-->
            <div class="iconToolbar" onclick="Finder.newFolder();" alt="Nova carpeta"><img src="/img/finder/16x16/actions/new_foder.png" /></div>
            <div class="iconToolbar noAction" onclick="Finder.getInfo()" id="infoButton" alt="Informació"><img src="/img/finder/16x16/actions/info.png" /></div>
            <div class="iconToolbar noAction" onclick="Finder.rename()" id="renameButton" alt="Renombrar"><img src="/img/finder/16x16/actions/rename.png" /></div>
            <div class="iconToolbar noAction" onclick="Finder.delete();" id="deleteButton" alt="Esborrar"><img src="/img/finder/16x16/actions/delete.png" /></div>
            <div class="spacer15 border-right"></div>
            <div class="spacer15"></div>
            <div class="iconToolbar noAction" id="downloadButton" alt="Descarregar"><img src="/img/finder/16x16/actions/download.png" onclick="Finder.downloadFiles()"/></div>
            <div class="spacer15 border-right"></div>
            <div class="spacer15"></div>
            <div class="iconToolbar noAction" onclick="Finder.setCopyCutFiles('copy')" id="copyButton" alt="Copiar"><img src="/img/finder/16x16/actions/copy.png" /></div>
            <div class="iconToolbar noAction" onclick="Finder.setCopyCutFiles('cut')" id="cutButton" alt="Tallar"><img src="/img/finder/16x16/actions/cut.png" /></div>
            <div class="iconToolbar noAction" onclick="Finder.checkOverwrite()" id="pasteButton" alt="Enganxar"><img src="/img/finder/16x16/actions/paste.png" /></div>
            <div class="iconToolbar iconRight" onclick="Finder.openUploads()" id="uploadsButton" style="display:none;" alt="Enganxar"><img src="/img/finder/16x16/actions/upload.gif" /></div>
          </div>
          <a class="modal-new-folder" href="#modalNewFolder" style="display:none" id="clickerNewFolder"></a>
          <a class="modal-delete" href="#modalDelete" style="display:none" id="clickerDelete"></a>
          <a class="modal-rename" href="#modalRename" style="display:none" id="clickerRename"></a>
          <a class="modal-overwrite" href="#modalConfirmOverwrite" style="display:none" id="clickerOverwrite"></a>
          <a class="modal-alert-rename" href="#modalAlertRename" style="display:none" id="clickerAlertRename"></a>
          <a class="modal-info" href="#modalInfo" style="display:none" id="clickerInfo"></a>
          <a class="modal-upload" href="#modalConfirmOverwriteUpload" style="display:none" id="clickerUpload"></a>
          <!-- FI BARRA D'EINES -->

          <div class="clear"></div>
          <div class="finder-aside">
            <div id="treeBasic">
              <ul>
                <li data-jstree='{ "icon" : "/img/finder/16x16/places/home.png","selected" : true }' class="folder-list-droppable" >
                  <a onclick="Finder.changeFolder('/')">Home</a>
                  <ul id='treeHome'>

                  </ul>
                </li>
                <li data-jstree='{ "icon" : "/img/finder/16x16/places/trash.png" }'>
                  Paperera
                </li>
                <li class="colored" data-jstree='{ "icon" : "/img/finder/16x16/places/network.png" }'>
                  Compartit
                </li>
              </ul>
            </div>
          </div>
          <div class="finder-main" id="finder-main" >

          </div>
          <div class="uploadsContainer">

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<div id="modalNewFolder" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Carpeta nova</h2>
    </header>
    <div class="panel-body">
      <div class="form-group mt-lg">
        <label class="col-sm-3 control-label">Nom de la carpeta</label>
        <div class="col-sm-9">
          <input type="text" name="nameFolder" id="nameFolder" class="form-control" placeholder="Nom de la carpeta" required/>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-primary modal-confirm modal-confirm-new-folder" id="acceptNewFolder">Acceptar</button>
          <button class="btn btn-default modal-dismiss">Cancelar</button>
        </div>
      </div>
    </footer>
  </section>
</div>

<div id="modalDelete" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Esborrar</h2>
    </header>
    <div class="panel-body">
      <div class="modal-wrapper">
        <div class="modal-icon">
          <i class="fa fa-question-circle"></i>
        </div>
        <div class="modal-text">
          <p>Estàs a punt d'esborrar la selcció. Estàs segur?</p>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-primary modal-confirm-delete">SI</button>
          <button class="btn btn-default modal-dismiss">NO</button>
        </div>
      </div>
    </footer>
  </section>
</div>
<div id="modalRename" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Renombrar</h2>
    </header>
    <div class="panel-body">
      <div class="modal-wrapper">
        <div class="form-group mt-lg">
          <label class="col-sm-3 control-label">Nou nom</label>
          <div class="col-sm-9">
            <input type="text" name="name" class="form-control" id="renameField"/>
          </div>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-primary modal-confirm-rename" id="renameButton">ACCEPTAR</button>
          <button class="btn btn-default modal-dismiss">CANCELAR</button>
        </div>
      </div>
    </footer>
  </section>
</div>
<div id="modalAlertRename" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Renombrar</h2>
    </header>
    <div class="panel-body">
      <div class="modal-wrapper">
        <div class="modal-icon">
          <i class="fa fa-question-circle"></i>
        </div>
        <div class="modal-text">
          <p>El nom que vols posar ja existeix</p>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-default modal-dismiss">ACCEPTAR</button>
        </div>
      </div>
    </footer>
  </section>
</div>
<div id="modalInfo" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Informacio</h2>
    </header>
    <div class="panel-body">
      <div class="form-group mt-lg">
        <label class="col-sm-4 control-label"><strong>Nom del fitxer:</strong></label>
        <div class="col-sm-8">
          <span id="fileNameLabel"></span>
        </div>
      </div>
      <div class="form-group mt-lg">
        <label class="col-sm-4 control-label"><strong>Tamany:</strong></label>
        <div class="col-sm-8">
          <span id="fileSizeBytesLabel"></span>B (<span id="fileSizeFormatLabel"></span>)
        </div>
      </div>
      <div class="form-group mt-lg">
        <label class="col-sm-4 control-label"><strong>Mimetype:</strong></label>
        <div class="col-sm-8">
          <span id="fileMimeLabel"></span>
        </div>
      </div>
      <div class="form-group mt-lg" id="ctime-container">
        <label class="col-sm-4 control-label"><strong>Data de creació:</strong></label>
        <div class="col-sm-8">
          <span id="fileCreationLabel"></span>
        </div>
      </div>
      <div class="form-group mt-lg" id="mtime-container">
        <label class="col-sm-4 control-label"><strong>Data de modificació:</strong></label>
        <div class="col-sm-8">
          <span id="fileModifyLabel"></span>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-default modal-dismiss">ACCEPTAR</button>
        </div>
      </div>
    </footer>
  </section>
</div>

<div id="modalConfirmOverwrite" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Confirmació</h2>
    </header>
    <div class="panel-body">
      <div class="modal-wrapper">
        <div class="modal-icon">
          <i class="fa fa-question-circle"></i>
        </div>
        <div class="modal-text">
          <p>Un o més fitxers existeixen al destí. Vols sobreescriure?</p>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-primary modal-confirm-overwrite">SI</button>
          <button class="btn btn-default modal-dismiss">NO</button>
        </div>
      </div>
    </footer>
  </section>
</div>
<div id="modalConfirmOverwriteUpload" class="modal-block modal-block-primary mfp-hide">
  <section class="panel">
    <header class="panel-heading">
      <h2 class="panel-title">Confirmació</h2>
    </header>
    <div class="panel-body">
      <div class="modal-wrapper">
        <div class="modal-icon">
          <i class="fa fa-question-circle"></i>
        </div>
        <div class="modal-text">
          <p>El fitxer <span id="fileUploadName"></span> ja existeix. Vols sobreescriure?</p>
        </div>
      </div>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-right">
          <button class="btn btn-primary modal-confirm-upload">SI</button>
          <button class="btn btn-default modal-dismiss">NO</button>
        </div>
      </div>
    </footer>
  </section>
</div>




<script src="/assets/vendor/jstree/jstree.js"></script>
<script src="/js/jquery.contextMenu.js"></script>
<script src="/js/jquery.ui.position.min.js"></script>

<script src="/js/finder.js"></script>
<script src="/js/finder.dragdrop.js"></script>
