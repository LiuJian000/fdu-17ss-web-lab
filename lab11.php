<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LRC 歌词编辑器</title>
    <style>
        .bold{
            font-weight:bold;
            color:red;
        }
        #lyric2{
            list-style:none;
            overflow:hidden;
            border:black 1px solid;
            border-radius:10px;
            height:500px;
            width:700px;
            display:block;
            margin:0px;
            padding:0;

        }
        .move{
            position:relative;
            top:210px;
            left:0;
        }
        #lyric2 div li{
            padding:0;
            margin:0;
            height:45px;
            width:100%;
            text-align:center;line-height:100%;
        }
        #lyric2 li p{
            padding:0;
            margin:0;
            line-height:100%;
        }
        nav ul {
            position: fixed;
            z-index: 99;
            right: 5%;
            border: 1px solid darkgray;
            border-radius: 5px;
            list-style:none;
            padding: 0;
        }
        .tab {
            padding: 1em;
            display: block;
        }
        .tab:hover {
            cursor: pointer;
            background-color: lightgray !important;
        }
        td {
            padding:0.2em;
        }
        textarea[name="edit_lyric"] {
            width: 100%;
            height: 50em;
        }
        input[type="button"] {
            width: 100%;
            height: 100%;
        }
        input[type="submit"] {
            width: 100%;
            height: 100%;
        }
        #td_submit {
            text-align: center;
        }
        select {
            display: block;
        }
        #lyric {
            width: 35%;
            height: 60%;
            border: 0;
            resize: none;
            font-size: large;
            line-height: 2em;
            text-align: center;
        }
    </style>
</head>
<body>
<nav><ul>
        <li id="d_edit" class="tab">Edit Lyric</li>
        <li id="d_show" class="tab">Show Lyric</li>
    </ul></nav>

<!--歌词编辑部分-->
<section id="s_edit" class="content">
    <form id="f_upload" enctype="multipart/form-data" action = 'submit.php' method = 'post'>
        <p>请上传音乐文件</p>

        <!--TODO: 在这里补充 html 元素，使 file_upload 上传后若为音乐文件，则可以直接播放-->
        <audio id = "audio"src = "" controls="controls" autoplay="autoplay"></audio><br/>
        <input type="file" name="file_upload" onchange = "document.getElementById('audio').src = window.URL.createObjectURL(this.files[0]); "/>
        <table>
            <tr><td>Title: <input type="text"></td><td>Artist: <input type="text"></td></tr>
            <tr><td colspan="2"><textarea name="edit_lyric" id="edit_lyric"></textarea></td></tr>
            <tr><td><input type="button" value="插入时间标签" onclick = 'insert(get_target_pos());'></td><td><input type="button" value="替换时间标签" onclick = 'change(get_target_pos())'></td></tr>
            <tr><td colspan="2" id="td_submit"><input type="submit" value="Submit"></td></tr>
        </table>
    </form>
</section>
<!--歌词展示部分-->
<section id="s_show" class="content">
    <select id = "select" onchange = "selectChange();">
        <!--TODO: 在这里补充 html 元素，使点开 #d_show 之后这里实时加载服务器中已有的歌名-->
        <?php
        echo readfile("upload/list.lst");
        ?>
    </select>
    <audio id = "audio2" src = "" controls="controls" autoplay="autoplay" ontimeupdate = "movelrc();"></audio><br/>
    <button id = "bt1" onclick = "bt1Click();">上一首</button>
    <button id = "bt2" onclick = "bt2Click();">下一首</button>
    <ul id = "lyric2"><div class = "move" id = 'ul2'>
            <li class = "bold"><br/></li><li></li><li></li><li></li>
        </div></ul>
    <textarea id="lyric" readonly="true">
    </textarea>
    <!--TODO: 在这里补充 html 元素，使选择了歌曲之后这里展示歌曲进度条，并且支持上下首切换-->
