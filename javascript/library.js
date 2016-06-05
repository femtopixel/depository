function test()
{
	alert('Library Successfully Loaded !');
}

function help()
{
	Dialog.alert($('messagebox').innerHTML,  {windowParameters: {className: "alphacube"}, okLabel: "Fermer"});
}

function rules()
{
	Dialog.alert($('rules').innerHTML,  {windowParameters: {className: "alphacube"}, okLabel: "Fermer"});
}

function activateEditFilename(file)
{
	$('edit'+file).onclick = function ()
		{
			AjaxLoad('texteditor');
			new Ajax.Updater('texteditor', './ajax/showeditor.ajax.php?value='+file,
				{
					evalScripts:'true',
					onComplete: function (t)
					{
						Dialog.alert(t.responseText, {windowParameters: {className: "alphacube"}, okLabel: "Annuler"});
					}
				}
			);
		};
}

function saveModif(obj)
{
	var value;
	value = obj.myvalue.value;
	Dialog.okCallback();
	new Ajax.Request('./ajax/savemodif.ajax.php',
		{
			method:'post',
			postBody:'value='+escape(value)
		});
}

Ajax.InPlaceExternal = Class.create();
Object.extend(Ajax.InPlaceExternal.prototype, Ajax.InPlaceEditor.prototype);
Object.extend(Ajax.InPlaceExternal.prototype, 
	{
		onclickCancel: function()
		{
			this.onComplete();
			this.leaveEditMode();
			this.dispose();
			return false;
		}
	}
	);
           
function AjaxLoad(div)
{
	$(div).innerHTML = '<img src="./images/ajax-load.gif" />';
}

function getDir(dir)
{
  HideCompatibility();
	AjaxLoad('work');
	new Ajax.Updater('work', './ajax/showfolder.ajax.php',
	{
		method:'post',
		postBody:'folder='+dir,
		evalScripts:'true',
		onComplete: function (t)
      {
        LoadContextMenu();
      }
	});
}

function startFolderDrop(folder)
{
	Droppables.add(folder, 
		{
			hoverclass: 'bordered',
			onDrop: function (t)
				{
					DropInFolder(t, folder);
				}
		});
}

function DropInFolder(t, folder)
{
  var isfolder;
	var temp;
	
  if (t.getAttribute('className') == 'folder' || 
    t.getAttribute('className') == 'copieurfolder' || 
    t.getAttribute('className') == 'coupeurfolder' ||
    t.getAttribute('class') == 'folder' || 
    t.getAttribute('class') == 'copieurfolder' || 
    t.getAttribute('class') == 'coupeurfolder')
    isfolder = 1;
  else
    isfolder = 0;
    
  if (t.getAttribute('className') == 'folder' || 
    t.getAttribute('className') == 'file' || 
    t.getAttribute('className') == 'coupeurfile' || 
    t.getAttribute('className') == 'coupeurfolder' ||
    t.getAttribute('class') == 'folder' || 
    t.getAttribute('class') == 'file' || 
    t.getAttribute('class') == 'coupeurfile' || 
    t.getAttribute('class') == 'coupeurfolder')
  {
    temp = t.id.replace('coupeur', '');
    new Ajax.Request('./ajax/movefile.ajax.php', 
      {
        method:'post',
        postBody:'start='+temp+'&stop='+folder+'&isfolder='+isfolder,
        onComplete:function (t)
          {
            getDir($('mydir').value);
          }
      }
      );
  }
  else if (t.getAttribute('className') == 'copieurfile' ||
        t.getAttribute('className') == 'copieurfolder' ||
        t.getAttribute('class') == 'copieurfile' ||
        t.getAttribute('class') == 'copieurfolder')
  {
    temp = t.id.replace('copieur', '');
    new Ajax.Request('./ajax/copyfile.ajax.php', 
      {
        method:'post',
        postBody:'start='+temp+'&stop='+folder+'&isfolder='+isfolder,
        onComplete:function (t)
          {
            getDir($('mydir').value);
          }
      }
      );
  }
}

function startFileDrag(file)
{
	new Draggable(file, 
		{
			revert:true,
			starteffect: function (t)
				{
					new Effect.Opacity(file, {duration:0.1, from:1.0, to:0.5});
				}
		});
}

function activateRenameFilename(filename)
{
	$('rename'+filename).onclick = function (t)
		{
			editor = new Ajax.InPlaceExternal('link'+filename, './ajax/editfilename.ajax.php?start='+filename,
				{
					onComplete: function (t)
					{
						if ($(filename).getAttribute('className') == 'folder' ||
							$(filename).getAttribute('class') == 'folder')
							getDir($('mydir').value);
					}
				});
			editor.enterEditMode('click');
		}
}

