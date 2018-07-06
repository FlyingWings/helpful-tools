chrome.runtime.onMessage.addListener(function(msg, sender, response){
    if(msg.open == 1){
        chrome.tabs.create({url: "https://t.bilibili.com", pinned: true});
        response({hi:1})
    }

    if(msg.imgs != undefined){
        localStorage.setItem("imgs:"+msg.url, msg.imgs);// 同步远程数据
        response({ack:1})
    }


});