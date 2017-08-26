(function() {
    tinymce.PluginManager.add('my_mce_button', function(editor, url) {
        editor.addButton('my_mce_button', {
          	text: 'Memory主题特色功能',
            icon: false,
            type: 'menubutton',
            menu: [{
                    text: '插入自定义链接',
                    onclick: function() {
                        editor.windowManager.open({
                            title: '插入自定义链接',
                            minWidth: 700,
                            body: [{
                                    type: 'listbox',
                                    name: 'ljlx',
                                    label: '请选择链接类型',
                                    values: [{
                                            text: '普通链接（带样式）',
                                            value: ''
                                        },
                                        {
                                            text: '普通链接（无样式）',
                                            value: 'no-des no-bg'
                                        },
                                        {
                                            text: '@',
                                            value: 'at'
                                        }
                                    ]
                                },
                                {
                                    type: 'textbox',
                                    name: 'ljurl',
                                    label: '请输入URL',
                                    value: '链接URL',
                                },
                                {
                                    type: 'textbox',
                                    name: 'ljwb',
                                    label: '请输入链接文本',
                                    value: '链接文本',
                                }
                            ],
                            onsubmit: function(e) {
                              	var sp = (e.data.addspaces ? '&nbsp;' : '');
                                editor.insertContent(sp + '<a class="' + e.data.ljlx + '" href="' + e.data.ljurl + '">' + e.data.ljwb + '</a>' + sp + '<p></p>');
                            }
                        });
                    }
                },{
                    text: '插入友链',
                    onclick: function() {
                        editor.windowManager.open({
                            title: '插入友链',
                            minWidth: 700,
                            body: [{
                                    type: 'textbox',
                                    name: 'yldz',
                                    label: '请输入友链地址',
                                    value: '',
                                },
                                {
                                    type: 'textbox',
                                    name: 'yltp',
                                    label: '请输入图片地址',
                                    value: '',
                                },
                                {
                                    type: 'textbox',
                                    name: 'ylmc',
                                    label: '请输入友链名称',
                                    value: '友链名称',
                                },
                                {
                                    type: 'textbox',
                                    name: 'ylms',
                                    label: '请输入友链描述',
                                    value: '友链描述',
                                  	multiline: true,
                                    minWidth: 300,
                                    minHeight: 60
                                }
                            ],
                            onsubmit: function(e) {
                              	var sp = (e.data.addspaces ? '&nbsp;' : '');
                              	editor.insertContent(sp + '[flink href="' + e.data.yldz + '" name="' + e.data.ylmc + '" des="' + e.data.ylms + '" imgsrc="' + e.data.yltp + '"]' + sp);
                            }
                        });
                    }
                },
                {
                    text: '代码语言',
                    onclick: function() {
                        editor.windowManager.open({
                            title: '代码标签',
                            minWidth: 700,
                            body: [{
                                    type: 'textbox',
                                    name: 'lang',
                                    label: '语言类型',
                                    value: '语言类型',
                                },
                                {
                                    type: 'textbox',
                                    name: 'code',
                                    label: '代码',
                                    value: '请输入您的代码......',
                                    multiline: true,
                                    minWidth: 300,
                                    minHeight: 100
                                }
                            ],
                            onsubmit: function(e) {
                                var code = e.data.code.replace(/\r\n/gmi, '\n'),
                                    code = tinymce.html.Entities.encodeAllRaw(code);
                                var sp = (e.data.addspaces ? '&nbsp;' : '');
                                editor.insertContent(sp + '<pre><span class="pre-title">' + e.data.lang + '</span><code class="hljs' + ' ' + e.data.lang + '">' + code + '</code></pre>' + sp + '<p></p>');
                            }
                        });
                    }
                }
            ]
        });
    });
})();