function deleteFile(file)
{
	var isdir;
	var confirmed;
	if ($(file).getAttribute('className') == 'folder' ||
		$(file).getAttribute('class') == 'folder')
	{
		isdir = 1;
		confirmed = confirm("Etes vous sure de vouloir supprimer ce dossier ?");
	}
	else
	{
		confirmed = confirm("Etes vous sure de vouloir supprimer ce fichier ?");
		isdir = 0;
	}
	
	if (confirmed)
	{
		if ($(file).getAttribute('className') == 'folder' ||
			$(file).getAttribute('class') == 'folder')
			isdir = 1;
		else
			isdir = 0;
		new Ajax.Request('./ajax/deletefile.ajax.php', 
			{
				method:'post',
				postBody:'value='+file+'&isdir='+isdir,
				onComplete: function (t)
					{
						getDir($('mydir').value);
					}
			});
	}
}

function activateDeleteFilename(file)
{
	$('delete'+file).onclick = function (t)
		{
			deleteFile(file);
		}
}

function activateSaveFilename(file)
{
	$('save'+file).onclick = function (t)
		{
			window.location = './ajax/savefile.ajax.php?value='+file;
		}
}

function activateShowFilename(file)
{
	var title;
	$('show'+file).onclick = function (t)
		{
			title = $('show'+file).getAttribute('title');
			Dialog.alert("<h1>"+title+"</h1><br/><div class='alphacube_buttons'><input type='button' onclick='Dialog.okCallback()' value='Fermer'/></div><img src='./ajax/showimage.ajax.php?value="+file+"' alt='Image' /><div class='separator'></div>",  {windowParameters: {className: "alphacube"}, okLabel: 'Fermer'});
		}
}

var copieur = new Array();
function activateCopieur()
{
	var i;
	Droppables.add('copieur', 
		{
			hoverclass: 'bordered',
			accept:['folder','file'],
			onDrop: function (t)
			{
        AddInCopieur(t);
			}
		});
}

function AddInCopieur(t)
{
  if (typeof(copieur[t.id]) == 'undefined')
  {
    if (copieur.length == 0)
      $('copieur').innerHTML = '';
    copieur[t.id] = 1;
    copieur.length++;
    element = document.createElement('span');
    
    element.id = 'copieur'+ t.id;
    element.setAttribute('className', 'copieur'+t.getAttribute('className'));
    element.setAttribute('class', 'copieur'+t.getAttribute('class'));
    element.innerHTML = unescape(t.id).replace('/', ' /') + '&nbsp;&nbsp;<a href=\'javascript:deleteur("'+escape(element.id)+'");\'><img src="./images/redcross.png"/></a><br/>';
    $('copieur').appendChild(element);
    startFileDrag(element.id);
  }
  else
    alert('Ce fichier est deja dans le copieur !');
}

var coupeur = new Array();
function activateCoupeur()
{
	var i;
	Droppables.add('coupeur', 
		{
			hoverclass: 'bordered',
			accept:['folder','file'],
      onDrop: function (t)
        {
          AddInCoupeur(t)
        }
		});
}

function AddInCoupeur(t)
{
  if (typeof(coupeur[t.id]) == 'undefined')
  {
    if (coupeur.length == 0)
      $('coupeur').innerHTML = '';
    coupeur[t.id] = 1;
    coupeur.length++;
    element = document.createElement('span');
    
    element.id = 'coupeur'+ t.id;
    element.setAttribute('className', 'coupeur'+t.getAttribute('className'));
    element.setAttribute('class', 'coupeur'+t.getAttribute('class'));
    element.innerHTML = unescape(t.id).replace('/', ' /') + '&nbsp;&nbsp;<a href=\'javascript:deleteur("'+escape(element.id)+'");\'><img src="./images/redcross.png"/></a><br/>';
    $('coupeur').appendChild(element);
    startFileDrag(element.id);
  }
  else
    alert('Ce fichier est deja dans le coupeur !');
}

function basename(path)
{
	path = unescape(path);
    var filename = path.split('/');
    if (filename.length == 1) {
        var filename = path.split("\\");    
    }
    filename = filename[filename.length-1];
    return filename;
}