</section>
</body>
<script type = "text/javascript" src = "jquery-3.3.1.js"></script>
<script>
    // 界面部分
    document.getElementById("d_edit").onclick = function () {click_tab("edit");audio2.src = ''};
    document.getElementById("d_show").onclick = function () {click_tab("show");selectChange();document.getElementById('audio').src='';};
    function click_tab(tag) {
        for (let i = 0; i < document.getElementsByClassName("tab").length; i++) document.getElementsByClassName("tab")[i].style.backgroundColor = "transparent";
        for (let i = 0; i < document.getElementsByClassName("content").length; i++) document.getElementsByClassName("content")[i].style.display = "none";
        document.getElementById("s_" + tag).style.display = "block";
        document.getElementById("d_" + tag).style.backgroundColor = "darkgray";
    }
    // Edit 部分
    var edit_lyric_pos = 0;
    document.getElementById("edit_lyric").onmouseleave = function () {
        edit_lyric_pos = document.getElementById("edit_lyric").selectionStart;
    };
    // 获取所在行的初始位置。
    function get_target_pos() {
        let n_pos = edit_lyric_pos;
        let value = document.getElementById("edit_lyric").value;
        let pos = 0;
        for (let i = n_pos; i >= 0; i--) {
            if (value.charAt(i) === '\n') {
                pos = i + 1;
                break;
            }
        }
        return pos;
    }
    // 选中所在行。
    function get_target_line(n_pos) {
        let value = document.getElementById("edit_lyric").value;
        let f_pos = get_target_pos(n_pos);
        let l_pos = 0;
        for (let i = f_pos;; i++) {
            if (value.charAt(i) === '\n') {
                l_pos = i + 1;
                break;
            }
        }
        return [f_pos, l_pos];
    }
    function getTime(){
        let time = document.getElementById('audio').currentTime;
        let str = "[";
        let temp;
        temp = parseInt(time/3600);
        str += printf(temp)+":";
        time -= temp*3600;
        temp = parseInt(time/60);
        str += printf(temp)+":";
        time -= temp*60;
        temp = parseInt(time);
        str += printf(temp)+".";
        time -= temp;
        temp = parseInt(time*60);
        str += printf(temp);
        str+=']';
        return str;
    }
    function printf(number){
        if(number<10)
            return "0" + number;
        else
            return number;
    }
    let lrcArea = document.getElementById("edit_lyric");
    let former;
    let latter;
    let s;
    function insert(n){
        s = lrcArea.selectionStart + 13;
        former = lrcArea.value.substring(0,n);
        latter = lrcArea.value.substring(n,lrcArea.value.length);
        lrcArea.value = former + getTime() + latter;
        lrcArea.focus();
        lrcArea.selectionStart = s;
        lrcArea.selectionEnd = s;
    }
    function change(n){
        s = lrcArea.selectionStart;
        former = lrcArea.value.substring(0,n);
        latter = lrcArea.value.substring(n+13,lrcArea.value.length);
        lrcArea.value = former + getTime() + latter;
        lrcArea.focus();
        lrcArea.selectionStart = s;
        lrcArea.selectionEnd = s;
    }
    function btSubmit(){
        let name = document.getElementsByTagName('input')[0].files[0].name;
        name = name.substring(0,name.lastIndexOf('.'))+'.lrc';
    }
    /* HINT:
     * 已经帮你写好了寻找每行开头的位置，可以使用 get_target_pos()
     * 来获取第一个位置，从而插入相应的歌词时间。
     * 在 textarea 中，可以通过这个 DOM 节点的 selectionStart 和
     * selectionEnd 获取相对应的位置。
     *
     * TODO: 请实现你的歌词时间标签插入效果。
     */
    /* TODO: 请实现你的上传功能，需包含一个音乐文件和你写好的歌词文本。
     */
    /* HINT:
     * 实现歌词和时间的匹配的时候推荐使用 Map class，ES6 自带。
     * 在 Map 中，key 的值必须是字符串，但是可以通过字符串直接比较。
     * 每一行行高可粗略估计为 40，根据电脑差异或许会有不同。
     * 当前歌词请以粗体显示。
     * 从第八行开始，当歌曲转至下一行的时候，需要调整滚动条，使得当前歌
     * 词保持在正中。
     *
     * TODO: 请实现你的歌词滚动效果。
     */
    let select = document.getElementById('select');
    let audio2 = document.getElementById('audio2');
    let lrc;
    let i = 0;
    let lTime = new Array();
    let lWords = new Array();
    let lrcOutput = '';
    let lists;
    let ul2 = document.getElementById('ul2');
    let temp;
    let temp2=0;
    function showLyrics(name){
        console.log("lrc/"+name+".lrc");
        lrc = $.ajax({url:(("lrc/"+name+".lrc")),async:false});
        lrc = lrc.responseText;
        lrc = lrc.split(/(\[\d\d:\d\d:\d\d.\d\d\])/);

        while(!((/\[\d\d:\d\d:\d\d.\d\d\]/).test(lrc[i]))){
            i++;

        }
        for(;i<lrc.length-1;i=i+2){

            temp = lrc[i].substr(1,11).split(':');
            temp2 = 0;
            for(j=0;j<temp.length;j++){
                temp2 += temp[j]*Math.pow(60,temp.length - 1 - j);
            }
            lTime.push(temp2);
            lWords.push(lrc[i+1]);
        }
        for(i=0;i<lWords.length;i++){
            lrcOutput += '<li>'+lWords[i]+'</li>';
        }
        ul2.innerHTML = lrcOutput;
        i=0;
        lists = ul2.children;
    }
    let j = 0;
    function movelrc(){
        lists[j].className = '';
        for(i=0;i<lTime.length;i++){
            if(audio2.currentTime>=lTime[i]&&audio2.currentTime<=lTime[i+1]){
                lists[i].className = 'bold';
                ul2.style.top = (210-i*45)+'px';
                j=i;
                break;
            }
        }
    }
    function selectChange(){
        console.log(select.value.substr(0,select.value.lastIndexOf('.')));
        showLyrics(select.value.substr(0,select.value.lastIndexOf('.')));
        audio2.src = 'upload/'+select.value;
    }
    function bt1Click(){
        temp = $('#select option:selected').prev();
        if(temp.length == 0){
            document.getElementById('bt1').disabled = true;
            return;
        }
        else{
            document.getElementById('bt1').disabled = false;
            return;
        }
        select.value = temp.value;
    }
    function bt2Click(){
        temp = $('#select option:selected').next();
        if(temp.length == 0){
            document.getElementById('bt2').disabled = true;
            return;
        }
        else{
            document.getElementById('bt2').disabled = false;
            return;
        }
        select.value = temp.value;
    }
    temp = $('#select option:selected').prev();
    if(temp.length == 0)
        document.getElementById('bt1').disabled = true;
    temp = $('#select option:selected').next();
    if(temp.length == 0)
        document.getElementById('bt2').disabled = true;
    document.getElementById("d_show").click();
</script>
</html>