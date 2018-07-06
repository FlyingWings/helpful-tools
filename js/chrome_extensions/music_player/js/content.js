//功能1: 自动打开B站动态
page_bi = location.href == "https://www.bilibili.com/"

if(page_bi == 1){
    chrome.runtime.sendMessage({
        open: 1
    }, function(res){
        console.log(res)
    });
}
function transform_img_src(src){
    return src.replace("_s.jpg", "_b.jpg"); //for zhihu
}

// 利用Unique set保存Images
function store_local(src, page_key){

    if(!localStorage.getItem("images")){
        localStorage.setItem("images", JSON.stringify(new Array(src)))
        s = new Set(src)
    }else{
        p = localStorage.getItem("images");
        s = new Set(JSON.parse(p))
    }



    if(!s.has(src)){
        s.add(src);
        p = [];
        s.forEach(function(i){
            if(i != undefined){
                p.push(i);
            }
        });
        localStorage.setItem("images", JSON.stringify(p));
    }else{
        // skip it
    }
}

function filter_src(src){
    return /^http[s]?:\/\//.test(src);
}


// 功能2: 监听页面变化，发图
function send_imgs(){
    // 获取server name
    page_key = /^http[s]?:\/\/(.*?)\//.exec(location.href)
    page_key = page_key[1]

    var imgs = document.querySelectorAll("img");
    imgs.forEach(function(i){
        if(filter_src(i.src)){
            src = transform_img_src(i.src)
            store_local(src, page_key);
        }
    });
    if(localStorage.getItem("images").length >0){
        chrome.runtime.sendMessage({
            imgs: localStorage.getItem("images"),
            url: page_key
        },function(res){
            console.log("imgs sent, total: "+imgs.length)
        });
    }else{
        console.log("Not image found");
    }

}

send_imgs();

MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

var observer = new MutationObserver(function(mutations, observer) {
    send_imgs();
});

// define what element should be observed by the observer
// and what types of mutations trigger the callback
observer.observe(document, {
    subtree: true,
    attributes: true
    //...
});