function deleteur(file)
{
	var length;
	if ($(file).getAttribute('className') == 'copieurfolder' ||
		$(file).getAttribute('className') == 'copieurfile' ||
		$(file).getAttribute('class') == 'copieurfolder' ||
		$(file).getAttribute('class') == 'copieurfile'
		)
	{
		length = copieur.length;
		copieur = unset(copieur, file.replace('copieur', ''));
		copieur.length = length - 1;
	}
	else if ($(file).getAttribute('className') == 'coupeurfolder' ||
			  $(file).getAttribute('className') == 'coupeurfile' || 
			  $(file).getAttribute('class') == 'coupeurfolder' ||
			  $(file).getAttribute('class') == 'coupeurfile')
	{
		length = coupeur.length;
		coupeur = unset(coupeur, file.replace('coupeur', ''));
		coupeur.length = length - 1;
	}
	
	var temp;
		
	$(file).parentNode.removeChild($(file));
	if (copieur.length == 0)
		$('copieur').innerHTML = 'Glissez les &eacute;l&eacute;ments &agrave; copier ici...';
	if (coupeur.length == 0)
		$('coupeur').innerHTML = 'Glissez les &eacute;l&eacute;ments &agrave; couper ici...';
}

function unset(array, valueOrIndex){
	var output = new Array();
	for(var i in array){
		if (i!=valueOrIndex)
			output[i]=array[i];
	}
	return output;
}

function createFolder(obj)
{
	var folder;
	folder = obj.folder.value;
	new Ajax.Request('./ajax/createfolder.ajax.php',
		{
			method:'post',
			postBody:'value='+escape(folder),
			onComplete:function (t)
				{
					getDir($('mydir').value);
				}
		});
	return (false);
}

function submitFile(obj)
{
	$('tohide').style.display = 'none';
	$('toshow').style.display = 'block';
	return (true);
}

function verifForm(form)
{
	var inputs = form.getElementsByTagName('input');
	var length = inputs.length;
	var i;
	var rel;
	for (i = 0; i < length - 1; i++)
	{
		rel = inputs[i].getAttribute('lang');
		if (inputs[i].value == '' && rel != 'ignore')
		{
			alert('Tous les champs doivent etre renseigne !');
			return (false);
		}
	}
	return (true);
}

function deleteUser(id)
{
	if (id != 0 && confirm("Etes vous sur de vouloir supprimer cet utilisateur ?"))
	{
		AjaxLoad('users');
		new Ajax.Request('./ajax/deleteuser.ajax.php', 
		{
			method:'post',
			postBody:'id_user='+id,
			onComplete: function (t)
				{
					window.location = './Users.php';
				}
		});
	}
}

function deleteClient(id)
{
	if (id != 0 && confirm("Etes vous sur de vouloir supprimer ce client et tous ses utilisateurs ?"))
	{
		AjaxLoad('users');
		new Ajax.Request('./ajax/deleteclient.ajax.php', 
		{
			method:'post',
			postBody:'id_user='+id,
			onComplete: function (t)
				{
					window.location = './Clients.php';
				}
		});
	}
}

function CopierCollerShow(id)
{
  IDaTraiter = (navigator.appName.substring(0,3) == "Net") ? id : window.event.srcElement.id;
  HideCompatibility();
  $('contextcopiercoller').style.left=MouseX+'px';
  $('contextcopiercoller').style.top=MouseY+'px';
  $('contextcopiercoller').style.visibility="visible"
  return false
}

function CopierCollerHide()
{
  $('contextcopiercoller').style.visibility="hidden"
}
 
function HideCompatibility()
{
  if ($('contextcopiercoller'))
    $('contextcopiercoller').style.visibility="hidden"
  if ($('contextcopier'))
    $('contextcopier').style.visibility="hidden"
}

function CopierShow(id)
{
  IDaTraiter = (navigator.appName.substring(0,3) == "Net") ? id : window.event.srcElement.id;
  HideCompatibility();
  $('contextcopier').style.left=MouseX+'px';
  $('contextcopier').style.top=MouseY+'px';
  $('contextcopier').style.visibility="visible"
  return false
}

function CopierHide()
{
  $('contextcopier').style.visibility="hidden"
}

function MousePositionHandler(e)
{
  MouseX = (navigator.appName.substring(0,3) == "Net") ? e.pageX : event.clientX+document.body.scrollLeft;
	MouseY = (navigator.appName.substring(0,3) == "Net") ? e.pageY : event.clientY+document.body.scrollTop;/**/
}
	
function StartContextMenuHandler()
{
    if(navigator.appName.substring(0,3) == "Net")
      document.captureEvents(Event.MOUSEMOVE);
    document.onmousemove = MousePositionHandler;
}
  
