// 加载动画
function loading_start(item) {
    var loading_tmp = '<div class="loading"><span></span><span></span><span></span><span></span><span></span></div>';
	item.append(loading_tmp);
}
function loading_finish(item) {
    item.children('.loading').remove();
}

// 获取meta信息
function getMetaContentByName(name) {
  	return (document.getElementsByName(name)[0] || 0).content;
}

// 消息推送
function createMessage(message,time=1000) {
	if ($(".message").length > 0) {
		$(".message").remove();
	}
	$("body").append('<div class="message"><p class="message-info">' + message + '</p></div>');
	setTimeout("$('.message').remove()", time);
}

// 复制到剪切板
function CopyToClipboard(input) {
    var textToClipboard = input;
    var success = true;
    if (window.clipboardData) {
        window.clipboardData.setData ("Text", textToClipboard);
    }
    else {
        var forExecElement = CreateElementForExecCommand (textToClipboard);
        SelectContent (forExecElement);
        var supported = true;
        try {
            if (window.netscape && netscape.security) {
                netscape.security.PrivilegeManager.enablePrivilege ("UniversalXPConnect");
            }
            success = document.execCommand ("copy", false, null);
        } catch (e) {
            success = false;
        }
        document.body.removeChild (forExecElement);
    }
    if (success) {
        createMessage("复制成功！你可以直接粘贴！");
    } else {
        createMessage("抱歉，你的浏览器不支持使用剪切板!");
    }
}
function CreateElementForExecCommand (textToClipboard) {
    var forExecElement = document.createElement ("div");
    forExecElement.style.position = "absolute";
    forExecElement.style.left = "-10000px";
    forExecElement.style.top = "-10000px";
    forExecElement.textContent = textToClipboard;
    document.body.appendChild (forExecElement);
    forExecElement.contentEditable = true;
    return forExecElement;
}
function SelectContent (element) {
    var rangeToSelect = document.createRange ();
    rangeToSelect.selectNodeContents (element);
    var selection = window.getSelection ();
    selection.removeAllRanges ();
    selection.addRange (rangeToSelect);
}


function getVersion(version) {
	version = version.toString().split('.').join('');
	if (version.length == 3)
		version = parseInt(version);
	else if (version.length == 2)
		version = parseInt(version)*10;
	return version;
}