function StartContextCopierColler()
{
  elements = getElementsByClass('folder')
  for(obj in elements)
  {
    obj = elements[obj];
    if (typeof(obj) == 'object')
    {
     if (navigator.appName.substring(0,3) == "Net")
        obj.setAttribute('oncontextmenu', "return CopierCollerShow('"+obj.id+"');");
     else
        obj.attachEvent('oncontextmenu', CopierCollerShow);
    }
  }
  
  if (elements.length > 0)
  {
    adder = $('work').getAttribute('onclick');
    if (navigator.appName.substring(0,3) == "Net")
    {
      document.getElementsByTagName('body')[0].setAttribute('onclick', "CopierCollerHide();"+adder);
    }
    else
    {
      document.getElementsByTagName('body')[0].attachEvent('onclick', CopierCollerHide);
    }
  }
    
  context = document.createElement('div')
  context.id = 'contextcopiercoller';
  context.innerHTML = '';
  context.innerHTML += '<div class="menuitems" onclick="ContextCopier();">Copier</div>';
  context.innerHTML += '<div class="menuitems" onclick="ContextCouper();">Couper</div>';
  context.innerHTML += '<div class="menuitems" onclick="ContextColler();">Coller</div>';
  document.getElementsByTagName('body')[0].appendChild(context);
}
   
function StartContextCopier()
{
  elements = getElementsByClass('file')
  for(obj in elements)
  {
    obj = elements[obj];
    if (typeof(obj) == 'object')
    {
     if (navigator.appName.substring(0,3) == "Net")
        obj.setAttribute('oncontextmenu', "return CopierShow('"+obj.id+"');");
     else
        obj.attachEvent('oncontextmenu', CopierShow);
    }
  }
  if (elements.length > 0)
  {
    adder = $('work').getAttribute('onclick');
    if (navigator.appName.substring(0,3) == "Net")
    {
      document.getElementsByTagName('body')[0].setAttribute('onclick', "CopierHide();"+adder);
    }
    else
    {
      document.getElementsByTagName('body')[0].attachEvent('onclick', CopierHide);
    }
  }
    
  context = document.createElement('div')
  context.id = 'contextcopier';
  context.innerHTML = '';
  context.innerHTML += '<div class="menuitems" onclick="ContextCopier();">Copier</div>';
  context.innerHTML += '<div class="menuitems" onclick="ContextCouper();">Couper</div>';
  document.getElementsByTagName('body')[0].appendChild(context);
}
  
function LoadContextMenu()
{
  StartContextMenuHandler();
  StartContextCopierColler();
  StartContextCopier();
}

function getElementsByClass(searchClass,node,tag)
{
  var classElements = new Array();
  if ( node == null )
    node = document;
  if ( tag == null )
    tag = '*';
  var els = node.getElementsByTagName(tag);
  var elsLen = els.length;
  var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
  for (i = 0, j = 0; i < elsLen; i++)
  {
    if ( pattern.test(els[i].className) )
    {
      classElements[j] = els[i];
      j++;
    }
  }
  return classElements;
}

function ContextCopier()
{
  HideCompatibility();
  IDaTraiter = IDaTraiter.replace('link', '');
  AddInCopieur($(IDaTraiter));
  IDaColler = 'copieur'+IDaTraiter;
}

function ContextColler()
{
  HideCompatibility();
  IDaTraiter = IDaTraiter.replace('link', '');
  DropInFolder($(IDaColler), IDaTraiter);
}

function ContextCouper()
{
  HideCompatibility();
  IDaTraiter = IDaTraiter.replace('link', '');
  AddInCoupeur($(IDaTraiter));
  IDaColler = 'coupeur'+IDaTraiter;
}

function refreshWorkingDirectory()
{
	HideCompatibility();
	AjaxLoad('work');
	new Ajax.Updater('work', './ajax/showfolder.ajax.php',
	{
		method:'post',
		evalScripts:'true',
		onComplete: function (t)
      {
        LoadContextMenu();
      }
	});
}

function activateAjaxFileUpload()
{
	new Ajax.FileUpload('ajaxfileupload', "./ajax/uploadfile.ajax.php", {
		loadingText: '<img src="./images/ajax-load-black.gif" /> Uploading <name>',
		finishText: '<img src="./images/valid.jpg" /> <name> uploaded !',
		onComplete: function () {
			id = 'ajaxfileupload_div_'+this.id;
			new Effect.Fade(id, {
				duration:3
			});
			refreshWorkingDirectory();
		}
	});
}